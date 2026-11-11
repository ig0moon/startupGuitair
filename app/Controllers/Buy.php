<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\OrderModel;

class Buy extends BaseController
{
    /**
     * Checkout page (RN07: só usuário logado finaliza compra)
     */
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
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

        if (empty($cart)) {
            $this->session->setFlashdata('error', 'Seu carrinho está vazio. Escolha um produto para continuar.');
            return redirect()->to(base_url('produtos'));
        }

        // O carrinho fica guardado na sessão; quem não está logado entra na conta e volta.
        $user = $this->session->get('user');
        if (!$user) {
            $this->session->setFlashdata('error', 'Entre na sua conta (ou crie uma) para finalizar a compra. Seu carrinho foi guardado.');
            return redirect()->to(base_url('conta'));
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
                $discount = round($subtotal * ($coupon['data']['discount_percent'] / 100), 2);
            }
            if (!empty($coupon['data']['free_shipping'])) {
                $shipping = 0.00;
            }
        }

        $total = max(0, ($subtotal - $discount + $shipping));

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
        // RN07: todo pedido pertence a um usuário cadastrado
        $user = $this->session->get('user');
        if (!$user) {
            $this->session->setFlashdata('error', 'Entre na sua conta para finalizar a compra.');
            return redirect()->to(base_url('conta'));
        }

        $cart = $this->session->get('cart') ?? [];
        if (empty($cart)) {
            $this->session->setFlashdata('error', 'Seu carrinho está vazio.');
            return redirect()->to(base_url('produtos'));
        }

        // Nada de valores inventados: os campos obrigatórios precisam vir preenchidos
        $post = fn(string $k): string => trim((string)$this->request->getPost($k));

        $state = strtoupper($post('estado'));
        $payment = $post('payment_method');

        $required = [
            'nome' => 'nome', 'email' => 'e-mail', 'endereco' => 'endereço', 'numero' => 'número',
            'bairro' => 'bairro', 'cidade' => 'cidade', 'cep' => 'CEP',
        ];
        foreach ($required as $field => $label) {
            if ($post($field) === '') {
                $this->session->setFlashdata('error', 'Preencha o campo obrigatório: ' . $label . '.');
                return redirect()->to(base_url('comprar'));
            }
        }
        if (!filter_var($post('email'), FILTER_VALIDATE_EMAIL)) {
            $this->session->setFlashdata('error', 'Informe um e-mail válido.');
            return redirect()->to(base_url('comprar'));
        }
        if (!preg_match('/^[A-Z]{2}$/', $state)) {
            $this->session->setFlashdata('error', 'Informe a UF com 2 letras (ex.: PR).');
            return redirect()->to(base_url('comprar'));
        }
        if (!in_array($payment, ['pix', 'credit_card', 'boleto'], true)) {
            $this->session->setFlashdata('error', 'Escolha uma forma de pagamento válida.');
            return redirect()->to(base_url('comprar'));
        }

        $customer = [
            'user_id'        => (int)$user['id'],
            'customer_name'  => $post('nome'),
            'customer_email' => strtolower($post('email')),
            'customer_phone' => $post('telefone'),
            'address'        => $post('endereco'),
            'number'         => $post('numero'),
            'complement'     => $post('complemento'),
            'neighborhood'   => $post('bairro'),
            'city'           => $post('cidade'),
            'state'          => $state,
            'zipcode'        => $post('cep'),
            'payment_method' => $payment,
        ];

        // Preços, estoque e cupom são conferidos no banco, dentro da transação
        $coupon = $this->session->get('cart_coupon');
        $orderModel = new OrderModel();
        $result = $orderModel->createOrder($customer, array_values($cart), $coupon['code'] ?? null);

        if (!$result['success']) {
            $this->session->setFlashdata('error', $result['message']);
            return redirect()->to(base_url('comprar'));
        }

        // Clear cart
        $this->session->remove('cart');
        $this->session->remove('cart_coupon');

        // Store confirmed order in flashdata
        $this->session->setFlashdata('confirmed_order', $result['order']);
        $this->session->setFlashdata('confirmed_items', $result['items']);

        return view('buy_success', [
            'order'     => $result['order'],
            'items'     => $result['items'],
            'pageTitle' => 'GuitAIr - Pedido Confirmado com Sucesso!'
        ]);
    }
}
