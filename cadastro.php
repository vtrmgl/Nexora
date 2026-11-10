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
            <div class="nome-sobrenome-cadastro">
                <div>
                    <label for="nome">Nome</label>
                    <input type="text" placeholder="Fulano" id="nome">
                </div>
                <div>
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" placeholder="da Silva" id="sobrenome">
                </div>
            </div>
            <div class="telefone-datanascimento-cadastro">
                <div>
                    <label for="telefone">Telefone</label>
                    <input type="tel" placeholder="(00) 91234-5678" id="telefone">
                </div>
                <div>
                    <label for="data-nascimento">Data de Nascimento</label>
                    <input type="date" id="data-nascimento">
                </div>
            </div>
            <label for="cpf">CPF</label>
            <input type="text" placeholder="123.456.789-00" id="cpf">
            <label for="email">Email</label>
            <input type="email" placeholder="nome@exemplo" id="email">
            <label for="senha">Senha</label>
            <div class="container-senha">
                <input type="password" placeholder="********" id="senha">
                <button type="button" id="mostrar-senha">
                    <img src="img/olho_fechado.svg" alt="icone-olho" id="icone-olho">
                </button>
            </div>
            <label for="conf-senha">Confirme sua Senha</label>
            <input type="password" placeholder="********" id="conf-senha">
            <input type="submit" value="Cadastre-se" id="Cadastre-se">
            <p>Já tem uma conta? <a href="Login.php">Entre</a></p>
        </form>
    </main>

    <?php include 'paginas/footer.php' ?>
    
    <script src="assets/js/formularios.js"></script>
</body>
</html>