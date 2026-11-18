# Telas e Fluxos — Nexora

Este documento apresenta o mapa de telas do Nexora, explicando quais páginas fazem parte do sistema, quem pode acessá-las, suas funcionalidades e como elas se conectam.

## 1. Mapa de telas
| Tela | Arquivo | Quem acessa | O que faz |
|------|---------|-------------|-----------|
| Início | `inicio.php` | Todos | Apresenta a vitrine principal do Nexora, com banner promocional e seções horizontais de novidades, ofertas em destaque, processadores, placas de vídeo e memórias RAM. |
| Catálogo | `produtos.php` | Todos | Apresenta o ranking de produtos mais vendidos (última semana e último mês) e o catálogo completo separado por categorias técnicas (`#novidades`, `#ofertas`, `#processadores`, `#placas-de-video`, `#memorias-ram`, `#ssds`, `#coolers` e `#placas-mae`). |
| Sobre | `sobre.php` | Todos | Apresenta informações institucionais sobre o projeto Nexora, sua finalidade acadêmica e os dados do desenvolvedor. |
| Contato | `contato.php` | Todos | Apresenta endereço, telefone e e-mail de suporte, além de um formulário para envio de mensagens armazenadas na tabela `mensagens`. |
| Cadastro | `cadastro.php` | Visitantes | Permite criar uma conta informando nome, sobrenome, telefone, data de nascimento, CPF, e-mail e senha. |
| Login | `login.php` | Visitantes | Permite acessar uma conta cadastrada utilizando e-mail e senha, iniciando a sessão do usuário. |
| Carrinho | `carrinho.php` | Usuários logados | Lista os produtos adicionados pelo usuário (`itens_carrinho`), calcula o subtotal e permite concluir a compra. |
| Perfil | `perfil.php` | Usuários logados | Exibe os dados cadastrais da conta, endereços de entrega, lista de produtos salvos (`favoritos`) e histórico de pedidos do comprador. |
| Dashboard | `dashboard.php` | Lojistas (usuários com loja) | Apresenta indicadores de desempenho da loja (diário, semanal e mensal: pedidos realizados, enviados, entregues e renda) e a tabela de pedidos recentes. |

## 2. Arquivos que fazem parte de várias telas

Além das páginas principais, o Nexora possui arquivos compartilhados que auxiliam na organização visual, na navegação e no controle de acesso do sistema.

| Arquivo | Função |
|---------|--------|
| `paginas/header.php` | Inicia a sessão PHP quando necessário e renderiza o cabeçalho com barra de pesquisa, atalhos de categorias e menu dinâmico (exibe Perfil, Dashboard, Carrinho e Logout para usuários logados, ou Login para visitantes). |
| `paginas/footer.php` | Garante a sessão ativa e renderiza o rodapé institucional do Nexora com descrição do projeto, endereço e links de navegação conforme o estado de login. |
| `index.php` | Atua como ponto de entrada e roteador inicial do sistema: verifica se existe sessão ativa e redireciona para `perfil.php` (se logado) ou `inicio.php` (se visitante). |
| `assets/css/style.css` | Contém toda a estilização visual, grids de produtos, formulários e layout responsivo das páginas do Nexora. |
| `assets/js/formularios.js` | Controla as interações e validações no navegador dos formulários de cadastro, login e contato (como exibir/ocultar senha). |
| `assets/php/funcoes.php` | Centraliza funções auxiliares reutilizáveis do sistema: inicia a sessão PHP sem duplicidade (`iniciarSessaoSegura()`), verifica se há usuário autenticado (`usuarioEstaLogado()`) e executa redirecionamentos entre páginas (`redirecionar()`). |

## 3. Arquivos que processam dados

Algumas páginas também são responsáveis por receber informações enviadas pelos formulários, validar os dados e realizar operações no banco de dados ou redirecionamentos.

No Nexora, os formulários enviam os dados via `POST` para processamento e redirecionamento conforme o resultado de cada operação.

| Arquivo | Método | Recebe | Faz |
|---------|--------|--------|-----|
| `index.php` | GET | — | Verifica a variável `$_SESSION['usuario']` e redireciona o navegador para `perfil.php` (quando autenticado) ou `inicio.php` (quando visitante). |
| `cadastro.php` | POST | Nome, sobrenome, telefone, data de nascimento, CPF, e-mail, senha e confirmação de senha. | Valida os campos obrigatórios, verifica duplicidade de e-mail/CPF, criptografa a senha com `password_hash()` e registra o usuário na tabela `usuarios`. |
| `login.php` | POST | E-mail e senha. | Busca o cadastro na tabela `usuarios`, confere a senha com `password_verify()`, grava os dados na sessão e redireciona o usuário. |
| `contato.php` | POST | Nome, e-mail, assunto e mensagem. | Valida os campos enviados no formulário de atendimento e grava a solicitação na tabela `mensagens` com status inicial `'Enviada'`. |
| `carrinho.php` | POST | ID do produto, quantidade e endereço/cupom selecionado. | Atualiza os itens na tabela `itens_carrinho` ou registra a compra nas tabelas `pedidos` e `itens_pedido`. |
| `favoritos.php` | POST | ID do produto (`id_produto`) e ID do usuário logado. | Verifica se o produto já consta na tabela `favoritos`; caso não esteja, insere o registro, ou remove caso já esteja favoritado. |

## 4. Fluxo principal

O fluxo principal demonstra o caminho percorrido pelo usuário desde o acesso ao site até a consulta do painel ou realização de compras:

1. **Entrada no sistema (`index.php`):** O sistema verifica se há uma sessão ativa. Visitantes são encaminhados para `inicio.php`, enquanto usuários já autenticados seguem para `perfil.php`.
2. **Navegação e consulta (`inicio.php` e `produtos.php`):** O visitante navega pelas vitrines de novidades e ofertas ou filtra hardware por categoria na página de produtos.
3. **Autenticação (`cadastro.php` e `login.php`):** Para adicionar itens ao carrinho ou gerenciar uma loja, o usuário cria uma conta em `cadastro.php` ou acessa em `login.php`. Se o e-mail ou a senha estiverem incorretos, permanece em `login.php`; se estiverem corretos, a sessão é aberta.
4. **Área autenticada (`carrinho.php`, `perfil.php`):** Com a sessão ativa, `paginas/header.php` libera os atalhos restritos da conta, permitindo finalizar compras no carrinho ou acompanhar vendas e pedidos recentes no painel administrativo.