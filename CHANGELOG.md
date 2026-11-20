# Changelog

Todas as mudanças importantes do Nexora ficam registradas aqui, da versão mais nova para a mais antiga.

O número da versão segue o padrão MAIOR.MENOR.CORREÇÃO. Enquanto o sistema não estiver completo (faltam o carrinho, favoritos, a finalização de pedidos e a tela de perfil), as versões começam com 0.

## Planejado
- Carrinho de compras (`carrinho.php`) e finalização de pedidos com validação de estoque e endereço.
- Página de perfil (`perfil.php`) com edição de dados pessoais, cadastro de endereços, favoritos e histórico de pedidos.
- Aplicação de cupons de desconto e seleção de formas de pagamento (Pix, Crédito, Débito e Boleto).
- Barra de pesquisa funcional no topo e catálogo carregando os produtos dinamicamente do banco de dados.
- Atualização do status dos pedidos diretamente pelo painel do lojista (`dashboard.php`).
- Layout responsivo adaptado para dispositivos móveis.

## [0.3.0] - 2026-11-19

### Adicionado
- Conexão com o banco de dados (`assets/php/conexao.php`) configurada com `utf8mb4` para suportar acentuação corretamente e ocultando mensagens técnicas de erro na tela.
- Banco de dados `nexora` com as tabelas principais (`usuarios`, `lojas`, `categorias`, `produtos`, `enderecos`, `pedidos`, `itens_pedido`, `pagamentos`, `cupons` e `favoritos`).
- Conexão inicial com o MySQL via PDO.
- Login (`login.php`) com conferência de senha usando `password_verify()` e cadastro (`cadastro.php`) que grava a senha criptografada com `password_hash()`, além de nome, sobrenome, telefone, data de nascimento e CPF.
- Redirecionamento automático na entrada (`index.php`) conforme o estado de autenticação do usuário.
- Mensagem de erro no login padronizada para não revelar se um e-mail está cadastrado no banco.
- Regeneração do identificador de sessão (`session_regenerate_id()`) após o login.
- Validação de campos obrigatórios e verificação de duplicidade de e-mail e CPF no cadastro com o PHP.
- Ordem de inicialização e encerramento de sessão (`session_start()` e `session_destroy()`) nos fluxos de login e logout.

## [0.2.0] - 2026-11-18

### Adicionado
- Pasta `docs` na pasta raíz
- Arquivos `banco-de-dados.md`, `der.png`, `instalacao.md`, `manual-usuario.md`, `requisitos.md`, `telas-e-fluxos.md` na pasta `docs`
- Pasta `telas` na pasta `docs`
- Arquivos `01-inicio.png`, `02-produtos.png`, `03-login.png`, `04-cadastro.png`, `05-dashboard.png`, `06-contato.png`, `07-sobre.png` na pasta `telas`.
- `README.md` e `CHANGELOG.md` na pasta raiz do projeto.
- Pasta `php` na pasta `assets`. 
- Arquivo `functions.php` na pasta `php`.

## [0.1.0] - 2026-11-10

### Adicionado
- Estrutura inicial do site em PHP: páginas `index.php`, `inicio.php`, `produtos.php`, `sobre.php`, `contato.php`, `cadastro.php`, `login.php` e `dashboard.php`.
- Cabeçalho e rodapé compartilhados (`paginas/header.php` e `paginas/footer.php`).
- Pasta `assets` com `css/style.css` e `js/formularios.js`