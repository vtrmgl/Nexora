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
| `id` | `INT` | Não | `PK`, `AUTO_INCREMENT` | Identificador do usuário. |
| `nome` | `VARCHAR(50)` | Não | — | Primeiro nome do usuário. |
| `sobrenome` | `VARCHAR(50)` | Não | — | Sobrenome do usuário. |
| `email` | `VARCHAR(255)` | Não | `UNIQUE` | E-mail do usuário |
| `senha` | `VARCHAR(255)` | Não | — | Senha do usuário |
| `cpf` | `CHAR(11)` | Não | `UNIQUE` | CPF do usuário |
| `telefone` | `VARCHAR(11)` | Não | — | Número de telefone do usuário |
| `data_de_nascimento` | `DATE` | Não | — | Data de nascimento |
| `foto_perfil` | `VARCHAR(250)` | Sim | `DEFAULT NULL` | Caminho ou URL da imagem de perfil |
| `data_cadastro` | `DATETIME` | Não | `DEFAULT CURRENT_TIMESTAMP` | Data e hora de criação da conta |

* **Cardinalidade:** `usuarios (1) : (0..1) lojas` | `usuarios (1) : (N) enderecos` | `usuarios (1) : (N) pedidos` | `usuarios (1) : (N) avaliacoes` | `usuarios (1) : (N) favoritos` | `usuarios (1) : (N) itens_carrinho`.

---

## 2. `enderecos`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `INT` | Não | `PK`, `AUTO_INCREMENT` | Identificador do endereço |
| `id_usuario` | `INT` | Não | `FK` → `usuarios(id)` | Identificador do usuário proprietário do endereço |
| `cep` | `CHAR(8)` | Não | — | Código de Endereçamento Postal |
| `logradouro` | `VARCHAR(150)` | Não | — | Rua, avenida ou travessa |
| `numero` | `VARCHAR(10)` | Não | — | Número do imóvel |
| `complemento` | `VARCHAR(50)` | Sim | `DEFAULT NULL` | Apartamento, bloco ou ponto de referência |
| `bairro` | `VARCHAR(100)` | Não | — | Bairro do endereço |
| `cidade` | `VARCHAR(50)` | Não | — | Cidade do endereço |
| `uf` | `CHAR(2)` | Não | — | Unidade Federativa (estado) do endereço |

* `id_usuario` referencia `usuarios(id)` com `ON DELETE CASCADE` e `ON UPDATE CASCADE`
* **Cardinalidade:** `usuarios (1) : (N) enderecos` | `enderecos (1) : (N) pedidos`

---

## 3. `categorias`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `INT` | Não | `PK`, `AUTO_INCREMENT` | Identificador da categoria |
| `nome` | `VARCHAR(100)` | Não | `UNIQUE` | Nome da categoria |
| `descricao` | `TEXT` | Não | — | Descrição dos tipos de produtos dessa categoria |

* **Cardinalidade:** `categorias (1) : (N) produtos`

---

## 4. `lojas`

| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `INT` | Não | `PK`, `AUTO_INCREMENT` | Identificador da loja |
| `id_usuario` | `INT` | Não | `FK` → `usuarios(id)`, `UNIQUE` | Usuário responsável pela loja |
| `nome` | `VARCHAR(100)` | Não | — | Nome da loja |
| `email` | `VARCHAR(255)` | Não | — | E-mail comercial da loja |
| `cnpj` | `CHAR(14)` | Não | `UNIQUE` | Cadastro Nacional da Pessoa Jurídica |
| `descricao` | `TEXT` | Sim | `DEFAULT NULL` | Apresentação da loja |
| `logo` | `VARCHAR(255)` | Sim | `DEFAULT NULL` | Caminho do logotipo da loja |
| `banner` | `VARCHAR(255)` | Sim | `DEFAULT NULL` | Caminho da capa da página da loja |
| `avaliacao` | `DECIMAL(3,2)` | Sim | `DEFAULT NULL` | Nota média da loja |
| `data_criacao` | `DATETIME` | Não | `DEFAULT CURRENT_TIMESTAMP` | Data e hora da criação da loja |
| `ativa` | `BOOLEAN` | Não | `DEFAULT TRUE` | Indica se a loja está ativa (`TRUE`) ou suspensa (`FALSE`). |

