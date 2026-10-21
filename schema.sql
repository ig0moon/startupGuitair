-- =======================================================
-- GuitAIr Database Schema (MySQL)
-- Compatível com MySQL 8.0+, MariaDB e Laragon / XAMPP
-- =======================================================

CREATE DATABASE IF NOT EXISTS `guitair_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `guitair_db`;

-- 1. TABELA DE USUÁRIOS
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(191) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(30) NULL,
    `role` ENUM('user', 'admin') DEFAULT 'user',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABELA DE PRODUTOS
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `name` VARCHAR(150) NOT NULL,
    `tagline` VARCHAR(255) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `old_price` DECIMAL(10,2) NULL,
    `image` VARCHAR(255) NOT NULL,
    `badge` VARCHAR(50) DEFAULT NULL,
    `description` TEXT NOT NULL,
    `features` TEXT NULL,
    `specs` TEXT NULL,
    `stock` INT NOT NULL DEFAULT 50,
    `rating` DECIMAL(2,1) DEFAULT 4.9,
    `reviews_count` INT DEFAULT 32,
    `featured` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABELA DE PEDIDOS
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_number` VARCHAR(50) NOT NULL UNIQUE,
    `user_id` INT UNSIGNED NULL,
    `customer_name` VARCHAR(150) NOT NULL,
    `customer_email` VARCHAR(191) NOT NULL,
    `customer_phone` VARCHAR(30) NULL,
    `address` VARCHAR(255) NOT NULL,
    `number` VARCHAR(50) NOT NULL,
    `complement` VARCHAR(100) NULL,
    `neighborhood` VARCHAR(100) NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `state` VARCHAR(10) NOT NULL,
    `zipcode` VARCHAR(20) NOT NULL,
    `payment_method` ENUM('pix', 'credit_card', 'boleto') NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,
    `shipping_cost` DECIMAL(10,2) DEFAULT 0.00,
    `discount` DECIMAL(10,2) DEFAULT 0.00,
    `total` DECIMAL(10,2) NOT NULL,
    `status` ENUM('pending', 'paid', 'shipped', 'completed', 'cancelled') DEFAULT 'pending',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. ITENS DO PEDIDO
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NULL,
    `product_name` VARCHAR(150) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `subtotal` DECIMAL(10,2) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. LEADS E MENSAGENS DA CAMPANHA MAIO LARANJA
CREATE TABLE IF NOT EXISTS `campaign_leads` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(191) NOT NULL,
    `age` INT NULL,
    `knows_campaign` VARCHAR(10) NOT NULL DEFAULT 'sim',
    `message` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================================
-- CARGA INICIAL DE PRODUTOS (SEED DATA)
-- =======================================================

INSERT INTO `products` (`slug`, `name`, `tagline`, `category`, `price`, `old_price`, `image`, `badge`, `description`, `features`, `specs`, `stock`, `rating`, `reviews_count`, `featured`) VALUES
(
    'guitair-acustico',
    'GuitAIr Acoustic Pro',
    'Violão acústico inteligente com reconhecimento de acordes e afinação neural',
    'violao',
    1899.00,
    2299.00,
    'img/violao.png',
    'Mais Vendido',
    'Instrumento acústico inteligente de alta precisão com IA embarcada. Equipado com microfones piezoelétricos inteligentes, guia visual de escala com micro-LEDs no braço e conexão direta com o app GuitAIr. Perfeito tanto para iniciantes acelerarem seu aprendizado quanto para compositores criarem arranjos complexos.',
    '["Afinação Automática Assistida por IA", "Sensores de Toque no Braço com Feedback Tátil", "Conexão Bluetooth 5.3 com App GuitAIr", "Bateria de Longa Duração (até 40 horas de uso contínuo)", "Madeiras Nobres Selecionadas: Tampo em Spruce Maciço"]',
    '{"Tampo": "Spruce Maciço", "Braço": "Mahogany com Micro-LEDs Embutidos", "Conectividade": "Bluetooth 5.3 / USB-C Áudio", "Autonomia": "40h", "Peso": "2.1 kg"}',
    35,
    4.9,
    48,
    1
),
(
    'electric-guitair',
    'Electric GuitAIr Neural',
    'Guitarra elétrica do futuro com sintetizador neural e simulação analógica em tempo real',
    'guitarra',
    3499.00,
    4199.00,
    'img/guitarra.png',
    'Lançamento',
    'A revolução das guitarras elétricas. Equipada com processador neural de sinal DSP em tempo real, permite simular mais de 100 pedais lendários, caixas e cabeçotes sem precisar de pedalboards pesados. Interface de áudio 24-bit/96kHz embutida via USB-C com latência inferior a 1.2ms.',
    '["Processador Neural DSP Quad-Core Integrado", "Simulação de 120+ Amplificadores e Pedais Famosos", "Saída Direta USB-C com Interface de Áudio 24-bit/96kHz", "Captadores Ativos Humbuckers Híbridos", "Corpo em Alder Premium com Acabamento Fosco Aeroespacial"]',
    '{"Corpo": "Alder Selecionado", "Escala": "Rosewood 24 Trastes Jumbo", "DSP": "Neural Audio Engine 64-bit", "Latência": "< 1.2ms", "Conexões": "P10 Hi-Z, USB-C, Fone P2, Bluetooth MIDI"}',
    20,
    5.0,
    64,
    1
),
(
    'drumia-beats',
    'DrumIA Smart Beats',
    'Bateria eletrônica responsiva com inteligência de metrônomo adaptativo e pads sensíveis',
    'bateria',
    4299.00,
    4999.00,
    'img/bateria.png',
    'Destaque',
    'O kit de bateria inteligente que se adapta à sua precisão rítmica. Utiliza redes neurais para analisar suas viradas, timing e groove, sugerindo exercícios personalizados e corrigindo desvios de tempo em frações de segundo. Pads em mesh com resposta idêntica a peles acústicas reais.',
    '["Módulo com Inteligência Artificial de Correção de Groove", "Pads Mesh Dual-Zone Silenciosos de Alto Desempenho", "Mais de 500 Samples Gravados em Estúdios Renomados", "Integração Instantânea com Qualquer DAW (Ableton, FL, Logic)", "Rack de Alumínio Robusto e Compacto Dobrável"]',
    '{"Configuração": "Snare 10\", 3x Toms 8\", Crash 12\", Ride 14\", Hi-Hat com pedal óptico", "Módulo": "DrumIA Brain v2", "Samples": "500+ Sons Gravados em 96kHz", "Dimensões": "Ajustável para qualquer espaço"}',
    15,
    4.8,
    29,
    1
);

-- USUÁRIO DE DEMONSTRAÇÃO (Senha: 123456)
INSERT INTO `users` (`name`, `email`, `password_hash`, `role`) VALUES
('Usuário Demo', 'demo@guitair.com.br', '$2y$10$wKqPZ3J61H0H3e5Fq4/w2O1n/hPzB38bZc98fP3c6tEcm/R/fI8y2', 'user'),
('Admin GuitAIr', 'admin@guitair.com.br', '$2y$10$wKqPZ3J61H0H3e5Fq4/w2O1n/hPzB38bZc98fP3c6tEcm/R/fI8y2', 'admin');
