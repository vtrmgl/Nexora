<?php
    require_once 'config/conexao.php';
    require_once 'assets/php/functions.php';

    iniciarSessaoSegura();

    exigirLogin();

    if (!usuarioPodeAcessarDashboard()) {
        redirecionar('perfil.php');
    }

    $csrfToken = gerarTokenCsrf();
    $mensagemSucesso = '';
    $mensagemErro = '';

    $periodo = $_GET['periodo'] ?? '24h';
    $periodosPermitidos = [
        '24h' => 'Últimas 24h',
        '7d'  => 'Últimos 7 dias',
        '30d' => 'Últimos 30 dias',
    ];

    if (!array_key_exists($periodo, $periodosPermitidos)) {
        $periodo = '24h';
    }

    $busca = trim($_GET['busca'] ?? '');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir_usuario') {
        $tokenRecebido = $_POST['csrf_token'] ?? '';
        $idAlvo = (int)($_POST['usuario_id'] ?? 0);
        $autorAcao = $_SESSION['usuario']['email'] ?? 'Sistema';

        if (!validarTokenCsrf($tokenRecebido)) {
            $mensagemErro = 'Token de segurança inválido (CSRF). A operação foi bloqueada.';
        } elseif ($idAlvo <= 0) {
            $mensagemErro = 'Identificador de usuário inválido.';
        } elseif ($idAlvo === (int)($_SESSION['usuario']['id'] ?? 0)) {
            $mensagemErro = 'Você não pode desativar o próprio usuário logado pelo painel administrativo.';
        } else {
            $stmtCheck = $pdo->prepare('SELECT id, nome, sobrenome, email, status FROM usuarios WHERE id = :id LIMIT 1');
            $stmtCheck->execute([':id' => $idAlvo]);
            $usuarioAlvo = $stmtCheck->fetch();

            if (!$usuarioAlvo) {
                $mensagemErro = 'Usuário não encontrado no banco de dados.';
            } elseif ($usuarioAlvo['status'] === 'Inativo'){
                $mensagemErro = 'Este usuário já foi desativado anteriormente.';
            } else {
                try {
                    $pdo->beginTransaction();

                    $stmtInativo = $pdo->prepare(
                        'UPDATE usuarios
                         SET status = "Inativo", atualizado_em = NOW(), desativado_em = NOW()
                         WHERE id = :id AND status = "Ativo"'
                    );
                    $stmtInativo->execute([':id' => $idAlvo]);

                    $stmtAudit = $pdo->prepare(
                        'INSERT INTO auditoria_usuarios (id_usuario_alvo, acao, autor, resultado)
                         VALUES (:alvo, :acao, :autor, :resultado)'
                    );
                    $stmtAudit->execute([
                        ':alvo'      => $idAlvo,
                        ':acao'      => 'INATIVACAO_USUARIO',
                        ':autor'     => $autorAcao,
                        ':resultado' => 'Usuário inativado: ' . $usuarioAlvo['email']
                    ]);

                    $pdo->commit();
                    $mensagemSucesso = 'Usuário "' . $usuarioAlvo['nome'] . ' ' . $usuarioAlvo['sobrenome'] . '" foi marcado como Inativo e registrado na auditoria.';
                } catch (PDOException $e) {
                    $pdo->rollBack();
                    error_log('Erro ao inativar usuário: ' . $e->getMessage());
                    $mensagemErro = 'Erro interno ao processar a exclusão lógica.';
                }
            }
        }
    }

    $condicaoData = match ($periodo) {
        '7d'    => 'p.data_tempo_pedido >= DATE_SUB(NOW(), INTERVAL 7 DAY)',
        '30d'   => 'p.data_tempo_pedido >= DATE_SUB(NOW(), INTERVAL 30 DAY)',
        default => 'p.data_tempo_pedido >= DATE_SUB(NOW(), INTERVAL 24 HOUR)',
    };

    function buscarIndicadores(PDO $pdo, ?int $idLoja, string $condicaoData): array
    {
        if ($idLoja !== null) {
            $sql = "SELECT
                        COUNT(DISTINCT p.id) AS realizados,
                        COUNT(DISTINCT CASE WHEN p.status_pedido IN ('Em rota', 'Em rota de entrega') OR ip.status_produto = 'Enviado' THEN p.id END) AS enviados,
                        COUNT(DISTINCT CASE WHEN p.status_pedido = 'Entregue' OR ip.status_produto = 'Entregue' THEN p.id END) AS entregues,
                        COALESCE(SUM(CASE WHEN p.status_pedido NOT IN ('Cancelado', 'Reembolsado') THEN ip.quantidade * ip.preco_unitario ELSE 0 END), 0) AS renda
                    FROM pedidos p
                    INNER JOIN itens_pedido ip ON ip.id_pedido = p.id
                    WHERE ip.id_loja = :id_loja AND {$condicaoData}";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_loja' => $idLoja]);
        } else {
            $sql = "SELECT
                        COUNT(DISTINCT p.id) AS realizados,
                        COUNT(DISTINCT CASE WHEN p.status_pedido IN ('Em rota', 'Em rota de entrega') THEN p.id END) AS enviados,
                        COUNT(DISTINCT CASE WHEN p.status_pedido = 'Entregue' THEN p.id END) AS entregues,
                        COALESCE(SUM(CASE WHEN p.status_pedido NOT IN ('Cancelado', 'Reembolsado') THEN p.valor_total ELSE 0 END), 0) AS renda
                    FROM pedidos p
                    WHERE {$condicaoData}";
            $stmt = $pdo->query($sql);
        }

        return $stmt->fetch() ?: ['realizados' => 0, 'enviados' => 0, 'entregues' => 0, 'renda' => 0];
    }

    $idLoja = $_SESSION['usuario']['id_loja'] ?? null;
    $indicadoresAtuais = buscarIndicadores($pdo, $idLoja, $condicaoData);

    if ($idLoja !== null) {
        $sqlPedidos = 'SELECT p.id AS pedido_id,
                              TRIM(CONCAT(COALESCE(u.nome, ""), " ", COALESCE(u.sobrenome, ""))) AS cliente_nome,
                              pr.nome AS produto_nome,
                              (ip.quantidade * ip.preco_unitario) AS valor_item,
                              ip.status_produto AS status
                       FROM pedidos p
                       LEFT JOIN usuarios u ON p.id_usuario = u.id
                       INNER JOIN itens_pedido ip ON ip.id_pedido = p.id
                       LEFT JOIN produtos pr ON ip.id_produto = pr.id
                       WHERE ip.id_loja = :id_loja
                       ORDER BY p.data_tempo_pedido DESC
                       LIMIT 5';
        $stmtPed = $pdo->prepare($sqlPedidos);
        $stmtPed->execute([':id_loja' => $idLoja]);
    } else {
        $sqlPedidos = 'SELECT p.id AS pedido_id,
                              TRIM(CONCAT(COALESCE(u.nome, ""), " ", COALESCE(u.sobrenome, ""))) AS cliente_nome,
                              pr.nome AS produto_nome,
                              p.valor_total AS valor_item,
                              p.status_pedido AS status
                       FROM pedidos p
                       LEFT JOIN usuarios u ON p.id_usuario = u.id
                       LEFT JOIN itens_pedido ip ON ip.id_pedido = p.id
                       LEFT JOIN produtos pr ON ip.id_produto = pr.id
                       GROUP BY p.id
                       ORDER BY p.data_tempo_pedido DESC
                       LIMIT 5';
        $stmtPed = $pdo->query($sqlPedidos);
    }
    $pedidosRecentes = $stmtPed->fetchAll();

    $sqlUsuarios = 'SELECT u.id, u.nome, u.sobrenome, u.email, u.status, u.data_cadastro,
                           s.nome AS setor_nome, p.nome AS perfil_nome
                    FROM usuarios u
                    INNER JOIN setores s ON u.id_setor = s.id
                    INNER JOIN perfis p ON u.id_perfil = p.id
                    WHERE u.status = "Ativo"';

    $paramsUsuarios = [];
    if ($busca !== '') {
        $sqlUsuarios .= ' AND (u.nome LIKE :busca_nome OR u.sobrenome LIKE :busca_sobrenome OR u.email LIKE :busca_email)';
        $termo = '%' . $busca . '%';
        $paramsUsuarios[':busca_nome'] = $termo;
        $paramsUsuarios[':busca_sobrenome'] = $termo;
        $paramsUsuarios[':busca_email'] = $termo;
    }
    $sqlUsuarios .= ' ORDER BY u.id DESC';

    $stmtUsers = $pdo->prepare($sqlUsuarios);
    $stmtUsers->execute($paramsUsuarios);
    $usuariosAtivos = $stmtUsers->fetchAll();

    $logsAuditoria = $pdo->query('SELECT * FROM auditoria_usuarios ORDER BY id DESC LIMIT 8')->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Nexora</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include 'paginas/header.php' ?>

    <main>
        <?php if ($mensagemSucesso !== ''): ?>
            <div class="alerta-sucesso" style="width: 80%;"><?= e($mensagemSucesso) ?></div>
        <?php endif; ?>

        <?php if ($mensagemErro !== ''): ?>
            <div class="alerta-erro" style="width: 80%;"><?= e($mensagemErro) ?></div>
        <?php endif; ?>

        <h1 id="texto-indicador" style="width: 80%; text-align: center; margin-top: 0;">
            Indicadores <?= !empty($_SESSION['usuario']['nome_loja']) ? '— ' . e($_SESSION['usuario']['nome_loja']) : '(Geral)' ?>
        </h1>

        <div style="width: 80%; display: flex; justify-content: flex-start; margin-top: 16px; margin-bottom: 14px;">
            <form method="GET" action="dashboard.php" class="filtro-periodo" style="display: flex; align-items: center; gap: 8px;">
                <?php if ($busca !== ''): ?>
                    <input type="hidden" name="busca" value="<?= e($busca) ?>">
                <?php endif; ?>
                <label for="periodo">Período:</label>
                <select name="periodo" id="periodo" onchange="this.form.submit()">
                    <?php foreach ($periodosPermitidos as $valor => $rotulo): ?>
                        <option value="<?= e($valor) ?>" <?= $periodo === $valor ? 'selected' : '' ?>>
                            <?= e($rotulo) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <noscript>
                    <button type="submit" class="btn-nexora-secundario">Filtrar</button>
                </noscript>
            </form>
        </div>

        <div class="indicadores-dashboard">
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Pedidos realizados</p>
                <p id="qtd-preco-metricas-dashboard"><?= (int)$indicadoresAtuais['realizados'] ?></p>
            </div>
            <div class="linha-vertical-metrica-dashboard"></div>
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Pedidos enviados</p>
                <p id="qtd-preco-metricas-dashboard"><?= (int)$indicadoresAtuais['enviados'] ?></p>
            </div>
            <div class="linha-vertical-metrica-dashboard"></div>
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Pedidos entregues</p>
                <p id="qtd-preco-metricas-dashboard"><?= (int)$indicadoresAtuais['entregues'] ?></p>
            </div>
            <div class="linha-vertical-metrica-dashboard"></div>
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Renda (R$)</p>
                <p id="qtd-preco-metricas-dashboard">R$ <?= number_format((float)$indicadoresAtuais['renda'], 2, ',', '.') ?></p>
            </div>
        </div>

        <h1>Pedidos Recentes</h1>
        <div class="secao-dashboard-box">
            <?php if (empty($pedidosRecentes)): ?>
                <p style="padding: 15px;">Nenhum pedido encontrado para esta loja.</p>
            <?php else: ?>
                <table class="tabela-nexora">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidosRecentes as $ped): ?>
                            <tr>
                                <td>#<?= (int)$ped['pedido_id'] ?></td>
                                <td><?= e(!empty($ped['cliente_nome']) ? $ped['cliente_nome'] : 'Cliente removido') ?></td>
                                <td><?= e($ped['produto_nome'] ?? 'Produto indisponível') ?></td>
                                <td>R$ <?= number_format((float)$ped['valor_item'], 2, ',', '.') ?></td>
                                <td><?= e($ped['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <h1 style="margin-top: 50px;">Gestão de Usuários Ativos</h1>
        <div class="secao-dashboard-box">
            <form method="GET" action="dashboard.php" class="barra-busca-usuarios">
                <input type="hidden" name="periodo" value="<?= e($periodo) ?>">
                <input type="text" name="busca" placeholder="Pesquisar por nome ou e-mail..." value="<?= e($busca) ?>">
                <button type="submit" class="btn-nexora-primario">Pesquisar</button>
                <?php if ($busca !== ''): ?>
                    <a href="dashboard.php?periodo=<?= urlencode($periodo) ?>" class="btn-nexora-secundario">Limpar</a>
                <?php endif; ?>
            </form>

            <?php if (empty($usuariosAtivos)): ?>
                <p style="padding: 15px;">Nenhum usuário ativo encontrado para a consulta informada.</p>
            <?php else: ?>
                <table class="tabela-nexora">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome Completo</th>
                            <th>E-mail</th>
                            <th>Setor (JOIN)</th>
                            <th>Perfil (JOIN)</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuariosAtivos as $u): ?>
                            <tr>
                                <td>#<?= (int)$u['id'] ?></td>
                                <td><?= e($u['nome'] . ' ' . $u['sobrenome']) ?></td>
                                <td><?= e($u['email']) ?></td>
                                <td><?= e($u['setor_nome']) ?></td>
                                <td><?= e($u['perfil_nome']) ?></td>
                                <td><span class="status-ativo"><?= e($u['status']) ?></span></td>
                                <td>
                                    <?php if ((int)$u['id'] !== (int)($_SESSION['usuario']['id'] ?? 0)): ?>
                                        <button type="button"
                                                class="btn-nexora-perigo"
                                                onclick="abrirModalExclusao(<?= (int)$u['id'] ?>, '<?= e(addslashes($u['nome'] . ' ' . $u['sobrenome'])) ?>', '<?= e(addslashes($u['email'])) ?>')">
                                            Excluir
                                        </button>
                                    <?php else: ?>
                                        <span><em>Sessão atual</em></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <h2 style="width: 80%; margin-top: 35px;">Auditoria de Operações Recentes</h2>
        <div class="secao-dashboard-box">
            <?php if (empty($logsAuditoria)): ?>
                <p style="padding: 15px;">Nenhum registro de auditoria ainda.</p>
            <?php else: ?>
                <table class="tabela-nexora">
                    <thead>
                        <tr>
                            <th>ID Log</th>
                            <th>ID Usuário Alvo</th>
                            <th>Ação</th>
                            <th>Autor</th>
                            <th>Data/Hora</th>
                            <th>Resultado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logsAuditoria as $log): ?>
                            <?php $alvoId = $log['id_usuario_alvo'] ?? null; ?>
                            <tr>
                                <td>#<?= (int)$log['id'] ?></td>
                                <td><?= $alvoId ? '#' . (int)$alvoId : '—' ?></td>
                                <td><?= e($log['acao']) ?></td>
                                <td><?= e($log['autor']) ?></td>
                                <td><?= date('d/m/Y H:i:s', strtotime($log['data_hora'])) ?></td>
                                <td><?= e($log['resultado']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <div id="modal-exclusao" class="modal-overlay" style="display: none;">
        <div class="modal-conteudo">
            <h2>Confirmar Exclusão</h2>
            <p>Deseja realmente excluir este usuário?</p>
            <p id="modal-info-usuario" style="background: antiquewhite; padding: 10px; border-radius: 6px; border: 1px solid #410200;"></p>
            <p style="font-size: 13px;">O registro será marcado com status <strong>Inativo</strong> e ficará registrado na auditoria.</p>

            <form method="POST" style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <input type="hidden" name="acao" value="excluir_usuario">
                <input type="hidden" name="usuario_id" id="modal-usuario-id" value="">

                <button type="button" class="btn-nexora-secundario" onclick="fecharModalExclusao()">Cancelar</button>
                <button type="submit" class="btn-nexora-perigo">Confirmar</button>
            </form>
        </div>
    </div>

    <?php include 'paginas/footer.php' ?>

    <script src="assets/js/functions.js"></script>
</body>
</html>