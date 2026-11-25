/* Validação do formulário de login */

const formLogin = document.getElementById("formulario-login");

if(formLogin){
    formLogin.addEventListener('submit', function(event){

        const email = document.getElementById('email').value.trim();
        const senha = document.getElementById('senha').value;

        if(email === '' || senha === ''){
            event.preventDefault();
            alert('Por favor, preencha todos os campos');
            return;
        }else if(senha.length < 8){
            event.preventDefault();
            alert('A senha deve ter pelo menos 8 caracteres');
            return;
        };
    });
};

/* Validação do formulário de cadastro */

const formCadastro = document.getElementById("formulario-cadastro");

if(formCadastro){
    formCadastro.addEventListener('submit', function(event){

        const nome = document.getElementById('nome').value.trim();
        const sobrenome = document.getElementById('sobrenome').value.trim();
        let telefone = document.getElementById('telefone').value;
        const dataDeNascimento = document.getElementById('data-nascimento').value;
        const setorId = document.getElementById('id_setor').value.trim();
        const perfilId = document.getElementById('id_perfil').value.trim();
        let cpf = document.getElementById('cpf').value;
        const email = document.getElementById('email').value.trim();
        const senha = document.getElementById('senha').value;
        const conSenha = document.getElementById('conf-senha').value;

        telefone = telefone.replace(/\D/g, '');
        cpf = cpf.replace(/\D/g, '')
        const dataDeNascimentoComparacao = new Date(dataDeNascimento);
        const hoje = new Date();

        if(!nome || !sobrenome || !cpf || !email || !senha || !conSenha || !dataDeNascimento || !telefone || !setorId || !perfilId){
            event.preventDefault();
            alert('Por favor, preencha todos os campos.');
            return;
        }else if(senha.length < 8 || conSenha.length < 8){
            event.preventDefault();
            alert('A senha deve ter pelo menos 8 caracteres.');
            return;
        }else if(senha !== conSenha){
            event.preventDefault();
            alert('As senhas são diferentes.');
            return;
        }else if(dataDeNascimentoComparacao >= hoje){
            event.preventDefault();
            alert('Insira uma data de nascimento válida.')
            return;
        }else if(telefone.length < 10 || telefone.length > 11){
            event.preventDefault();
            alert('Insira um número de telefone válido.')
            return;
        } else if(cpf.length !== 11){
            event.preventDefault();
            alert('Insira um cpf válido.');
            return;
        };
    });
};

/* Validação do formulário de Contato */

const formContato = document.getElementById("form-contato");

if(formContato){
    formContato.addEventListener('submit', function(event){

        const nome = document.getElementById('nome').value.trim();
        const email = document.getElementById('email').value.trim();
        const assunto = document.getElementById('assunto').value.trim();
        const mensagem = document.getElementById('mensagem').value.trim();

        if(!nome || !email || !assunto || !mensagem){
            event.preventDefault();
            alert('Por favor, preencha todos os campos');
            return;
        }
    });
};

/* Botão mostrar senha */

const inputSenha = document.getElementById('senha');
const mostrarSenha = document.getElementById('mostrar-senha');
const iconeOlho = document.getElementById('icone-olho');

if (mostrarSenha && inputSenha && iconeOlho){
    mostrarSenha.addEventListener('click', () => {
        if(inputSenha.type === 'password'){
            inputSenha.type = 'text';
            iconeOlho.src = 'img/olho_aberto.svg'
        }else{
            inputSenha.type = 'password';
            iconeOlho.src = 'img/olho_fechado.svg'
        }
    });
}
