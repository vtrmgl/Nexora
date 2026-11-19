<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contatos</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include 'paginas/header.php' ?>

    <main>
        <div class="card-contatos">
            <div class="secao-contato">
                <div class="textos-secao-contato">
                    <h2>Entre em contato conosco!</h2>
                    <h3>Endereço</h3>
                    <p>Rua Core I5, 1235u, Recife-PE</p>
                    <h3>Telefone</h3>
                    <p>4002-8922</p>
                    <h3>Email</h3>
                    <p>vitorazevedovmpa02@gmail.com</p>
                </div>
            </div>
            <form class="form-contato" id="form-contato">
                <div class="inputs-contato">
                    <div class="campo-input">
                        <label for="nome">Nome</label>
                        <input type="text" placeholder="Fulano" maxlength="100" id="nome">
                    </div>
                    <div class="campo-input">
                        <label for="email">Email</label>
                        <input type="email" placeholder="nome@exemplo" maxlength="255" id="email">
                    </div>
                    <div class="campo-input">
                        <label for="assunto">Assunto</label>
                        <input type="text" placeholder="Ex: Atraso na entrega" maxlength="150" id="assunto">
                    </div>
                    <div class="campo-input-grande">
                        <label for="mensagem">Mensagem</label>
                        <textarea type="text" placeholder="Explique a situação detalhadamente" id="mensagem"></textarea>
                    </div>
                    <input type="submit" value="Enviar mensagem">
                </div>
            </form>
        </div>
    </main>

    <?php include 'paginas/footer.php' ?>

    <script src="assets/js/formularios.js"></script>
</body>
</html>