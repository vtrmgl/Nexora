# Requisitos

## Requisitos funcionais
- RF01: O sistema deve permitir que o usuário se cadastre com nome, sobrenome, e-
mail, cpf, telefone, data de nascimento e senha;
- RF02: O sistema deve permitir que o usuário adicione produtos ao carrinho e aos favoritos;
- RF03: O sistema deve permitir que a loja altere o status do pedido;
- RF04: O sistema deve mostrar o histórico de pedidos do usuário;
- RF05: O sistema deve permitir que o usuário cadestre uma única loja vinculado ao seu perfil;
- RF06: O sistema deve mostrar dasdos da loja no dashboard;
- RF07: O sistema deve ter filtros de pesquisa (ordem de preço, tipo, mais comprados, etc).

## Requisitos não funcionais
- RNF01: As senhas devem ser guardadas criptografadas com password_hash();
- RNF02: O sistema deve ser feito em HTML 5, CSS 3, PHP 8 e MySQL 8;
- RNF03: O catálogo de produtos e o carrinho devem carregar em menos de 2 segundos em redes móveis.

## Regras de negócios
- RN01: Pedidos só podem ser feitos entre sábado e domingo só podem ser processados na segunda;
- RN02: Um pedido só pode ser cancelado enquanto o status for "Pagamento pendente" ou "Em processamento";
- RN03: Produto com estoque zero fica esgotado no catálogo.

## Histórias de usuários
- HU01: **Como** ususário, **quero** ver o status do meu pedido, **para** saber se meu pedido já foi enviado, está em rota de entrega, etc;
- HU02: **Como** visitante, **quero** buscar itens por palavra-chave e filtrar por categoria e preço **para** encontrar o que procuro rapidamente;
- HU03: **Como** usuário, **quero** consultar meu histórico de pedidos **para** acompanhar entregas e repetir compras anteriores.