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
        <form class="formulario-cadastro" id="formulario-cadastro">
            <h1>Cadastro</h1>
            <div class="grade-campos-inputs-duplos">
                <div>
                    <label for="nome">Nome</label>
                    <input type="text" placeholder="Fulano" maxlength="50" id="nome">
                </div>
                <div>
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" placeholder="da Silva" maxlength="50" id="sobrenome">
                </div>
            </div>
            <div class="grade-campos-inputs-duplos">
                <div>
                    <label for="telefone">Telefone</label>
                    <input type="tel" placeholder="(00) 91234-5678" maxlength="11" id="telefone">
                </div>
                <div>
                    <label for="data-nascimento">Data de Nascimento</label>
                    <input type="date" id="data-nascimento">
                </div>
            </div>
            <div class="campo-input">
                <label for="cpf">CPF</label>
                <input type="text" placeholder="123.456.789-00" maxlength="11" id="cpf">
            </div>
            <div class="campo-input">
                <label for="email">Email</label>
            <input type="email" placeholder="nome@exemplo" maxlength="255" id="email">
            </div>
            <label for="senha">Senha</label>
            <div class="campo-senha">
                <input type="password" placeholder="********" maxlength="255" id="senha">
                <button type="button" id="mostrar-senha">
                    <img src="img/olho_fechado.svg" alt="icone-olho" id="icone-olho">
                </button>
            </div>
            <div class="campo-input">
                <label for="conf-senha">Confirme sua Senha</label>
            <input type="password" placeholder="********" maxlength="255" id="conf-senha">
            </div>
            
            <input type="submit" value="Cadastre-se" id="Cadastre-se">
            <p>Já tem uma conta? <a href="Login.php">Entre</a></p>
        </form>
    </main>

    <?php include 'paginas/footer.php' ?>
    
    <script src="assets/js/formularios.js"></script>
</body>
</html>