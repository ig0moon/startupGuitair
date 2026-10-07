<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('index.php', 'Home::index');

// Sobre
$routes->get('sobre', 'About::index');
$routes->get('about.php', 'About::index');

// Produtos
$routes->get('produtos', 'Products::index');
$routes->get('prod.php', 'Products::index');

// Compra
$routes->match(['get', 'post'], 'comprar', 'Buy::index');
$routes->match(['get', 'post'], 'buy.php', 'Buy::index');

// Ajuda / Dúvidas
$routes->get('ajuda', 'Help::index');
$routes->get('help.php', 'Help::index');

// Conta
$routes->match(['get', 'post'], 'conta', 'Account::index');
$routes->match(['get', 'post'], 'acc.php', 'Account::index');

// Carrinho
$routes->get('carrinho', 'Cart::index');
$routes->get('cart.php', 'Cart::index');

// Campanha Maio Laranja
$routes->match(['get', 'post'], 'maio-laranja', 'OrangeMay::index');
$routes->match(['get', 'post'], 'orangemay.php', 'OrangeMay::index');
