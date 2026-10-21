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
$routes->get('produtos/(:segment)', 'Products::show/$1');
$routes->get('prod.php', 'Products::index');

// Compra & Checkout
$routes->match(['GET', 'POST'], 'comprar', 'Buy::index');
$routes->post('comprar/finalizar', 'Buy::checkout');
$routes->match(['GET', 'POST'], 'buy.php', 'Buy::index');

// Carrinho de Compras
$routes->get('carrinho', 'Cart::index');
$routes->post('carrinho/adicionar', 'Cart::add');
$routes->post('carrinho/atualizar', 'Cart::update');
$routes->get('carrinho/remover/(:segment)', 'Cart::remove/$1');
$routes->post('carrinho/remover', 'Cart::removePost');
$routes->get('carrinho/limpar', 'Cart::clear');
$routes->post('carrinho/cupom', 'Cart::applyCoupon');
$routes->get('cart.php', 'Cart::index');

// Conta / Autenticação
$routes->match(['GET', 'POST'], 'conta', 'Account::index');
$routes->post('conta/login', 'Account::login');
$routes->post('conta/cadastro', 'Account::register');
$routes->get('conta/sair', 'Account::logout');
$routes->match(['GET', 'POST'], 'acc.php', 'Account::index');

// Ajuda / Central de Dúvidas
$routes->get('ajuda', 'Help::index');
$routes->get('help.php', 'Help::index');

// Painel Administrativo (Acesso restrito por role no MySQL)
$routes->get('admin', 'Admin::index');
$routes->post('admin/alterar-funcao', 'Admin::changeRole');
$routes->get('admin/compras-usuarios', 'Admin::userPurchases');
$routes->get('admin/pedidos', 'Admin::orders');
$routes->post('admin/alterar-status-pedido', 'Admin::updateOrderStatus');

// Redirecionamentos de rotas antigas
$routes->addRedirect('maio-laranja', '/');
$routes->addRedirect('orangemay.php', '/');