* `id_usuario` referencia `usuarios(id)` com `ON DELETE CASCADE` e `ON UPDATE CASCADE`
* **Cardinalidade:** `usuarios (1) : (0..1) lojas` | `lojas (1) : (N) produtos` | `lojas (1) : (N) itens_pedido`

---

## 5. `produtos`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `INT` | Não | `PK`, `AUTO_INCREMENT` | Identificador do produto |
| `id_categoria` | `INT` | Não | `FK` → `categorias(id)` | Identificador da categoria deste produto |
| `id_loja` | `INT` | Não | `FK` → `lojas(id)` | Identificador da loja que vende este produto |
| `nome` | `VARCHAR(100)` | Não | — | Nome do produto |
| `marca` | `VARCHAR(100)` | Não | — | Fabricante do produto |
| `modelo` | `VARCHAR(100)` | Não | — | Modelo do produto |
| `condicao` | `ENUM('Novo', 'Usado', 'Recondicionado')` | Não | `DEFAULT 'Novo'` | Estado do produto |
| `descricao` | `TEXT` | Não | — | Descrição detalhada do produto |
| `especificacoes` | `TEXT` | Não | — | Ficha técnica e especificações do produto |
| `estoque` | `INT` | Não | `DEFAULT 1`, `CHECK (estoque >= 0)` | Quantidade de unidades do produto disponíveis para venda |
| `preco` | `DECIMAL(10,2)` | Não | `CHECK (preco > 0)` | Preço unitário atual de venda (em reais) |
| `data_cadastro` | `DATETIME` | Não | `DEFAULT CURRENT_TIMESTAMP` | Data e hora do cadastro do produto |
| `ativo` | `BOOLEAN` | Não | `DEFAULT TRUE` | Indica se o anúncio está visível (`TRUE`) ou oculto (`FALSE`) |
| `imagem` | `VARCHAR(255)` | Não | — | Caminho da imagem do produto |

* `id_categoria` referencia `categorias(id)` com `ON DELETE RESTRICT` e `ON UPDATE CASCADE`
* `id_loja` referencia `lojas(id)` com `ON DELETE CASCADE` e `ON UPDATE CASCADE`
* **Cardinalidade:** `categorias (1) : (N) produtos` | `lojas (1) : (N) produtos` | `produtos (1) : (N) itens_pedido` | `produtos (1) : (N) avaliacoes` | `produtos (1) : (N) favoritos` | `produtos (1) : (N) itens_carrinho`

---

## 6. `cupons`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `INT` | Não | `PK`, `AUTO_INCREMENT` | Identificador do cupom |
| `codigo` | `VARCHAR(30)` | Não | `UNIQUE` | Código promocional deste cupom |
| `tipo_desconto` | `ENUM('Porcentagem', 'Fixo')` | Não | `DEFAULT 'Porcentagem'` | Modalidade de cálculo do desconto |
| `valor_desconto` | `DECIMAL(10,2)` | Não | `CHECK (valor_desconto > 0)` | Percentual ou valor fixo do desconto |
| `valor_minimo_pedido` | `DECIMAL(10,2)` | Sim | `DEFAULT 0` | Valor mínimo necessário no pedido para aplicar o cupom |
| `data_validade` | `DATETIME` | Não | — | Data e hora limite para utilização do cupom |
| `limite_usos` | `INT` | Sim | `DEFAULT NULL` | Quantidade máxima de resgates |
| `usos_atuais` | `INT` | Sim | `DEFAULT 0`, `CHECK (usos_atuais >= 0)` | Quantidade de vezes em que o cupom já foi utilizado |
| `ativo` | `BOOLEAN` | Sim | `DEFAULT TRUE` | Indica se o cupom está disponível |
| `data_criacao` | `DATETIME` | Não | `DEFAULT CURRENT_TIMESTAMP` | Data e hora da criação do cupom |

* **Cardinalidade:** `cupons (1) : (N) pedidos`

---

