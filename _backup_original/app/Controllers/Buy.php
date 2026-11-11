<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\OrderModel;

class Buy extends BaseController
{
    /**
     * Checkout page
     */
    public function index(): string
    {
        $productModel = new ProductModel();

        // Check if coming directly from a product button (amarelo, azul, vermelho, or product slug/id)
        $modelo = $this->request->getPost('modelo') 
               ?? $this->request->getGet('modelo')
               ?? $this->request->getPost('product_id')
               ?? $this->request->getGet('id');

        $cart = $this->session->get('cart') ?? [];

        if ($modelo) {
            $selectedProduct = $productModel->findProduct($modelo);
            if ($selectedProduct) {
                // If cart doesn't have it, add it
                $key = (string)$selectedProduct['id'];
                if (!isset($cart[$key])) {
                    $cart[$key] = [
                        'id'       => $selectedProduct['id'],
                        'slug'     => $selectedProduct['slug'],
                        'name'     => $selectedProduct['name'],
                        'price'    => (float)$selectedProduct['price'],
                        'image'    => $selectedProduct['image'],
                        'tagline'  => $selectedProduct['tagline'] ?? '',
                        'quantity' => 1,
                    ];
                    $this->session->set('cart', $cart);
                }
            }
        }

        // If cart is still empty, add default featured product so user can test checkout smoothly
        if (empty($cart)) {
            $default = $productModel->findProduct(1);
            if ($default) {
                $cart[(string)$default['id']] = [
                    'id'       => $default['id'],
                    'slug'     => $default['slug'],
                    'name'     => $default['name'],
                    'price'    => (float)$default['price'],
                    'image'    => $default['image'],
                    'tagline'  => $default['tagline'] ?? '',
                    'quantity' => 1,
                ];
                $this->session->set('cart', $cart);
            }
        }

        // Calculate checkout totals
        $items = array_values($cart);
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $coupon = $this->session->get('cart_coupon');
        $discount = 0;
        $shipping = ($subtotal >= 2500) ? 0.00 : 45.00;

        if ($coupon) {
            if (!empty($coupon['data']['discount_percent'])) {
                $discount = ($subtotal * ($coupon['data']['discount_percent'] / 100));
            }
            if (!empty($coupon['data']['free_shipping'])) {
                $shipping = 0.00;
            }
        }

        $total = max(0, ($subtotal - $discount + $shipping));

        $user = $this->session->get('user');

        return view('buy', [
            'items'     => $items,
            'subtotal'  => $subtotal,
            'discount'  => $discount,
            'shipping'  => $shipping,
            'total'     => $total,
            'user'      => $user,
            'coupon'    => $coupon,
            'modelo'    => $modelo ?? '',
            'pageTitle' => 'GuitAIr - Finalizar Compra Segura'
        ]);
    }

    /**
     * Process and finalize the order
     */
    public function checkout()
    {
        $cart = $this->session->get('cart') ?? [];
        if (empty($cart)) {
            $this->session->setFlashdata('error', 'Seu carrinho está vazio.');
            return redirect()->to(base_url('produtos'));
        }

        $items = array_values($cart);
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $coupon = $this->session->get('cart_coupon');
        $discount = 0;
        $shipping = ($subtotal >= 2500) ? 0.00 : 45.00;

        if ($coupon) {
            if (!empty($coupon['data']['discount_percent'])) {
                $discount = ($subtotal * ($coupon['data']['discount_percent'] / 100));
            }
            if (!empty($coupon['data']['free_shipping'])) {
                $shipping = 0.00;
            }
        }

        $total = max(0, ($subtotal - $discount + $shipping));

        $orderData = [
            'user_id'        => $this->session->get('user')['id'] ?? null,
            'customer_name'  => $this->request->getPost('nome') ?? 'Cliente GuitAIr',
            'customer_email' => $this->request->getPost('email') ?? 'cliente@exemplo.com',
            'customer_phone' => $this->request->getPost('telefone') ?? '',
            'address'        => $this->request->getPost('endereco') ?? 'Rua da Inovação Musical',
            'number'         => $this->request->getPost('numero') ?? '100',
            'complement'     => $this->request->getPost('complemento') ?? '',
            'neighborhood'   => $this->request->getPost('bairro') ?? 'Centro',
            'city'           => $this->request->getPost('cidade') ?? 'São Paulo',
            'state'          => $this->request->getPost('estado') ?? 'SP',
            'zipcode'        => $this->request->getPost('cep') ?? '01001-000',
            'payment_method' => $this->request->getPost('payment_method') ?? 'pix',
            'subtotal'       => $subtotal,
            'shipping_cost'  => $shipping,
            'discount'       => $discount,
            'total'          => $total,
        ];

        $orderModel = new OrderModel();
        $result = $orderModel->createOrder($orderData, $items);

        // Clear cart
        $this->session->remove('cart');
        $this->session->remove('cart_coupon');

        // Store confirmed order in flashdata
        $this->session->setFlashdata('confirmed_order', $result['order']);
        $this->session->setFlashdata('confirmed_items', $items);

        return view('buy_success', [
            'order'     => $result['order'],
            'items'     => $items,
            'pageTitle' => 'GuitAIr - Pedido Confirmado com Sucesso!'
        ]);
    }
}
