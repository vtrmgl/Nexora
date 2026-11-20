# Dicionário de Dados

## Sumário das Tabelas
1. [`usuarios`](#1-usuarios)
2. [`enderecos`](#2-enderecos)
3. [`categorias`](#3-categorias)
4. [`lojas`](#4-lojas)
5. [`produtos`](#5-produtos)
6. [`cupons`](#6-cupons)
7. [`pedidos`](#7-pedidos)
8. [`itens_pedido`](#8-itens_pedido)
9. [`pagamentos`](#9-pagamentos)
10. [`avaliacoes`](#10-avaliacoes)
11. [`mensagens`](#11-mensagens)
12. [`favoritos`](#12-favoritos)
13. [`itens_carrinho`](#13-itens_carrinho)

---

## 1. `usuarios`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `int` | Não | `PK`, `auto_increment` | Identificador do usuário. |
| `nome` | `varchar(50)` | Não | — | Primeiro nome do usuário. |
| `sobrenome` | `varchar(50)` | Não | — | Sobrenome do usuário. |
| `email` | `varchar(255)` | Não | `unique` | E-mail do usuário |
| `senha` | `varchar(255)` | Não | — | Senha do usuário |
| `tipo` | `enum('Usuário', 'Administrador')` | Não | `default 'Usuário'` | Tipo do usuário |
| `cpf` | `char(11)` | Não | `unique` | CPF do usuário |
| `telefone` | `varchar(11)` | Não | — | Número de telefone do usuário |
| `data_de_nascimento` | `DATE` | Não | — | Data de nascimento |
| `foto_perfil` | `varchar(250)` | Sim | `default null` | Caminho ou URL da imagem de perfil |
| `data_cadastro` | `datetime` | Não | `default current_timestamp` | Data e hora de criação da conta |

* **Cardinalidade:** `usuarios (1) : (0..1) lojas` | `usuarios (1) : (N) enderecos` | `usuarios (1) : (N) pedidos` | `usuarios (1) : (N) avaliacoes` | `usuarios (1) : (N) favoritos` | `usuarios (1) : (N) itens_carrinho`.

---

## 2. `enderecos`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `int` | Não | `PK`, `auto_increment` | Identificador do endereço |
| `id_usuario` | `int` | Não | `FK` → `usuarios(id)` | Identificador do usuário proprietário do endereço |
| `cep` | `char(8)` | Não | — | Código de Endereçamento Postal |
| `logradouro` | `varchar(150)` | Não | — | Rua, avenida ou travessa |
| `numero` | `varchar(10)` | Não | — | Número do imóvel |
| `complemento` | `varchar(50)` | Sim | `default null` | Apartamento, bloco ou ponto de referência |
| `bairro` | `varchar(100)` | Não | — | Bairro do endereço |
| `cidade` | `varchar(50)` | Não | — | Cidade do endereço |
| `uf` | `char(2)` | Não | — | Unidade Federativa (estado) do endereço |

* `id_usuario` referencia `usuarios(id)` com `on delete cascade` e `on update cascade`
* **Cardinalidade:** `usuarios (1) : (N) enderecos` | `enderecos (1) : (N) pedidos`

---

## 3. `categorias`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `int` | Não | `PK`, `auto_increment` | Identificador da categoria |
| `nome` | `varchar(100)` | Não | `unique` | Nome da categoria |
| `descricao` | `text` | Não | — | Descrição dos tipos de produtos dessa categoria |

* **Cardinalidade:** `categorias (1) : (N) produtos`

---

## 4. `lojas`

| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `int` | Não | `PK`, `auto_increment` | Identificador da loja |
| `id_usuario` | `int` | Não | `FK` → `usuarios(id)`, `unique` | Usuário responsável pela loja |
| `nome` | `varchar(100)` | Não | — | Nome da loja |
| `email` | `varchar(255)` | Não | — | E-mail comercial da loja |
| `cnpj` | `char(14)` | Não | `unique` | Cadastro Nacional da Pessoa Jurídica |
| `descricao` | `text` | Sim | `default null` | Apresentação da loja |
| `logo` | `varchar(255)` | Sim | `default null` | Caminho do logotipo da loja |
| `banner` | `varchar(255)` | Sim | `default null` | Caminho da capa da página da loja |
| `avaliacao` | `decimal(3,2)` | Sim | `default null` | Nota média da loja |
| `data_criacao` | `datetime` | Não | `default current_timestamp` | Data e hora da criação da loja |
| `ativa` | `boolean` | Não | `default TRUE` | Indica se a loja está ativa (`TRUE`) ou suspensa (`FALSE`). |

* `id_usuario` referencia `usuarios(id)` com `on delete cascade` e `on update cascade`
* **Cardinalidade:** `usuarios (1) : (0..1) lojas` | `lojas (1) : (N) produtos` | `lojas (1) : (N) itens_pedido`

---

## 5. `produtos`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `int` | Não | `PK`, `auto_increment` | Identificador do produto |
| `id_categoria` | `int` | Não | `FK` → `categorias(id)` | Identificador da categoria deste produto |
| `id_loja` | `int` | Não | `FK` → `lojas(id)` | Identificador da loja que vende este produto |
| `nome` | `varchar(100)` | Não | — | Nome do produto |
| `marca` | `varchar(100)` | Não | — | Fabricante do produto |
| `modelo` | `varchar(100)` | Não | — | Modelo do produto |
| `condicao` | `enum('Novo', 'Usado', 'Recondicionado')` | Não | `default 'Novo'` | Estado do produto |
| `descricao` | `text` | Não | — | Descrição detalhada do produto |
| `especificacoes` | `text` | Não | — | Ficha técnica e especificações do produto |
| `estoque` | `int` | Não | `default 1`, `CHECK (estoque >= 0)` | Quantidade de unidades do produto disponíveis para venda |
| `preco` | `decimal(10,2)` | Não | `CHECK (preco > 0)` | Preço unitário atual de venda (em reais) |
| `data_cadastro` | `datetime` | Não | `default current_timestamp` | Data e hora do cadastro do produto |
| `ativo` | `boolean` | Não | `default TRUE` | Indica se o anúncio está visível (`TRUE`) ou oculto (`FALSE`) |
| `imagem` | `varchar(255)` | Não | — | Caminho da imagem do produto |

* `id_categoria` referencia `categorias(id)` com `on delete restrict` e `on update cascade`
* `id_loja` referencia `lojas(id)` com `on delete cascade` e `on update cascade`
* **Cardinalidade:** `categorias (1) : (N) produtos` | `lojas (1) : (N) produtos` | `produtos (1) : (N) itens_pedido` | `produtos (1) : (N) avaliacoes` | `produtos (1) : (N) favoritos` | `produtos (1) : (N) itens_carrinho`

---

## 6. `cupons`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `int` | Não | `PK`, `auto_increment` | Identificador do cupom |
| `codigo` | `varchar(30)` | Não | `unique` | Código promocional deste cupom |
| `tipo_desconto` | `enum('Porcentagem', 'Fixo')` | Não | `default 'Porcentagem'` | Modalidade de cálculo do desconto |
| `valor_desconto` | `decimal(10,2)` | Não | `CHECK (valor_desconto > 0)` | Percentual ou valor fixo do desconto |
| `valor_minimo_pedido` | `decimal(10,2)` | Sim | `default 0` | Valor mínimo necessário no pedido para aplicar o cupom |
| `data_validade` | `datetime` | Não | — | Data e hora limite para utilização do cupom |
| `limite_usos` | `int` | Sim | `default null` | Quantidade máxima de resgates |
| `usos_atuais` | `int` | Sim | `default 0`, `CHECK (usos_atuais >= 0)` | Quantidade de vezes em que o cupom já foi utilizado |
| `ativo` | `boolean` | Sim | `default TRUE` | Indica se o cupom está disponível |
| `data_criacao` | `datetime` | Não | `default current_timestamp` | Data e hora da criação do cupom |

* **Cardinalidade:** `cupons (1) : (N) pedidos`

---

## 7. `pedidos`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `int` | Não | `PK`, `auto_increment` | Identificador do pedido |
| `id_usuario` | `int` | Sim | `FK` → `usuarios(id)` | Identificador do usuário que realizou o pedido |
| `id_endereco` | `int` | Sim | `FK` → `enderecos(id)` | Identificador do endereço selecionado para a entrega |
| `id_cupom` | `int` | Sim | `FK` → `cupons(id)` | Identificador do cupom promocional aplicado na compra |
| `status_pedido` | `enum('Pagamento pendente', 'Em processamento', 'Confirmado', 'Em rota', 'Em rota de entrega', 'Cancelado', 'Reembolsado', 'Entregue')` | Sim | `default 'Pagamento pendente'` | Situação geral do pedido |
| `valor_total` | `decimal(10,2)` | Sim | `default 0`, `CHECK (valor_total >= 0)` | Valor final do pedido |
| `data_tempo_pedido` | `datetime` | Sim | `default current_timestamp` | Data e hora em que o pedido foi registrado |

* `id_usuario`, `id_endereco` e `id_cupom` utilizam `on delete set null` e `on update cascade`
* **Cardinalidade:** `usuarios (1) : (N) pedidos` | `enderecos (1) : (N) pedidos` | `cupons (1) : (N) pedidos` | `pedidos (1) : (N) itens_pedido` | `pedidos (1) : (N) pagamentos`

---

## 8. `itens_pedido`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `int` | Não | `PK`, `auto_increment` | Identificador do item no pedido |
| `id_pedido` | `int` | Não | `FK` → `pedidos(id)` | Identificador do pedido que o item pertence |
| `id_produto` | `int` | Sim | `FK` → `produtos(id)` | Identificador do produto adquirido |
| `id_loja` | `int` | Sim | `FK` → `lojas(id)` | Identificador da loja responsável pela separação e envio deste item |
| `quantidade` | `int` | Sim | `default 1`, `CHECK (quantidade > 0)` | Quantidade de unidades do produto para serem compradas |
| `preco_unitario` | `decimal(10,2)` | Sim | `default 0` | Preço unitário do momento da compra |
| `status_produto` | `enum('Em separação', 'Enviado', 'Entregue', 'Cancelado')` | Não | `default 'Em separação'` | Estado específico do item |

* `id_pedido` referencia `pedidos(id)` com `on delete cascade` e `on update cascade`
* `id_produto` e `id_loja` utilizam `on delete set null` e `on update cascade`
* **Cardinalidade:** `pedidos (1) : (N) itens_pedido` | `produtos (1) : (N) itens_pedido` | `lojas (1) : (N) itens_pedido`

---

## 9. `pagamentos`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `int` | Não | `PK`, `auto_increment` | Identificador do pagamento |
| `id_pedido` | `int` | Não | `FK` → `pedidos(id)` | Identificador do pedido vinculado ao pagamento |
| `forma_pagamento` | `enum('Pix', 'Crédito', 'Débito', 'Boleto')` | Não | — | Forma de pagamento selecionada pelo usuário |
| `status_pagamento` | `enum('Pendente', 'Confirmado', 'Cancelado')` | Não | — | Situação da transação |
| `valor_total` | `decimal(10,2)` | Não | `CHECK (valor_total > 0)` | Valor da transação |
| `data_pagamento` | `datetime` | Sim | `default current_timestamp` | Data e hora da confirmação do pagamento |

* `id_pedido` referencia `pedidos(id)` com `on delete restrict` e `on update cascade`
* **Cardinalidade:** `pedidos (1) : (N) pagamentos`

---

## 10. `avaliacoes`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `int` | Não | `PK`, `auto_increment` | Identificador da avaliação |
| `id_produto` | `int` | Não | `FK` → `produtos(id)` | Identificador do produto avaliado |
| `id_usuario` | `int` | Sim | `FK` → `usuarios(id)` | Identificador do usuário autor desta avaliação |
| `nota` | `TINYint` | Não | `CHECK (nota >= 1 AND nota <= 5)` | Pontuação dada pelo usuário ao produto |
| `comentario` | `text` | Sim | `default null` | Relato do usuário sobre o produto e a experiência |
| `data_avaliacao` | `datetime` | Não | `default current_timestamp` | Data e hora de envio da avaliação |

* `id_produto` referencia `produtos(id)` com `on delete cascade` e `on update cascade`
* `id_usuario` referencia `usuarios(id)` com `on delete set null` e `on update cascade`
* **Cardinalidade:** `produtos (1) : (N) avaliacoes` | `usuarios (1) : (N) avaliacoes`

---

## 11. `mensagens`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `int` | Não | `PK`, `auto_increment` | Identificador da mensagem |
| `nome_remetente` | `varchar(100)` | Não | — | Nome do remetente |
| `email_remetente` | `varchar(255)` | Não | — | E-mail do remetente|
| `assunto` | `varchar(150)` | Não | — | Assunto da mensagem |
| `mensagem` | `text` | Não | — | Mensagem enviada pelo remetente |
| `status_mensagem` | `enum('Enviada', 'Em análise', 'Respondida')` | Não | `default 'Enviada'` | Estado de triagem da mensagem |
| `data_envio` | `datetime` | Não | `default current_timestamp` | Data e hora de envio da mensagem |

* **Cardinalidade:** Entidade independente

---

## 12. `favoritos`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `int` | Não | `PK`, `auto_increment` | Identificador de registro do produto favoritado |
| `id_usuario` | `int` | Não | `FK` → `usuarios(id)`, `unique (id_usuario, id_produto)` | Identificador do usuário que favoritou o produto |
| `id_produto` | `int` | Não | `FK` → `produtos(id)`, `unique (id_usuario, id_produto)` | Identificador do produto favoritado |
| `data_adicao` | `datetime` | Não | `default current_timestamp` | Data e hora em que o produto foi favoritado |

* Chave única composta `unique (id_usuario, id_produto)` impede a duplicidade do mesmo produto nos favoritos de um mesmo usuário
* `id_usuario` e `id_produto` utilizam `on delete cascade` e `on update cascade`
* **Cardinalidade:** `usuarios (1) : (N) favoritos` | `produtos (1) : (N) favoritos`

---

## 13. `itens_carrinho`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `int` | Não | `PK`, `auto_increment` | Identificador do item no carrinho |
| `id_usuario` | `int` | Não | `FK` → `usuarios(id)`, `unique (id_usuario, id_produto)` | Identificador do usuário dono do carrinho |
| `id_produto` | `int` | Não | `FK` → `produtos(id)`, `unique (id_usuario, id_produto)` | Identificador do produto adicionado ao carrinho |
| `quantidade` | `int` | Não | `default 1`, `CHECK (quantidade > 0)` | Quantidade selecionada daquele produto |
| `data_adicao` | `datetime` | Não | `default current_timestamp` | Data e hora da adição do produto no carrinho. |

* Chave única composta `unique (id_usuario, id_produto)` impede a duplicidade do mesmo produto no carrinho de um mesmo usuário
* `id_usuario` e `id_produto` utilizam `on delete cascade` e `on update cascade`
* **Cardinalidade:** `usuarios (1) : (N) itens_carrinho` | `produtos (1) : (N) itens_carrinho`