## 7. `pedidos`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `INT` | Não | `PK`, `AUTO_INCREMENT` | Identificador do pedido |
| `id_usuario` | `INT` | Sim | `FK` → `usuarios(id)` | Identificador do usuário que realizou o pedido |
| `id_endereco` | `INT` | Sim | `FK` → `enderecos(id)` | Identificador do endereço selecionado para a entrega |
| `id_cupom` | `INT` | Sim | `FK` → `cupons(id)` | Identificador do cupom promocional aplicado na compra |
| `status_pedido` | `ENUM('Pagamento pendente', 'Em processamento', 'Confirmado', 'Em rota', 'Em rota de entrega', 'Cancelado', 'Reembolsado', 'Entregue')` | Sim | `DEFAULT 'Pagamento pendente'` | Situação geral do pedido |
| `valor_total` | `DECIMAL(10,2)` | Sim | `DEFAULT 0`, `CHECK (valor_total >= 0)` | Valor final do pedido |
| `data_tempo_pedido` | `DATETIME` | Sim | `DEFAULT CURRENT_TIMESTAMP` | Data e hora em que o pedido foi registrado |

* `id_usuario`, `id_endereco` e `id_cupom` utilizam `ON DELETE SET NULL` e `ON UPDATE CASCADE`
* **Cardinalidade:** `usuarios (1) : (N) pedidos` | `enderecos (1) : (N) pedidos` | `cupons (1) : (N) pedidos` | `pedidos (1) : (N) itens_pedido` | `pedidos (1) : (N) pagamentos`

---

## 8. `itens_pedido`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `INT` | Não | `PK`, `AUTO_INCREMENT` | Identificador do item no pedido |
| `id_pedido` | `INT` | Não | `FK` → `pedidos(id)` | Identificador do pedido que o item pertence |
| `id_produto` | `INT` | Sim | `FK` → `produtos(id)` | Identificador do produto adquirido |
| `id_loja` | `INT` | Sim | `FK` → `lojas(id)` | Identificador da loja responsável pela separação e envio deste item |
| `quantidade` | `INT` | Sim | `DEFAULT 1`, `CHECK (quantidade > 0)` | Quantidade de unidades do produto para serem compradas |
| `preco_unitario` | `DECIMAL(10,2)` | Sim | `DEFAULT 0` | Preço unitário do momento da compra |
| `status_produto` | `ENUM('Em separação', 'Enviado', 'Entregue', 'Cancelado')` | Não | `DEFAULT 'Em separação'` | Estado específico do item |

* `id_pedido` referencia `pedidos(id)` com `ON DELETE CASCADE` e `ON UPDATE CASCADE`
* `id_produto` e `id_loja` utilizam `ON DELETE SET NULL` e `ON UPDATE CASCADE`
* **Cardinalidade:** `pedidos (1) : (N) itens_pedido` | `produtos (1) : (N) itens_pedido` | `lojas (1) : (N) itens_pedido`

---

## 9. `pagamentos`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `INT` | Não | `PK`, `AUTO_INCREMENT` | Identificador do pagamento |
| `id_pedido` | `INT` | Não | `FK` → `pedidos(id)` | Identificador do pedido vinculado ao pagamento |
| `forma_pagamento` | `ENUM('Pix', 'Crédito', 'Débito', 'Boleto')` | Não | — | Forma de pagamento selecionada pelo usuário |
| `status_pagamento` | `ENUM('Pendente', 'Confirmado', 'Cancelado')` | Não | — | Situação da transação |
| `valor_total` | `DECIMAL(10,2)` | Não | `CHECK (valor_total > 0)` | Valor da transação |
| `data_pagamento` | `DATETIME` | Sim | `DEFAULT CURRENT_TIMESTAMP` | Data e hora da confirmação do pagamento |

* `id_pedido` referencia `pedidos(id)` com `ON DELETE RESTRICT` e `ON UPDATE CASCADE`
* **Cardinalidade:** `pedidos (1) : (N) pagamentos`

---

