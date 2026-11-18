# Instalação do Nexora

Guia para colocar o sistema para rodar em outro computador, do zero.

## Requisitos

- XAMPP 8.2 ou superior (Apache + PHP 8.2 + MySQL/MariaDB)
- Navegador atualizado (Chrome, Firefox ou Edge)

## Passo a passo

1. Abra o XAMPP Control Panel e clique em **Start** no **Apache** e no **MySQL**.
2. Copie a pasta `Nexora` para `C:\xampp\htdocs\`.
3. Acesse http://localhost/phpmyadmin
4. Clique em **Novo** e crie o banco `nexora`, com o agrupamento `utf8mb4_general_ci`. O nome precisa ser exatamente esse, em minúsculas.
5. Com o banco `nexora` selecionado no menu da esquerda, vá em **Importar**, escolha o arquivo `nexora.sql` (localizado na raiz do projeto) e clique em **Importar** (ou **Executar**).
6. Abra o arquivo `assets/php/conexao.php` e confira os dados de acesso ao banco (tabela abaixo).
7. Acesse http://localhost/Nexora

## Configuração (`assets/php/conexao.php`)

| Variável | Valor padrão no XAMPP |
|----------|-----------------------|
| `$host` | `localhost` |
| `$dbname` | `nexora` |
| `$username` | `root` |
| `$password` | (vazio) |

## Criando o administrador / lojista

O painel (`dashboard.php`) só abre para usuários autenticados que possuam uma loja vinculada no sistema. Para criar o seu acesso de teste:

1. Em http://localhost/Nexora/cadastro.php, crie uma conta nova preenchendo nome, sobrenome, telefone, data de nascimento, CPF, e-mail e senha.
2. No phpMyAdmin, aba **SQL** do banco `nexora`, rode o comando abaixo (trocando pelo e-mail que você cadastrou):

```sql
INSERT INTO lojas (id_usuario, nome_loja, cnpj, descricao)
SELECT id_usuario, 'Nexora Hardware Oficial', '12345678000199', 'Loja oficial de hardware e periféricos'
FROM usuarios
WHERE email = 'seu@email.com';
```

3. Se já estava logado, clique em **Sair** e entre de novo.

## Teste

Entre em http://localhost/Nexora/login.php com a conta criada acima e acesse o **Dashboard** (`dashboard.php`). Se aparecerem os indicadores (**Diário**, **Semanal** e **Mensal**) e a tabela de **Pedidos Recentes**, está tudo certo.

Para testar um cliente comum, cadastre outra conta em `cadastro.php`. Ao entrar, ela navega normalmente pelas vitrines (`inicio.php`), catálogo (`produtos.php`) e carrinho (`carrinho.php`), sem acesso administrativo à loja.

## Problemas comuns

| Erro | Causa | Solução |
|------|-------|---------|
| "Não foi possível conectar ao sistema" | Banco `nexora` não existe, MySQL desligado ou senha do MySQL diferente | Confira se o MySQL está com **Start** no XAMPP, se o banco foi criado (passo 4) e os dados de `assets/php/conexao.php` |
| `Table 'nexora.usuarios' doesn't exist` (ou outra tabela) | O arquivo `nexora.sql` do passo 5 não foi importado | Selecione o banco `nexora` no phpMyAdmin e execute o passo 5 |
| Erro de `Duplicate entry` ao cadastrar | O e-mail ou CPF informado já está cadastrado na tabela `usuarios` | Use outro e-mail/CPF ou remova o registro de teste no phpMyAdmin |
| "E-mail ou senha incorretos" | Usuário inserido manualmente no banco sem senha criptografada com `password_hash()` | Crie uma conta nova diretamente pela tela `cadastro.php` |
| Não abre o Dashboard | A conta não está autenticada ou não possui loja vinculada na tabela `lojas` | Rode o `INSERT` da seção "Criando o administrador / lojista" e entre de novo |
| Imagens, estilos ou cabeçalho não aparecem | As pastas `assets`, `img` ou `paginas` ficaram de fora da cópia | Copie a pasta `Nexora` inteira para `C:\xampp\htdocs\` |
| Apache não inicia | Porta 80 ocupada (Skype, IIS, VMware) | Feche o programa que usa a porta ou mude a porta do Apache no XAMPP |
| Acentos aparecem errados | Banco criado sem `utf8mb4` | Recrie o banco com o agrupamento `utf8mb4_general_ci` e importe o `nexora.sql` de novo |

## Segurança

Os dados deste guia (`root` sem senha) valem só para o XAMPP no computador de teste. Em um servidor de verdade, use um usuário e uma senha próprios do banco, e nunca coloque essa senha no GitHub nem na documentação.