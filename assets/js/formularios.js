/* Validação do formulário de login */

const formLogin = document.getElementById("#formulario-login");

if(formLogin){
    formLogin.addEventListener('submit', function(event){
        event.preventDefault();

        const email = document.getElementById('#email').value.trim();
        const senha = document.getElementById('#senha').value;

        if(email === '' || senha === ''){
            alert('Por favor, preencha todos os campos');
            return;
        }else if(senha.length < 8){
            alert('A senha deve ter pelo menos 8 caracteres');
            return;
        };

        alert('Login concluído com sucesso');

        window.location.href = 'inicio.html';
    });
};

/* Validação do formulário de cadastro */

const formCadastro = document.getElementById("#formulario-cadastro");

if(formCadastro){
    formCadastro.addEventListener('submit', function(event){
        event.preventDefault();

        const nome = document.getElementById('#nome').value.trim();
        const sobrenome = document.getElementById('#sobrenome').value.trim();
        let telefone = document.getElementById('#telefone').value;
        const dataDeNascimento = document.getElementById('#data-nascimento').value;
        let cpf = document.getElementById('#cpf').value;
        const email = document.getElementById('#email').value.trim();
        const senha = document.getElementById('#senha').value;
        const conSenha = document.getElementById('#conf-senha').value;

        telefone = telefone.replace(/\D/g, '');
        cpf = cpf.replace(/\D/g, '')
        const dataDeNascimentoComparacao = new Date(dataDeNascimento);
        const hoje = new Date();

        if(!nome || !sobrenome || !cpf || !email || !senha || !conSenha || !dataDeNascimento || !telefone){
            alert('Por favor, preencha todos os campos.');
            return;
        }else if(senha.length < 8 || conSenha.length < 8){
            alert('A senha deve ter pelo menos 8 caracteres.');
            return;
        }else if(senha !== conSenha){
            alert('As senhas são diferentes.');
            return;
        }else if(dataDeNascimentoComparacao >= hoje){
            alert('Insira uma data de nascimento válida.')
            return;
        }else if(telefone.length < 10 || telefone.length > 11){
            alert('Insira um número de telefone válido.')
            return;
        } else if(cpf.length !== 11){
            alert('Insira um cpf válido.');
            return;
        };

        alert('Cadastro feito com sucesso');

        window.location.href = 'login.html';
    });
};

/* Validação do formulário de Contato */

const formContato = document.getElementById("#form-contato");

if(formContato){
    formContato.addEventListener('submit', function(event){
        event.preventDefault();

        const nome = document.getElementById('#nome').value.trim();
        const email = document.getElementById('#email').value.trim();
        const assunto = document.getElementById('#assunto').value.trim();
        const mensagem = document.getElementById('#mensagem').value.trim();

        if(!nome || !email || !assunto || !mensagem){
            alert('Por favor, preencha todos os campos');
            return;
        }

        alert('Mensagem enviada com sucesso.');

        window.location.href = 'inicio.html';
    });
};

/* Botão mostrar senha */

const inputSenha = document.getElementById('senha');
const mostrarSenha = document.getElementById('mostrar-senha');
const iconeOlho = document.getElementById('icone-olho');

mostrarSenha.addEventListener('click', () => {
    if(inputSenha.type === 'password'){
        inputSenha.type = 'text';
        iconeOlho.src = 'img/olho_aberto.svg'
    }else{
        inputSenha.type = 'password';
        iconeOlho.src = 'img/olho_fechado.svg'
    }
});