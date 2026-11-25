<?php
    require_once 'assets/php/functions.php';
    require_once 'config/conexao.php';

    iniciarSessaoSegura();

    exigirLogin();

    $usuarioId = (int)$_SESSION['usuario']['id'];
    $csrfToken = gerarTokenCsrf();
    $mensagemSucesso = '';
    $mensagemErro = '';

    // Processa ações POST (Atualização de dados ou Inativo da conta)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $acao = $_POST['acao'] ?? '';
        $tokenRecebido = $_POST['csrf_token'] ?? '';

        if (!validarTokenCsrf($tokenRecebido)) {
            $mensagemErro = 'Falha de validação de segurança (CSRF). Atualize a página e tente novamente.';
        } elseif ($acao === 'atualizar_perfil') {
            $nome = trim($_POST['nome'] ?? '');
            $sobrenome = trim($_POST['sobrenome'] ?? '');
            $telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? '');
            $dataNascimento = trim($_POST['data_de_nascimento'] ?? '');

            if ($nome === '' || $sobrenome === '' || $telefone === '' || $dataNascimento === '') {
                $mensagemErro = 'Preencha todos os campos para atualizar seus dados.';
            } elseif (strlen($telefone) < 10 || strlen($telefone) > 11) {
                $mensagemErro = 'Informe um telefone válido com DDD.';
            } elseif (strtotime($dataNascimento) === false || strtotime($dataNascimento) >= time()) {
                $mensagemErro = 'Informe uma data de nascimento válida.';
            } else {
                $pdo->beginTransaction();
                try {
                    $stmtUpd = $pdo->prepare(
                        'UPDATE usuarios
                         SET nome = :nome, sobrenome = :sobrenome, telefone = :telefone,
                             data_de_nascimento = :data_nasc, atualizado_em = NOW()
                         WHERE id = :id AND status = "Ativo"'
                    );
                    $stmtUpd->execute([
                        ':nome'      => $nome,
                        ':sobrenome' => $sobrenome,
                        ':telefone'  => $telefone,
                        ':data_nasc' => $dataNascimento,
                        ':id'        => $usuarioId
                    ]);

                    $stmtAudit = $pdo->prepare('INSERT INTO auditoria_usuarios (id_usuario_alvo, acao, autor, resultado) VALUES (:alvo, :acao, :autor, :resultado)');
                    $stmtAudit->execute([
                        ':alvo'      => $usuarioId,
                        ':acao'      => 'ATUALIZACAO_PERFIL',
                        ':autor'     => $_SESSION['usuario']['email'],
                        ':resultado' => 'Dados cadastrais atualizados pelo próprio usuário'
                    ]);

                    $pdo->commit();

                    $_SESSION['usuario']['nome'] = $nome;
                    $_SESSION['usuario']['sobrenome'] = $sobrenome;
                    $mensagemSucesso = 'Seus dados foram atualizados com sucesso!';
                } catch (PDOException $e) {
                    $pdo->rollBack();
                    error_log('Erro ao atualizar perfil: ' . $e->getMessage());
                    $mensagemErro = 'Não foi possível atualizar seus dados no momento.';
                }
            }
        } elseif ($acao === 'desativar_conta') {
            $pdo->beginTransaction();
            try {
                $stmtInativo = $pdo->prepare(
                    'UPDATE usuarios
                     SET status = "Inativo", atualizado_em = NOW(), desativado_em = NOW()
                     WHERE id = :id AND status = "Ativo"'
                );
                $stmtInativo->execute([':id' => $usuarioId]);

                $stmtAudit = $pdo->prepare('INSERT INTO auditoria_usuarios (id_usuario_alvo, acao, autor, resultado) VALUES (:alvo, :acao, :autor, :resultado)');
                $stmtAudit->execute([
                    ':alvo' => $usuarioId,
                    ':acao' => 'DESATIVACAO_CONTA',
                    ':autor' => $_SESSION['usuario']['email'],
                    ':resultado' => 'Conta marcada como Inativa pelo titular'
                ]);

                $pdo->commit();
                redirecionar('logout.php');
            } catch (PDOException $e) {
                $pdo->rollBack();
                error_log('Erro ao desativar conta: ' . $e->getMessage());
                $mensagemErro = 'Não foi possível desativar sua conta no momento.';
            }
        }
    }

    // Busca dados completos do usuário com JOIN em setores, perfis e lojas
    $sqlUsuario = 'SELECT u.*, s.nome AS setor_nome, p.nome AS perfil_nome, l.nome AS loja_nome
                   FROM usuarios u
                   INNER JOIN setores s ON u.id_setor = s.id
                   INNER JOIN perfis p ON u.id_perfil = p.id
                   LEFT JOIN lojas l ON l.id_usuario = u.id
                   WHERE u.id = :id
                   LIMIT 1';
    $stmtUser = $pdo->prepare($sqlUsuario);
    $stmtUser->execute([':id' => $usuarioId]);
    $dadosUsuario = $stmtUser->fetch();

    if (!$dadosUsuario || $dadosUsuario['status'] !== 'Ativo') {
        redirecionar('logout.php');
    }

    // Busca histórico de pedidos do usuário
    $stmtPedidos = $pdo->prepare(
        'SELECT id, status_pedido, valor_total, data_tempo_pedido
         FROM pedidos
         WHERE id_usuario = :id_usuario
         ORDER BY data_tempo_pedido DESC
         LIMIT 10'
    );
    $stmtPedidos->execute([':id_usuario' => $usuarioId]);
    $meusPedidos = $stmtPedidos->fetchAll();

    $fotoPerfil = !empty($dadosUsuario['foto_perfil']) ? $dadosUsuario['foto_perfil'] : 'img/img-generica.jpg';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Nexora</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include 'paginas/header.php' ?>

    <main>
        <div class="container-perfil">
            <?php if ($mensagemSucesso !== ''): ?>
                <div class="alerta-sucesso"><?= e($mensagemSucesso) ?></div>
            <?php endif; ?>

            <?php if ($mensagemErro !== ''): ?>
                <div class="alerta-erro"><?= e($mensagemErro) ?></div>
            <?php endif; ?>

            <!-- Cabeçalho do Perfil -->
            <div class="card-perfil-cabecalho">
                <img src="<?= e($fotoPerfil) ?>" alt="Foto de perfil">
                <div class="info-cabecalho-perfil">
                    <h1><?= e($dadosUsuario['nome'] . ' ' . $dadosUsuario['sobrenome']) ?></h1>
                    <p><?= e($dadosUsuario['email']) ?></p>
                    <div class="badges-perfil">
                        <span class="badge-nexora">Setor: <?= e($dadosUsuario['setor_nome']) ?></span>
                        <span class="badge-nexora">Perfil: <?= e($dadosUsuario['perfil_nome']) ?></span>
                        <?php if (!empty($dadosUsuario['loja_nome'])): ?>
                            <span class="badge-nexora">Loja: <?= e($dadosUsuario['loja_nome']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Formulário de Edição de Dados -->
            <div class="card-secao-perfil">
                <h2>Seus Dados Cadastrais</h2>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                    <input type="hidden" name="acao" value="atualizar_perfil">

                    <div class="grade-campos-inputs-duplos">
                        <div>
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" maxlength="50" value="<?= e($dadosUsuario['nome']) ?>" required>
                        </div>
                        <div>
                            <label for="sobrenome">Sobrenome</label>
                            <input type="text" id="sobrenome" name="sobrenome" maxlength="50" value="<?= e($dadosUsuario['sobrenome']) ?>" required>
                        </div>
                    </div>

                    <div class="grade-campos-inputs-duplos">
                        <div>
                            <label for="telefone">Telefone</label>
                            <input type="tel" id="telefone" name="telefone" maxlength="15" value="<?= e($dadosUsuario['telefone']) ?>" required>
                        </div>
                        <div>
                            <label for="data_de_nascimento">Data de Nascimento</label>
                            <input type="date" id="data_de_nascimento" name="data_de_nascimento" value="<?= e($dadosUsuario['data_de_nascimento']) ?>" required>
                        </div>
                    </div>

                    <div class="grade-campos-inputs-duplos">
                        <div>
                            <label>CPF</label>
                            <input type="text" value="<?= e($dadosUsuario['cpf']) ?>" disabled>
                        </div>
                        <div>
                            <label>Membro desde</label>
                            <input type="text" value="<?= date('d/m/Y H:i', strtotime($dadosUsuario['data_cadastro'])) ?>" disabled>
                        </div>
                    </div>

                    <button type="submit" class="btn-nexora-primario">Salvar Alterações</button>
                </form>
            </div>

            <!-- Histórico de Pedidos -->
            <div class="card-secao-perfil">
                <h2>Meus Pedidos Recentes</h2>
                <?php if (empty($meusPedidos)): ?>
                    <p>Você ainda não realizou nenhum pedido.</p>
                <?php else: ?>
                    <table class="tabela-nexora">
                        <thead>
                            <tr>
                                <th>Pedido</th>
                                <th>Data</th>
                                <th>Valor Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($meusPedidos as $pedido): ?>
                                <tr>
                                    <td>#<?= (int)$pedido['id'] ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($pedido['data_tempo_pedido'])) ?></td>
                                    <td>R$ <?= number_format((float)$pedido['valor_total'], 2, ',', '.') ?></td>
                                    <td><?= e($pedido['status_pedido']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Zona de Desativação com Confirmação (Ponto 09 / 10) -->
            <div class="card-secao-perfil zona-perigo">
                <h2>Encerrar Conta</h2>
                <p>Ao desativar sua conta, seu status será alterado para <strong>Inativo</strong> e o acesso ao sistema será encerrado.</p>
                <button type="button" class="btn-nexora-perigo" onclick="document.getElementById('confirmacao-conta').style.display='block'">
                    Excluir / Desativar Minha Conta
                </button>

                <div id="confirmacao-conta" class="caixa-confirmacao" style="display: none;">
                    <p><strong>Deseja realmente excluir este usuário?</strong></p>
                    <p>Usuário: <strong><?= e($dadosUsuario['nome'] . ' ' . $dadosUsuario['sobrenome']) ?></strong> (<?= e($dadosUsuario['email']) ?>)</p>
                    <form method="POST" style="display: flex; gap: 10px; margin-top: 10px;">
                        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                        <input type="hidden" name="acao" value="desativar_conta">
                        <button type="button" class="btn-nexora-secundario" onclick="document.getElementById('confirmacao-conta').style.display='none'">Cancelar</button>
                        <button type="submit" class="btn-nexora-perigo">Confirmar</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include 'paginas/footer.php' ?>
</body>
</html>