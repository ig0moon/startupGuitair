<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Cart extends BaseController
{
    /**
     * Display Shopping Cart page
     */
    public function index(): string
    {
        $cart = $this->getCartData();

        return view('cart', [
            'cart'      => $cart,
            'pageTitle' => 'GuitAIr - Carrinho de Compras'
        ]);
    }

    /**
     * Add product to cart
     */
    public function add()
    {
        $productId = $this->request->getPost('product_id') 
                  ?? $this->request->getPost('modelo')
                  ?? $this->request->getGet('id');
        $quantity  = max(1, (int)($this->request->getPost('quantity') ?? 1));

        $productModel = new ProductModel();
        $product = $productModel->findProduct($productId);

        if (!$product) {
            $this->session->setFlashdata('error', 'Produto não encontrado.');
            return redirect()->back();
        }

        $cart = $this->session->get('cart') ?? [];
        $key = (string)$product['id'];

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'id'       => $product['id'],
                'slug'     => $product['slug'],
                'name'     => $product['name'],
                'price'    => (float)$product['price'],
                'image'    => $product['image'],
                'tagline'  => $product['tagline'] ?? '',
                'quantity' => $quantity,
            ];
        }

        $this->session->set('cart', $cart);
        $this->session->setFlashdata('success', '<strong>' . esc($product['name']) . '</strong> foi adicionado ao seu carrinho!');

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'    => true,
                'cart_count' => array_sum(array_column($cart, 'quantity')),
                'message'    => 'Produto adicionado com sucesso!'
            ]);
        }

        $action = $this->request->getPost('action');
        if ($action === 'buy_now') {
            return redirect()->to(base_url('comprar'));
        }

        return redirect()->to(base_url('carrinho'));
    }

    /**
     * Update cart item quantities
     */
    public function update()
    {
        $quantities = $this->request->getPost('quantities') ?? [];
        $cart = $this->session->get('cart') ?? [];

        foreach ($quantities as $id => $qty) {
            $id = (string)$id;
            $qty = (int)$qty;
            if (isset($cart[$id])) {
                if ($qty <= 0) {
                    unset($cart[$id]);
                } else {
                    $cart[$id]['quantity'] = min(99, $qty);
                }
            }
        }

        $this->session->set('cart', $cart);
        $this->session->setFlashdata('success', 'Carrinho atualizado com sucesso.');

        return redirect()->to(base_url('carrinho'));
    }

    /**
     * Remove item from cart (GET)
     */
    public function remove(string $id)
    {
        $cart = $this->session->get('cart') ?? [];
        if (isset($cart[$id])) {
            $name = $cart[$id]['name'];
            unset($cart[$id]);
            $this->session->set('cart', $cart);
            $this->session->setFlashdata('success', 'Item "' . esc($name) . '" removido do carrinho.');
        }

        return redirect()->to(base_url('carrinho'));
    }

    /**
     * Remove item from cart (POST)
     */
    public function removePost()
    {
        $id = (string)$this->request->getPost('id');
        return $this->remove($id);
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        $this->session->remove('cart');
        $this->session->remove('cart_coupon');
        $this->session->setFlashdata('success', 'Carrinho esvaziado.');

        return redirect()->to(base_url('carrinho'));
    }

    /**
     * Apply discount coupon
     */
    public function applyCoupon()
    {
        $code = strtoupper(trim($this->request->getPost('coupon') ?? ''));
        $coupons = [
            'GUITAIR10' => ['discount_percent' => 10, 'label' => '10% de desconto'],
            'IA2026'    => ['discount_percent' => 15, 'label' => '15% de desconto de boas-vindas'],
            'FRETEGRATIS' => ['free_shipping' => true, 'label' => 'Frete Grátis']
        ];

        if (isset($coupons[$code])) {
            $this->session->set('cart_coupon', [
                'code' => $code,
                'data' => $coupons[$code]
            ]);
            $this->session->setFlashdata('success', 'Cupom <strong>' . esc($code) . '</strong> aplicado com sucesso!');
        } else {
            $this->session->remove('cart_coupon');
            $this->session->setFlashdata('error', 'Cupom inválido ou expirado. Tente GUITAIR10 ou IA2026.');
        }

        return redirect()->to(base_url('carrinho'));
    }

    /**
     * Helper to compute cart totals
     */
    protected function getCartData(): array
    {
        $rawCart = $this->session->get('cart') ?? [];
        $items = array_values($rawCart);

        $subtotal = 0;
        $totalItems = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
            $totalItems += $item['quantity'];
        }

        // Coupon calculation
        $coupon = $this->session->get('cart_coupon');
        $discount = 0;
        $shipping = ($totalItems > 0) ? 45.00 : 0.00;

        if ($coupon) {
            if (!empty($coupon['data']['discount_percent'])) {
                $discount = ($subtotal * ($coupon['data']['discount_percent'] / 100));
            }
            if (!empty($coupon['data']['free_shipping'])) {
                $shipping = 0.00;
            }
        }

        // Free shipping above R$ 2500
        if ($subtotal >= 2500.00) {
            $shipping = 0.00;
        }

        $total = max(0, ($subtotal - $discount + $shipping));

        return [
            'items'       => $items,
            'total_items' => $totalItems,
            'subtotal'    => $subtotal,
            'discount'    => $discount,
            'shipping'    => $shipping,
            'total'       => $total,
            'coupon'      => $coupon,
        ];
    }
}
