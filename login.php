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
        <form class="formulario-login" id="formulario-login">
            <h1>Login</h1>
            <div class="campo-input">
                <Label for="email">Email</Label>
                <input type="email" placeholder="nome@exemplo" maxlength="255" id="email">
            </div>
            <div class="campo-input">
                <label for="senha">Senha</label>
                <input type="password" placeholder="********" maxlength="255" id="senha">
            </div>
            <input type="submit" value="Login" id="login">
            <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
        </form>
    </main>
    
    <?php include 'paginas/footer.php' ?>

    <script src="assets/js/formularios.js"></script>
</body>
</html>