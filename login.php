<?php 
    require_once 'config/conexao.php';
    require_once 'assets/php/functions.php';

    iniciarSessaoSegura();

    if(usuarioEstaLogado()){
        redirecionar('inicio.php');
    }

    $erro = '';
    $sucesso = $_SESSION['sucesso_cadastro'] ?? '';
    unset($_SESSION['sucesso_cadastro']);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if($email === '' || $senha === ''){
            $erro = 'Por favor, preencha todos os campos.';
        }else{
            $stmt = $pdo->prepare('SELECT id, nome, sobrenome, email, senha FROM usuarios WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                session_regenerate_id(true);

                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome'],
                    'sobrenome' => $usuario['sobrenome'],
                    'email' => $usuario['email']
                ];

                redirecionar('inicio.php');
            } else {
                $erro = 'E-mail ou senha incorretos.';
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
                <p style="color: green; font-weight: bold;"><?= htmlspecialchars($sucesso) ?></p>
            <?php endif; ?>

            <?php if ($erro !== ''): ?>
                <p style="color: #D9381E; font-weight: bold;"><?= htmlspecialchars($erro) ?></p>
            <?php endif; ?>

            <div class="campo-input">
                <Label for="email">Email</Label>
                <input type="email" placeholder="nome@exemplo" maxlength="255" id="email" name="email">
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