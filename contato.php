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
                    <label for="nome">Nome</label>
                    <input type="text" placeholder="Fulano" id="nome">
                    <label for="email">Email</label>
                    <input type="email" placeholder="nome@exemplo" id="email">
                    <label for="assunto">Assunto</label>
                    <input type="text" placeholder="Ex: Atraso na entrega" id="assunto">
                    <label for="mensagem">Mensagem</label>
                    <input type="text" placeholder="Explique a situação detalhadamente" id="mensagem">
                    <input type="submit" value="Enviar mensagem">
                </div>
            </form>
        </div>
    </main>

    <?php include 'paginas/footer.php' ?>

    <script src="assets/js/formularios.js"></script>
</body>
</html>