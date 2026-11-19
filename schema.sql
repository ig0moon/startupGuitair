-- =======================================================
-- GuitAIr - Script completo do banco de dados (MySQL 8+ / MariaDB 10.5+)
-- Cria o banco, todas as tabelas, chaves estrangeiras e dados iniciais.
--
-- Uso:  mysql -u root -p < schema.sql
--   ou  abrir no HeidiSQL / phpMyAdmin / Workbench e executar tudo.
--
-- ATENÇÃO: o script recria as tabelas (DROP TABLE). Rodar em um banco
-- existente apaga os dados atuais.
--
-- Relacionamento principal do trabalho (CRUD completo nas duas tabelas):
--     categories (1) ────< (N) products
-- =======================================================

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `guitair_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `guitair_db`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `addresses`;
DROP TABLE IF EXISTS `coupons`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `campaign_leads`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. USUÁRIOS -------------------------------------------
CREATE TABLE `users` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`          VARCHAR(150) NOT NULL,
    `email`         VARCHAR(191) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `phone`         VARCHAR(30)  NULL,
    `role`          ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    `created_at`    DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. CATEGORIAS (tabela "pai") --------------------------
CREATE TABLE `categories` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(100) NOT NULL,
    `slug`        VARCHAR(100) NOT NULL UNIQUE,
    `description` VARCHAR(255) NULL,
    `created_at`  DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. PRODUTOS (tabela "filha": cada produto pertence a uma categoria) ---
CREATE TABLE `products` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT UNSIGNED NOT NULL,
    `slug`        VARCHAR(100) NOT NULL UNIQUE,
    `name`        VARCHAR(150) NOT NULL,
    `tagline`     VARCHAR(255) NOT NULL,
    `price`       DECIMAL(10,2) NOT NULL,
    `old_price`   DECIMAL(10,2) NULL,
    `image`       VARCHAR(255) NOT NULL,
    `badge`       VARCHAR(50)  NULL,
    `description` TEXT NOT NULL,
    `features`    TEXT NULL,               -- JSON (lista de recursos)
    `specs`       TEXT NULL,               -- JSON (chave: valor)
    `stock`       INT NOT NULL DEFAULT 0,
    `featured`    TINYINT(1) NOT NULL DEFAULT 0,
    `active`      TINYINT(1) NOT NULL DEFAULT 1, -- exclusão lógica (mantém histórico de vendas)
    `created_at`  DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`)
        REFERENCES `categories` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `chk_products_stock` CHECK (`stock` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. CUPONS ---------------------------------------------
CREATE TABLE `coupons` (
    `id`               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code`             VARCHAR(30)  NOT NULL UNIQUE,
    `label`            VARCHAR(100) NOT NULL,
    `discount_percent` INT NULL,
    `free_shipping`    TINYINT(1) NOT NULL DEFAULT 0,
    `valid_until`      DATE NULL,
    `active`           TINYINT(1) NOT NULL DEFAULT 1,
    `created_at`       DATETIME NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. ENDEREÇOS DOS USUÁRIOS -----------------------------
