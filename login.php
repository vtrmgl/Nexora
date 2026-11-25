<?php 
    require_once 'config/conexao.php';
    require_once 'assets/php/functions.php';

    iniciarSessaoSegura();

    if (usuarioEstaLogado()) {
        redirecionar('inicio.php');
    }

    $erro = '';
    $sucesso = $_SESSION['sucesso_cadastro'] ?? '';
    unset($_SESSION['sucesso_cadastro']);
    $email = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if ($email === '' || $senha === '') {
            $erro = 'Por favor, preencha todos os campos.';
        } else {
            $sql = 'SELECT u.id, u.nome, u.sobrenome, u.email, u.senha, u.tipo, u.status,
                           l.id AS id_loja, l.nome AS nome_loja
                    FROM usuarios u
                    LEFT JOIN lojas l ON l.id_usuario = u.id AND l.ativa = "Ativa"
                    WHERE u.email = :email
                    LIMIT 1';

            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch();

            if ($usuario && $usuario['status'] === 'Ativo' && password_verify($senha, $usuario['senha'])) {
                session_regenerate_id(true);

                $_SESSION['usuario'] = [
                    'id' => (int)$usuario['id'],
                    'nome' => $usuario['nome'],
                    'sobrenome' => $usuario['sobrenome'],
                    'email' => $usuario['email'],
                    'tipo' => $usuario['tipo'],
                    'id_loja' => $usuario['id_loja'] ? (int)$usuario['id_loja'] : null,
                    'nome_loja' => $usuario['nome_loja'] ?? null
                ];

                redirecionar('inicio.php');
            } else {
                $erro = 'E-mail e/ou senha incorretos, ou conta inativa.';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    
    <?php include 'paginas/header.php' ?>

    <main>
        <form class="formulario-login" id="formulario-login" method="POST">
            <h1>Login</h1>

            <?php if ($sucesso !== ''): ?>
                <p style="color: green; font-weight: bold;"><?= e($sucesso) ?></p>
            <?php endif; ?>

            <?php if ($erro !== ''): ?>
                <p style="color: #D9381E; font-weight: bold;"><?= e($erro) ?></p>
            <?php endif; ?>

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
            <input type="submit" value="Login" id="login">
            <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
        </form>
    </main>
    
    <?php include 'paginas/footer.php' ?>

    <script src="assets/js/formularios.js"></script>
</body>
</html>