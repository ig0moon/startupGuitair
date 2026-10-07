<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title><?= esc($pageTitle ?? 'GuitAIr') ?></title>
</head>

<body>
    <header>
        <div class="logo">
            <a href="<?= base_url('/') ?>"><img src="<?= base_url('img/guitair.png') ?>" alt="Logo da Startup"></a>
        </div>

        <nav class="navbar" aria-label="Navegação Principal">
            <ul>
                <li><button type="button" title="Página Inicial" aria-label="Início" onclick="document.location='<?= base_url('/') ?>'"><span class="material-symbols-outlined">home</span></button></li>
                <li><button type="button" title="Produtos" aria-label="Produtos" onclick="document.location='<?= base_url('produtos') ?>'"><span class="material-symbols-outlined">list</span></button></li>
                <li><button type="button" title="Sobre Nós" aria-label="Sobre" onclick="document.location='<?= base_url('sobre') ?>'"><span class="material-symbols-outlined">info</span></button></li>
                <li><button type="button" title="Central de Ajuda" aria-label="Ajuda" onclick="document.location='<?= base_url('ajuda') ?>'"><span class="material-symbols-outlined">help</span></button></li>
                <li><button type="button" title="Minha Conta" aria-label="Conta" onclick="document.location='<?= base_url('conta') ?>'"><span class="material-symbols-outlined">person</span></button></li>
                <li><button type="button" title="Carrinho de Compras" aria-label="Carrinho" onclick="document.location='<?= base_url('carrinho') ?>'"><span class="material-symbols-outlined">shopping_cart</span></button></li>
            </ul>
        </nav>

        <div class="nav-mobile-menu"></div>
        <script type="text/javascript" src="<?= base_url('js/navbarmobile.js') ?>"></script>

        <div class="navbar-mobile">
            <ul>
                <li><button onclick="document.location='<?= base_url('/') ?>'">Home</button></li>
                <li><button onclick="document.location='<?= base_url('produtos') ?>'">Produtos</button></li>
                <li><button onclick="document.location='<?= base_url('sobre') ?>'">Sobre</button></li>
                <li><button onclick="document.location='<?= base_url('ajuda') ?>'">Ajuda</button></li>
                <li><button onclick="document.location='<?= base_url('conta') ?>'">Conta</button></li>
                <li><button onclick="document.location='<?= base_url('carrinho') ?>'">Carrinho</button></li>
            </ul>
        </div>
    </header>

<div onclick="subirTela()" class="scrlbtn"></div>
<script type="text/javascript" src="<?= base_url('js/scrbtn.js') ?>"></script>
