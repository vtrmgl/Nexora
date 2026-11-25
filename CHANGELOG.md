# Changelog

Todas as mudanças importantes do Nexora ficam registradas aqui, da versão mais nova para a mais antiga.

## Planejado
- Carrinho de compras (`carrinho.php`) e finalização de pedidos com validação de estoque e endereço.
- Cadastro de endereços e painel de favoritos na página de perfil (`perfil.php`).
- Aplicação de cupons de desconto e seleção de formas de pagamento (Pix, Crédito, Débito e Boleto).
- Barra de pesquisa funcional no topo e catálogo carregando os produtos dinamicamente do banco de dados.
- Atualização do status dos pedidos diretamente pelo painel do lojista (`dashboard.php`).
- Layout responsivo adaptado para dispositivos móveis.

## [0.4.0] - 2026-11-24

### Adicionado
- Script de migração e homologação da Sprint 01 (`database/sprint01.sql`), criando as tabelas `setores`, `perfis` e `auditoria_usuarios`, adicionando chaves estrangeiras (`id_setor`, `id_perfil`) e campos de controle (`status`, `atualizado_em`, `desativado_em`) em `usuarios` e inserindo dados fictícios de teste.
- Página de perfil do usuário (`perfil.php`) com exibição de setor e perfil via `INNER JOIN`, edição de dados cadastrais, histórico de pedidos recentes e fluxo de encerramento/inativação confirmada da conta.
- Fluxo de encerramento seguro de sessão (`logout.php`) com limpeza de variáveis de sessão, expiração de cookie e destruição da sessão.
- Modal de confirmação antes de excluir/inativar usuários (`#modal-exclusao`) controlado por `assets/js/functions.js`, exibindo ID, nome e e-mail do usuário alvo antes do envio.
- Gestão de usuários ativos e trilha de auditoria (`auditoria_usuarios`) no painel administrativo (`dashboard.php`), com filtro de busca preparada por nome ou e-mail e histórico de operações recentes.
- Funções auxiliares de segurança e autorização em `assets/php/functions.php`: `exigirLogin()`, `usuarioPodeAcessarDashboard()`, `gerarTokenCsrf()`, `validarTokenCsrf()` e escape contra XSS com `e()`.
- Documentação completa da Sprint 01 (`docs/relatorio-sprint01.md`) com evidências visuais em `docs/evidencias/` e matriz de testes `T01` a `T12`.

### Alterado
- Tela de cadastro (`cadastro.php` e `assets/js/formularios.js`) atualizada para carregar dinamicamente `<select>` de setores e perfis ativos do banco de dados, validar entradas no cliente e no servidor, gravar o registro via transação PDO e registrar o evento `CADASTRO` na auditoria.
- Autenticação (`login.php`) ajustada para verificar se `status === 'Ativo'` antes de liberar o acesso e vincular os dados da loja ativa à sessão do usuário.
- Painel gerencial (`dashboard.php`) integrado ao banco de dados com indicadores dinâmicos por período (`Últimas 24h`, `Últimos 7 dias` e `Últimos 30 dias`), listagem real de pedidos recentes e exclusão lógica segura protegida por `POST` e token CSRF.
- Cabeçalho e rodapé compartilhados (`paginas/header.php` e `paginas/footer.php`) atualizados para exibir o atalho do `Dashboard` apenas para administradores ou usuários com loja vinculada.
- Estilos visuais (`assets/css/style.css`) expandidos com classes para selects, alertas de sucesso/erro, cards de perfil, badges, tabelas administrativas e modal de confirmação.
- Documentação técnica (`docs/banco-de-dados.md`, `docs/instalacao.md` e `README.md`) atualizada com o dicionário das novas tabelas, instruções de execução do `sprint01.sql`, explicação do `JOIN` e matriz de testes.

## [0.3.0] - 2026-11-19

### Adicionado
- Conexão com o banco de dados (`config/conexao.php`) configurada com `utf8mb4` para suportar acentuação corretamente e ocultando mensagens técnicas de erro na tela.
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