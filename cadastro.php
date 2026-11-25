<?php 
    require_once 'config/conexao.php';
    require_once 'assets/php/functions.php';

    iniciarSessaoSegura();

    if (usuarioEstaLogado()) {
        redirecionar('inicio.php');
    }

    // Ponto 04: Carrega setores e perfis ativos diretamente do banco
    $setores = $pdo->query('SELECT id, nome FROM setores WHERE ativo = "Ativo" ORDER BY nome')->fetchAll();
    $perfis  = $pdo->query('SELECT id, nome FROM perfis WHERE ativo = "Ativo" ORDER BY nome')->fetchAll();

    $erro = '';
    $nome = '';
    $sobrenome = '';
    $telefone = '';
    $dataNascimento = '';
    $cpf = '';
    $email = '';
    $setorId = 0;
    $perfilId = 0;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = trim($_POST['nome'] ?? '');
        $sobrenome = trim($_POST['sobrenome'] ?? '');
        $telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? '');
        $dataNascimento = trim($_POST['data-nascimento'] ?? '');
        $cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $setorId = (int)($_POST['id_setor'] ?? 0);
        $perfilId = (int)($_POST['id_perfil'] ?? 0);
        $senha = $_POST['senha'] ?? '';
        $confSenha = $_POST['conf-senha'] ?? '';

        if (!$nome || !$sobrenome || !$telefone || !$dataNascimento || !$cpf || !$email || !$senha || !$confSenha || $setorId <= 0 || $perfilId <= 0) {
            $erro = 'Por favor, preencha todos os campos obrigatórios, incluindo Setor e Perfil.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = 'Informe um e-mail válido.';
        } elseif (strlen($senha) < 8 || strlen($confSenha) < 8) {
            $erro = 'A senha deve ter pelo menos 8 caracteres.';
        } elseif ($senha !== $confSenha) {
            $erro = 'As senhas são diferentes.';
        } elseif (strlen($cpf) !== 11) {
            $erro = 'Insira um CPF válido com 11 dígitos.';
        } elseif (strlen($telefone) < 10 || strlen($telefone) > 11) {
            $erro = 'Insira um número de telefone válido.';
        } elseif (strtotime($dataNascimento) === false || strtotime($dataNascimento) >= time()) {
            $erro = 'Insira uma data de nascimento válida.';
        } else {
            $stmtSetor = $pdo->prepare('SELECT id FROM setores WHERE id = :id AND ativo = "Ativo" LIMIT 1');
            $stmtSetor->execute([':id' => $setorId]);

            $stmtPerfil = $pdo->prepare('SELECT id FROM perfis WHERE id = :id AND ativo = "Ativo" LIMIT 1');
            $stmtPerfil->execute([':id' => $perfilId]);

            if (!$stmtSetor->fetch() || !$stmtPerfil->fetch()) {
                $erro = 'Setor ou perfil selecionado é inválido ou está inativo.';
            } else {
                $stmtBusca = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email OR cpf = :cpf LIMIT 1');
                $stmtBusca->execute([':email' => $email, ':cpf' => $cpf]);

                if ($stmtBusca->fetch()) {
                    $erro = 'Este e-mail ou CPF já está cadastrado.';
                } else {
                    try {
                        $pdo->beginTransaction();

                        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                        $tipoUsuario = ($perfilId === 4) ? 'Administrador' : 'Usuário';

                        $sql = 'INSERT INTO usuarios (nome, sobrenome, email, senha, tipo, id_setor, id_perfil, status, cpf, telefone, data_de_nascimento)
                                VALUES (:nome, :sobrenome, :email, :senha, :tipo, :id_setor, :id_perfil, :status, :cpf, :telefone, :data_de_nascimento)';

                        $stmtInsert = $pdo->prepare($sql);
                        $stmtInsert->execute([
                            ':nome' => $nome,
                            ':sobrenome' => $sobrenome,
                            ':email' => $email,
                            ':senha' => $senhaHash,
                            ':tipo' => $tipoUsuario,
                            ':id_setor' => $setorId,
                            ':id_perfil' => $perfilId,
                            ':status' => 'Ativo',
                            ':cpf' => $cpf,
                            ':telefone' => $telefone,
                            ':data_de_nascimento' => $dataNascimento
                        ]);

                        $novoId = (int)$pdo->lastInsertId();

                        $stmtAudit = $pdo->prepare('INSERT INTO auditoria_usuarios (id_usuario_alvo, acao, autor, resultado) VALUES (:alvo, :acao, :autor, :resultado)');
                        $stmtAudit->execute([
                            ':alvo' => $novoId,
                            ':acao' => 'CADASTRO',
                            ':autor' => $email,
                            ':resultado' => 'Usuário cadastrado com sucesso'
                        ]);

                        $pdo->commit();

                        $_SESSION['sucesso_cadastro'] = 'Cadastro realizado com sucesso! Faça seu login.';
                        redirecionar('login.php');
                    } catch (PDOException $e) {
                        $pdo->rollBack();
                        error_log('Erro ao cadastrar usuário: ' . $e->getMessage());
                        $erro = 'Não foi possível concluir o cadastro. Tente novamente.';
                    }
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    
    <?php include 'paginas/header.php' ?>

    <main>
        <form class="formulario-cadastro" id="formulario-cadastro" method="POST">
            <h1>Cadastro</h1>

            <?php if ($erro !== ''): ?>
                <p style="color: #D9381E; font-weight: bold;"><?= e($erro) ?></p>
            <?php endif; ?>

            <div class="grade-campos-inputs-duplos">
                <div>
                    <label for="nome">Nome</label>
                    <input type="text" placeholder="Fulano" maxlength="50" id="nome" name="nome" value="<?= e($nome) ?>">
                </div>
                <div>
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" placeholder="da Silva" maxlength="50" id="sobrenome" name="sobrenome" value="<?= e($sobrenome) ?>">
                </div>
            </div>
            <div class="grade-campos-inputs-duplos">
                <div>
                    <label for="telefone">Telefone</label>
                    <input type="tel" placeholder="(00) 91234-5678" maxlength="15" id="telefone" name="telefone" value="<?= e($telefone) ?>">
                </div>
                <div>
                    <label for="data-nascimento">Data de Nascimento</label>
                    <input type="date" id="data-nascimento" name="data-nascimento" value="<?= e($dataNascimento) ?>">
                </div>
            </div>
            <div class="grade-campos-inputs-duplos">
                <div>
                    <label for="id_setor">Setor / Área</label>
                    <select id="id_setor" name="id_setor" class="select-nexora">
                        <option value="">Selecione um setor...</option>
                        <?php if (empty($setores)): ?>
                            <option value="" disabled>Nenhum setor ativo cadastrado</option>
                        <?php else: ?>
                            <?php foreach ($setores as $s): ?>
                                <option value="<?= (int)$s['id'] ?>" <?= $setorId === (int)$s['id'] ? 'selected' : '' ?>>
                                    <?= e($s['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div>
                    <label for="id_perfil">Perfil de Acesso</label>
                    <select id="id_perfil" name="id_perfil" class="select-nexora">
                        <option value="">Selecione um perfil...</option>
                        <?php if (empty($perfis)): ?>
                            <option value="" disabled>Nenhum perfil ativo cadastrado</option>
                        <?php else: ?>
                            <?php foreach ($perfis as $p): ?>
                                <option value="<?= (int)$p['id'] ?>" <?= $perfilId === (int)$p['id'] ? 'selected' : '' ?>>
                                    <?= e($p['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="campo-input">
                <label for="cpf">CPF</label>
                <input type="text" placeholder="123.456.789-00" maxlength="14" id="cpf" name="cpf" value="<?= e($cpf) ?>">
            </div>
            <div class="campo-input">
                <label for="email">Email</label>
                <input type="email" placeholder="nome@exemplo.com" maxlength="255" id="email" name="email" value="<?= e($email) ?>">
            </div>
            <label for="senha">Senha</label>
            <div class="campo-senha">
                <input type="password" placeholder="********" maxlength="255" id="senha" name="senha">
                <button type="button" id="mostrar-senha">
                    <img src="img/olho_fechado.svg" alt="icone-olho" id="icone-olho">
                </button>
            </div>
            <div class="campo-input">
                <label for="conf-senha">Confirme sua Senha</label>
                <input type="password" placeholder="********" maxlength="255" id="conf-senha" name="conf-senha">
            </div>
            
            <input type="submit" value="Cadastre-se" id="Cadastre-se">
            <p>Já tem uma conta? <a href="login.php">Entre</a></p>
        </form>
    </main>

    <?php include 'paginas/footer.php' ?>
    
    <script src="assets/js/formularios.js"></script>
</body>
</html>