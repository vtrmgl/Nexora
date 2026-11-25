USE nexora;

CREATE TABLE IF NOT EXISTS setores (
    id int auto_increment primary key,
    nome varchar(80) not null unique,
    ativo enum('Ativo', 'Inativo') not null default 'Ativo'
);
CREATE TABLE IF NOT EXISTS perfis (
    id int auto_increment primary key,
    nome varchar(80) not null unique,
    ativo enum('Ativo', 'Inativo') not null default 'Ativo'
);
CREATE TABLE IF NOT EXISTS auditoria_usuarios (
    id int auto_increment primary key,
    id_usuario_alvo int null,
    acao varchar(50) not null,
    autor varchar(150) not null,
    data_hora datetime not null default current_timestamp,
    resultado varchar(150) not null
);
ALTER TABLE usuarios
    ADD COLUMN id_setor int not null AFTER tipo,
    ADD COLUMN id_perfil int not null AFTER id_setor,
    ADD COLUMN status ENUM('Ativo', 'Inativo') not null default 'Ativo' AFTER id_perfil,
    ADD COLUMN atualizado_em datetime null AFTER data_cadastro,
    ADD COLUMN desativado_em datetime null AFTER atualizado_em,
    ADD FOREIGN KEY (id_setor) REFERENCES setores(id) ON UPDATE CASCADE,
    ADD FOREIGN KEY (id_perfil) REFERENCES perfis(id) ON UPDATE CASCADE;

INSERT IGNORE INTO setores (id, nome, ativo)
VALUES
    (1, 'Clientes / Varejo', 'Ativo'),
    (2, 'Vendas e Marketplace', 'Ativo'),
    (3, 'Suporte Técnico', 'Ativo'),
    (4, 'Logística e Estoque', 'Ativo'),
    (5, 'Administração TI', 'Ativo');

INSERT IGNORE INTO perfis (id, nome, ativo)
VALUES
    (1, 'Comprador Padrão', 'Ativo'),
    (2, 'Lojista Parceiro', 'Ativo'),
    (3, 'Operador de Suporte', 'Ativo'),
    (4, 'Administrador Geral', 'Ativo');

INSERT IGNORE INTO usuarios (id, nome, sobrenome, email, senha, tipo, id_setor, id_perfil, status, cpf, telefone, data_de_nascimento)
VALUES
    (100, 'Admin', 'Nexora', 'admin@nexora.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 5, 4, 'Ativo', '11122233344', '81999990001', '1998-05-10'),
    (101, 'Vitor', 'Azevedo', 'vtr@teste.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Usuário', 2, 2, 'Ativo', '22233344455', '81999990002', '2000-08-22'),
    (102, 'Bigode', 'Barbosa', 'bigas@teste.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Usuário', 1, 1, 'Ativo', '33344455566', '81999990003', '2002-01-15'),
    (103, 'Tchuco', 'Duarte', '22co@teste.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Usuário', 3, 3, 'Ativo', '44455566677', '81999990004', '1999-11-30');

INSERT IGNORE INTO lojas (id, id_usuario, nome, email, cnpj, descricao, ativa)
VALUES
    (1, 101, 'Nexora Hardware Oficial', 'loja@nexora.com', '12345678000199', 'Loja oficial de hardware e periféricos', 'Ativa');

INSERT IGNORE INTO categorias (id, nome, descricao)
VALUES
    (1, 'Processadores', 'CPUs intel e AMD'),
    (2, 'Periféricos', 'Mouses, teclados e headsets');

INSERT IGNORE INTO produtos (id, id_categoria, id_loja, nome, marca, modelo, condicao, descricao, especificacoes, estoque, preco, ativo, imagem)
VALUES
    (1, 1, 1, 'Processador AMD Ryzen 5 5600GE', 'AMD', '5600GE', 'Novo', 'Processador 6-Core', '6 Cores, 12 Threads, AM4', 15, 869.99, 1, 'https://media.pichau.com.br/media/catalog/product/cache/74c1057f7991b4edb2bc7bdaa94de933/1/0/100-100000261box.jpg'),
    (2, 2, 1, 'Mouse Gamer Corsair M65 Ultra', 'Corsair', 'M65 Ultra', 'Novo', 'Mouse óptico 26000 DPI', 'RGB, 8 botões', 30, 319.99, 1, 'https://images.kabum.com.br/produtos/fotos/593795/corsair-m65-rgb-ultra-gaming-mouse-backlit-rgb-led-optical-silver-alu-black-ch-9309411-na2_1729765851_gg.jpg');

INSERT IGNORE INTO pedidos (id, id_usuario, status_pedido, valor_total, data_tempo_pedido)
VALUES
    (1, 102, 'Entregue', 869.99, NOW()),
    (2, 103, 'Em rota de entrega', 319.99, NOW()),
    (3, 101, 'Em processamento', 1189.98, DATE_SUB(NOW(), intERVAL 3 DAY));

INSERT IGNORE INTO itens_pedido (id, id_pedido, id_produto, id_loja, quantidade, preco_unitario, status_produto)
VALUES
    (1, 1, 1, 1, 1, 869.99, 'Entregue'),
    (2, 2, 2, 1, 1, 319.99, 'Enviado'),
    (3, 3, 1, 1, 1, 869.99, 'Em separação'),
    (4, 3, 2, 1, 1, 319.99, 'Em separação');