CREATE TABLE `addresses` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`      INT UNSIGNED NOT NULL,
    `zipcode`      VARCHAR(20)  NOT NULL,
    `street`       VARCHAR(255) NOT NULL,
    `number`       VARCHAR(50)  NOT NULL,
    `complement`   VARCHAR(100) NULL,
    `neighborhood` VARCHAR(100) NOT NULL,
    `city`         VARCHAR(100) NOT NULL,
    `state`        CHAR(2)      NOT NULL,
    `is_primary`   TINYINT(1)   NOT NULL DEFAULT 0,
    `created_at`   DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. PEDIDOS --------------------------------------------
CREATE TABLE `orders` (
    `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_number`   VARCHAR(50)  NOT NULL UNIQUE,
    `user_id`        INT UNSIGNED NULL,
    `customer_name`  VARCHAR(150) NOT NULL,
    `customer_email` VARCHAR(191) NOT NULL,
    `customer_phone` VARCHAR(30)  NULL,
    `address`        VARCHAR(255) NOT NULL,
    `number`         VARCHAR(50)  NOT NULL,
    `complement`     VARCHAR(100) NULL,
    `neighborhood`   VARCHAR(100) NOT NULL,
    `city`           VARCHAR(100) NOT NULL,
    `state`          VARCHAR(10)  NOT NULL,
    `zipcode`        VARCHAR(20)  NOT NULL,
    `payment_method` ENUM('pix', 'credit_card', 'boleto') NOT NULL,
    `subtotal`       DECIMAL(10,2) NOT NULL,
    `shipping_cost`  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `discount`       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `coupon_id`      INT UNSIGNED NULL,
    `address_id`     INT UNSIGNED NULL,
    `total`          DECIMAL(10,2) NOT NULL,
    `status`         ENUM('pending', 'paid', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    `created_at`     DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_orders_user`    FOREIGN KEY (`user_id`)    REFERENCES `users` (`id`)     ON DELETE SET NULL,
    CONSTRAINT `fk_orders_coupon`  FOREIGN KEY (`coupon_id`)  REFERENCES `coupons` (`id`)   ON DELETE SET NULL,
    CONSTRAINT `fk_orders_address` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. ITENS DO PEDIDO ------------------------------------
CREATE TABLE `order_items` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id`     INT UNSIGNED NOT NULL,
    `product_id`   INT UNSIGNED NULL,
    `product_name` VARCHAR(150) NOT NULL,
    `price`        DECIMAL(10,2) NOT NULL,
    `quantity`     INT NOT NULL DEFAULT 1,
    `subtotal`     DECIMAL(10,2) NOT NULL,
    `created_at`   DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_order_items_order`   FOREIGN KEY (`order_id`)   REFERENCES `orders` (`id`)   ON DELETE CASCADE,
    CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. AVALIAÇÕES (uma por usuário em cada produto) -------
