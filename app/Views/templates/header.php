<?php
$currentRoute = uri_string();
$cartItems = session('cart') ?? [];
$cartCount = array_sum(array_column($cartItems, 'quantity'));
$currentUser = session('user');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'GuitAIr - Instrumentos Musicais Inteligentes com IA') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">
    
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <!-- Stylesheet with automatic cache buster -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>?v=<?= file_exists(FCPATH . 'css/style.css') ? filemtime(FCPATH . 'css/style.css') : time() ?>">
</head>

<body>
    <!-- Top Bar Header -->
    <header id="siteHeader">
        <div class="header-inner">
            <!-- Brand Logo -->
            <a href="<?= base_url('/') ?>" class="logo" aria-label="Página Inicial da GuitAIr">
                <img src="<?= base_url('img/guitair.png') ?>" alt="GuitAIr Logo">
                <span class="logo-badge">AI LABS</span>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="navbar" aria-label="Navegação Principal">
                <ul>
                    <li>
                        <a href="<?= base_url('/') ?>" class="nav-link <?= empty($currentRoute) ? 'active' : '' ?>">
                            <span class="material-symbols-outlined">home</span>
                            <span>Início</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('produtos') ?>" class="nav-link <?= (strpos($currentRoute, 'produtos') !== false) ? 'active' : '' ?>">
                            <span class="material-symbols-outlined">queue_music</span>
                            <span>Produtos</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('sobre') ?>" class="nav-link <?= (strpos($currentRoute, 'sobre') !== false) ? 'active' : '' ?>">
                            <span class="material-symbols-outlined">psychology</span>
                            <span>Sobre a IA</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('ajuda') ?>" class="nav-link <?= (strpos($currentRoute, 'ajuda') !== false) ? 'active' : '' ?>">
                            <span class="material-symbols-outlined">help</span>
                            <span>Ajuda</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Header Action Buttons -->
            <div class="header-actions">
                <!-- Admin Panel Button if user is Admin -->
                <?php if ($currentUser && ($currentUser['role'] ?? '') === 'admin'): ?>
                    <a href="<?= base_url('admin') ?>" class="btn btn-outline btn-sm" style="border-color: rgba(6, 182, 212, 0.5); color: var(--brand-cyan); padding: 6px 14px; font-size: 0.85rem;" title="Painel Administrativo">
                        <span class="material-symbols-outlined" style="font-size: 18px;">admin_panel_settings</span>
                        <span>Admin</span>
                    </a>
                <?php endif; ?>

                <!-- User Account -->
                <?php if ($currentUser): ?>
                    <a href="<?= base_url('conta') ?>" class="user-pill" title="Painel da Minha Conta">
                        <div class="user-avatar"><?= esc(strtoupper(substr($currentUser['name'], 0, 1))) ?></div>
                        <span><?= esc(explode(' ', $currentUser['name'])[0]) ?></span>
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('conta') ?>" class="btn-icon" title="Entrar na Minha Conta" aria-label="Minha Conta">
                        <span class="material-symbols-outlined">person</span>
                    </a>
                <?php endif; ?>

                <!-- Shopping Cart Button with Dynamic Badge -->
                <a href="<?= base_url('carrinho') ?>" class="btn-icon" title="Carrinho de Compras" aria-label="Carrinho">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    <?php if ($cartCount > 0): ?>
                        <span class="badge-count" id="cartCountBadge"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>

                <!-- Mobile Hamburger Button -->
                <button type="button" class="hamburger-btn" aria-label="Abrir Menu de Navegação">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div class="navbar-mobile">
            <ul>
                <?php if ($currentUser && ($currentUser['role'] ?? '') === 'admin'): ?>
                    <li><a href="<?= base_url('admin') ?>" style="color: var(--brand-cyan); border-color: rgba(6, 182, 212, 0.4);"><span class="material-symbols-outlined" style="color: var(--brand-cyan);">admin_panel_settings</span> Painel Administrativo</a></li>
                <?php endif; ?>
                <li><a href="<?= base_url('/') ?>"><span class="material-symbols-outlined">home</span> Início</a></li>
                <li><a href="<?= base_url('produtos') ?>"><span class="material-symbols-outlined">queue_music</span> Produtos Inteligentes</a></li>
                <li><a href="<?= base_url('sobre') ?>"><span class="material-symbols-outlined">psychology</span> Sobre o GuitAIr</a></li>
                <li><a href="<?= base_url('ajuda') ?>"><span class="material-symbols-outlined">help</span> Central de Ajuda</a></li>
                <li><a href="<?= base_url('carrinho') ?>"><span class="material-symbols-outlined">shopping_cart</span> Meu Carrinho (<?= $cartCount ?>)</a></li>
                <li><a href="<?= base_url('conta') ?>"><span class="material-symbols-outlined">person</span> <?= $currentUser ? 'Meu Painel' : 'Entrar / Criar Conta' ?></a></li>
            </ul>
        </div>
    </header>

    <!-- Main Content Wrapper -->
    <main class="main-content">
        <!-- Flash Messages Container -->
        <?php if (session()->getFlashdata('success')): ?>
            <div style="max-width: 1280px; margin: 20px auto 0; padding: 0 24px;">
                <div class="alert alert-success">
                    <span class="material-symbols-outlined">check_circle</span>
                    <div><?= session()->getFlashdata('success') ?></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div style="max-width: 1280px; margin: 20px auto 0; padding: 0 24px;">
                <div class="alert alert-error">
                    <span class="material-symbols-outlined">error</span>
                    <div><?= session()->getFlashdata('error') ?></div>
                </div>
            </div>
        <?php endif; ?>