## 10. `avaliacoes`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `INT` | Não | `PK`, `AUTO_INCREMENT` | Identificador da avaliação |
| `id_produto` | `INT` | Não | `FK` → `produtos(id)` | Identificador do produto avaliado |
| `id_usuario` | `INT` | Sim | `FK` → `usuarios(id)` | Identificador do usuário autor desta avaliação |
| `nota` | `TINYINT` | Não | `CHECK (nota >= 1 AND nota <= 5)` | Pontuação dada pelo usuário ao produto |
| `comentario` | `TEXT` | Sim | `DEFAULT NULL` | Relato do usuário sobre o produto e a experiência |
| `data_avaliacao` | `DATETIME` | Não | `DEFAULT CURRENT_TIMESTAMP` | Data e hora de envio da avaliação |

* `id_produto` referencia `produtos(id)` com `ON DELETE CASCADE` e `ON UPDATE CASCADE`
* `id_usuario` referencia `usuarios(id)` com `ON DELETE SET NULL` e `ON UPDATE CASCADE`
* **Cardinalidade:** `produtos (1) : (N) avaliacoes` | `usuarios (1) : (N) avaliacoes`

---

## 11. `mensagens`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `INT` | Não | `PK`, `AUTO_INCREMENT` | Identificador da mensagem |
| `nome_remetente` | `VARCHAR(100)` | Não | — | Nome do remetente |
| `email_remetente` | `VARCHAR(255)` | Não | — | E-mail do remetente|
| `assunto` | `VARCHAR(150)` | Não | — | Assunto da mensagem |
| `mensagem` | `TEXT` | Não | — | Mensagem enviada pelo remetente |
| `status_mensagem` | `ENUM('Enviada', 'Em análise', 'Respondida')` | Não | `DEFAULT 'Enviada'` | Estado de triagem da mensagem |
| `data_envio` | `DATETIME` | Não | `DEFAULT CURRENT_TIMESTAMP` | Data e hora de envio da mensagem |

* **Cardinalidade:** Entidade independente

---

## 12. `favoritos`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `INT` | Não | `PK`, `AUTO_INCREMENT` | Identificador de registro do produto favoritado |
| `id_usuario` | `INT` | Não | `FK` → `usuarios(id)`, `UNIQUE (id_usuario, id_produto)` | Identificador do usuário que favoritou o produto |
| `id_produto` | `INT` | Não | `FK` → `produtos(id)`, `UNIQUE (id_usuario, id_produto)` | Identificador do produto favoritado |
| `data_adicao` | `DATETIME` | Não | `DEFAULT CURRENT_TIMESTAMP` | Data e hora em que o produto foi favoritado |

* Chave única composta `UNIQUE (id_usuario, id_produto)` impede a duplicidade do mesmo produto nos favoritos de um mesmo usuário
* `id_usuario` e `id_produto` utilizam `ON DELETE CASCADE` e `ON UPDATE CASCADE`
* **Cardinalidade:** `usuarios (1) : (N) favoritos` | `produtos (1) : (N) favoritos`

---

## 13. `itens_carrinho`
| Campo | Tipo | Nulo | Chave / Restrições | Descrição |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `INT` | Não | `PK`, `AUTO_INCREMENT` | Identificador do item no carrinho |
| `id_usuario` | `INT` | Não | `FK` → `usuarios(id)`, `UNIQUE (id_usuario, id_produto)` | Identificador do usuário dono do carrinho |
| `id_produto` | `INT` | Não | `FK` → `produtos(id)`, `UNIQUE (id_usuario, id_produto)` | Identificador do produto adicionado ao carrinho |
| `quantidade` | `INT` | Não | `DEFAULT 1`, `CHECK (quantidade > 0)` | Quantidade selecionada daquele produto |
| `data_adicao` | `DATETIME` | Não | `DEFAULT CURRENT_TIMESTAMP` | Data e hora da adição do produto no carrinho. |

* Chave única composta `UNIQUE (id_usuario, id_produto)` impede a duplicidade do mesmo produto no carrinho de um mesmo usuário
* `id_usuario` e `id_produto` utilizam `ON DELETE CASCADE` e `ON UPDATE CASCADE`
* **Cardinalidade:** `usuarios (1) : (N) itens_carrinho` | `produtos (1) : (N) itens_carrinho`