CREATE TABLE `reviews` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `user_id`    INT UNSIGNED NOT NULL,
    `rating`     TINYINT UNSIGNED NOT NULL,
    `comment`    TEXT NULL,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_reviews_user_product` (`user_id`, `product_id`),
    CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_reviews_user`    FOREIGN KEY (`user_id`)    REFERENCES `users` (`id`)    ON DELETE CASCADE,
    CONSTRAINT `chk_reviews_rating` CHECK (`rating` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. LEADS DA CAMPANHA MAIO LARANJA ---------------------
CREATE TABLE `campaign_leads` (
    `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`           VARCHAR(150) NOT NULL,
    `email`          VARCHAR(191) NOT NULL,
    `age`            INT NULL,
    `knows_campaign` VARCHAR(10) NOT NULL DEFAULT 'sim',
    `message`        TEXT NULL,
    `created_at`     DATETIME NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================================
-- DADOS INICIAIS
-- =======================================================

-- Usuários de teste (senhas: admin123 / cliente123)
INSERT INTO `users` (`name`, `email`, `password_hash`, `phone`, `role`) VALUES
('Administrador GuitAIr', 'admin@guitair.com',   '$2y$10$Ld6SlGcQQndmHRw6BysYFet4RiTD7VrDPuyQnSFt6Yd.RJVwlXrQK', NULL, 'admin'),
('Cliente Teste',         'cliente@guitair.com', '$2y$10$pY7bIgECCpNso6/ZHytdYOBUGXMJP9l1f7gImvRX6OEz/KMun3C32', NULL, 'user');

INSERT INTO `categories` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Violão Acústico',     'violao',    'Violões com afinação e reconhecimento de acordes por IA'),
(2, 'Guitarra Elétrica',   'guitarra',  'Guitarras com processamento neural de áudio'),
(3, 'Bateria Eletrônica',  'bateria',   'Kits de bateria com correção de groove'),
(4, 'Acessórios / Áudio',  'acessorio', 'Cabos, pedais, fones e interfaces');

INSERT INTO `products` (`category_id`, `slug`, `name`, `tagline`, `price`, `old_price`, `image`, `badge`, `description`, `features`, `specs`, `stock`, `featured`) VALUES
(
    1, 'guitair-acustico', 'GuitAIr Acoustic Pro',
    'Violão acústico inteligente com reconhecimento de acordes e afinação neural',
    1899.00, 2299.00, 'img/violao.png', 'Mais Vendido',
    'Instrumento acústico inteligente de alta precisão com IA embarcada. Equipado com microfones piezoelétricos inteligentes, guia visual de escala com micro-LEDs no braço e conexão direta com o app GuitAIr.',
    '["Afinação Automática Assistida por IA", "Sensores de Toque no Braço com Feedback Tátil", "Conexão Bluetooth 5.3 com App GuitAIr", "Bateria de Longa Duração (até 40 horas de uso contínuo)", "Madeiras Nobres Selecionadas: Tampo em Spruce Maciço"]',
    '{"Tampo": "Spruce Maciço", "Braço": "Mahogany com Micro-LEDs Embutidos", "Conectividade": "Bluetooth 5.3 / USB-C Áudio", "Autonomia": "40h", "Peso": "2.1 kg"}',
    35, 1
),
(
    2, 'electric-guitair', 'Electric GuitAIr Neural',
    'Guitarra elétrica do futuro com sintetizador neural e simulação analógica em tempo real',
    3499.00, 4199.00, 'img/guitarra.png', 'Lançamento',
    'A revolução das guitarras elétricas. Equipada com processador neural de sinal DSP em tempo real, permite simular mais de 100 pedais lendários, caixas e cabeçotes sem precisar de pedalboards pesados.',
    '["Processador Neural DSP Quad-Core Integrado", "Simulação de 120+ Amplificadores e Pedais Famosos", "Saída Direta USB-C com Interface de Áudio 24-bit/96kHz", "Captadores Ativos Humbuckers Híbridos", "Corpo em Alder Premium com Acabamento Fosco Aeroespacial"]',
    '{"Corpo": "Alder Selecionado", "Escala": "Rosewood 24 Trastes Jumbo", "DSP": "Neural Audio Engine 64-bit", "Latência": "< 1.2ms", "Conexões": "P10 Hi-Z, USB-C, Fone P2, Bluetooth MIDI"}',
    20, 1
),
(
    3, 'drumia-beats', 'DrumIA Smart Beats',
    'Bateria eletrônica responsiva com inteligência de metrônomo adaptativo e pads sensíveis',
    4299.00, 4999.00, 'img/bateria.png', 'Destaque',
    'O kit de bateria inteligente que se adapta à sua precisão rítmica. Utiliza redes neurais para analisar suas viradas, timing e groove, sugerindo exercícios personalizados.',
    '["Módulo com Inteligência Artificial de Correção de Groove", "Pads Mesh Dual-Zone Silenciosos de Alto Desempenho", "Mais de 500 Samples Gravados em Estúdios Renomados", "Integração Instantânea com Qualquer DAW (Ableton, FL, Logic)", "Rack de Alumínio Robusto e Compacto Dobrável"]',
    '{"Configuração": "Snare 10\", 3x Toms 8\", Crash 12\", Ride 14\", Hi-Hat", "Módulo": "DrumIA Brain v2", "Samples": "500+ Sons Gravados em 96kHz", "Dimensões": "Ajustável para qualquer espaço"}',
    15, 1
);

INSERT INTO `coupons` (`code`, `label`, `discount_percent`, `free_shipping`, `valid_until`, `active`) VALUES
('GUITAIR10',   '10% de desconto',                10,   0, NULL, 1),
('IA2026',      '15% de desconto de boas-vindas', 15,   0, NULL, 1),
('FRETEGRATIS', 'Frete Grátis',                   NULL, 1, NULL, 1);
