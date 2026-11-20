<?php 
    require_once 'config/conexao.php';
    require_once 'assets/php/functions.php';

    iniciarSessaoSegura();

    if(usuarioEstaLogado()){
        redirecionar('inicio.php');
    }

    $erro = '';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $nome = trim($_POST['nome'] ?? '');
        $sobrenome = trim($_POST['sobrenome'] ?? '');
        $telefone = preg_replace('/\D/', '',$_POST['telefone'] ?? '');
        $dataNascimento = trim($_POST['data-nascimento'] ?? '');
        $cpf = preg_replace('/\D/', '',$_POST['cpf'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = ($_POST['senha'] ?? '');
        $confSenha = ($_POST['conf-senha'] ?? '');

        if(!$nome || !$sobrenome || !$telefone || !$dataNascimento || !$cpf || !$email || !$senha || !$confSenha){
            $erro = 'Por favor, preencha todos os campos.';
        }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $erro = 'Informe um email válido';
        }elseif(strlen($senha) < 8 || strlen($confSenha) < 8){
            $erro = 'A senha deve ter pelo menos 8 caracteres.';
        }elseif($senha !== $confSenha){
            $erro = 'As senhas são diferentes.';
        }elseif(strlen($cpf) !== 11){
            $erro = 'Insira um cpf válido';
        }elseif(strlen($telefone) < 10 || strlen($telefone) > 11){
            $erro = 'Insira um número de telefone válido.';
        }elseif(strtotime($dataNascimento) >= time()){
            $erro = 'Insira uma data de nascimento válida.';
        }else{
            $stmtBusca = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email OR cpf = :cpf LIMIT 1');
            $stmtBusca->execute([':email' => $email, ':cpf' => $cpf]);

            if ($stmtBusca->fetch()) {
                $erro = 'Este e-mail ou CPF já está cadastrado.';
            } else {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $sql = 'INSERT INTO usuarios (nome, sobrenome, email, senha, cpf, telefone, data_de_nascimento)
                        VALUES (:nome, :sobrenome, :email, :senha, :cpf, :telefone, :data_de_nascimento)';

                $stmtInsert = $pdo->prepare($sql);
                $stmtInsert->execute([
                    ':nome' => $nome,
                    ':sobrenome' => $sobrenome,
                    ':email' => $email,
                    ':senha' => $senhaHash,
                    ':cpf' => $cpf,
                    ':telefone' => $telefone,
                    ':data_de_nascimento' => $dataNascimento
                ]);

                $_SESSION['sucesso_cadastro'] = 'Cadastro realizado com sucesso!';
                redirecionar('login.php');
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
                <p style="color: #D9381E; font-weight: bold;"><?= htmlspecialchars($erro) ?></p>
            <?php endif; ?>

            <div class="grade-campos-inputs-duplos">
                <div>
                    <label for="nome">Nome</label>
                    <input type="text" placeholder="Fulano" maxlength="50" id="nome" name="nome">
                </div>
                <div>
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" placeholder="da Silva" maxlength="50" id="sobrenome" name="sobrenome">
                </div>
            </div>
            <div class="grade-campos-inputs-duplos">
                <div>
                    <label for="telefone">Telefone</label>
                    <input type="tel" placeholder="(00) 91234-5678" maxlength="15" id="telefone" name="telefone">
                </div>
                <div>
                    <label for="data-nascimento">Data de Nascimento</label>
                    <input type="date" id="data-nascimento" name="data-nascimento">
                </div>
            </div>
            <div class="campo-input">
                <label for="cpf">CPF</label>
                <input type="text" placeholder="123.456.789-00" maxlength="14" id="cpf" name="cpf">
            </div>
            <div class="campo-input">
                <label for="email">Email</label>
            <input type="email" placeholder="nome@exemplo" maxlength="255" id="email" name="email">
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