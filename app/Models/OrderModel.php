<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_number', 'user_id', 'customer_name', 'customer_email',
        'customer_phone', 'address', 'number', 'complement', 'neighborhood',
        'city', 'state', 'zipcode', 'payment_method', 'subtotal',
        'shipping_cost', 'discount', 'coupon_id', 'address_id', 'total', 'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Cria o pedido inteiro dentro de UMA transação: pedido, número, itens,
     * baixa de estoque e endereço entram juntos ou nenhum entra.
     *
     * - RN07: o pedido sempre pertence a um usuário.
     * - Os preços vêm do banco (products), nunca da sessão.
     * - O estoque é conferido e baixado (RN04).
     * - O cupom é conferido na tabela coupons (RN11).
     *
     * @param array $customer  user_id, customer_name, customer_email, customer_phone,
     *                         address, number, complement, neighborhood, city, state,
     *                         zipcode, payment_method
     * @param array $cartItems itens do carrinho (id, quantity, image, ...)
     * @return array ['success' => bool, 'order' => array, 'items' => array] ou
     *               ['success' => false, 'message' => string]
     */
    public function createOrder(array $customer, array $cartItems, ?string $couponCode = null): array
    {
        $userId = (int)($customer['user_id'] ?? 0);
        if ($userId <= 0) {
            return ['success' => false, 'message' => 'É preciso estar logado para finalizar a compra.'];
        }
        if (empty($cartItems)) {
            return ['success' => false, 'message' => 'Seu carrinho está vazio.'];
        }

        $db  = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $db->transBegin();
        try {
            // 1. Itens: preço, nome e estoque vêm do banco, com a linha travada
            $lines    = [];
            $subtotal = 0.0;
            foreach ($cartItems as $item) {
                $productId = (int)($item['id'] ?? 0);
                $qty       = max(1, min(99, (int)($item['quantity'] ?? 1)));

                $product = $this->exec($db, $db->query(
                    'SELECT id, name, price, stock FROM products WHERE id = ? AND active = 1 FOR UPDATE',
                    [$productId]
                ))->getRowArray();

                if (!$product) {
                    throw new \DomainException('Um dos produtos do carrinho não está mais disponível.');
                }
                if ((int)$product['stock'] < $qty) {
                    throw new \DomainException(
                        'Estoque insuficiente para "' . $product['name'] . '" (restam ' . (int)$product['stock'] . ').'
                    );
                }

                $price      = (float)$product['price'];
                $lineTotal  = round($price * $qty, 2);
                $subtotal  += $lineTotal;
                $lines[]    = array_merge($item, [
                    'id'       => (int)$product['id'],
                    'name'     => $product['name'],
                    'price'    => $price,
                    'quantity' => $qty,
                    'subtotal' => $lineTotal,
                ]);
            }
            $subtotal = round($subtotal, 2);

            // 2. Frete e cupom (RN09, RN11)
            $shipping = ($subtotal >= 2500) ? 0.00 : 45.00;
            $discount = 0.00;
            $couponId = null;

            if ($couponCode !== null && $couponCode !== '') {
                $coupon = (new CouponModel())->findValid($couponCode);
                if (!$coupon) {
                    throw new \DomainException('Cupom inválido ou expirado.');
                }
                $couponId = (int)$coupon['id'];
                if (!empty($coupon['discount_percent'])) {
                    $discount = round($subtotal * ((int)$coupon['discount_percent'] / 100), 2);
                }
                if (!empty($coupon['free_shipping'])) {
                    $shipping = 0.00;
                }
            }
            $discount = min($discount, $subtotal);
            $total    = round($subtotal + $shipping - $discount, 2);

            // 3. Endereço salvo do usuário (v1.3); o pedido guarda também uma cópia
            $addressId = $this->saveAddress($db, $userId, $customer, $now);

            // 4. Pedido (número provisório único; o definitivo usa o id)
            $order = [
                'order_number'   => 'TMP-' . bin2hex(random_bytes(8)),
                'user_id'        => $userId,
                'customer_name'  => $customer['customer_name'],
                'customer_email' => $customer['customer_email'],
                'customer_phone' => $customer['customer_phone'] ?: null,
                'address'        => $customer['address'],
                'number'         => $customer['number'],
                'complement'     => $customer['complement'] ?: null,
                'neighborhood'   => $customer['neighborhood'],
                'city'           => $customer['city'],
                'state'          => $customer['state'],
                'zipcode'        => $customer['zipcode'],
                'payment_method' => $customer['payment_method'],
                'subtotal'       => $subtotal,
                'shipping_cost'  => $shipping,
                'discount'       => $discount,
                'coupon_id'      => $couponId,
                'address_id'     => $addressId,
                'total'          => $total,
                'status'         => 'pending',
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
            $this->exec($db, $db->table('orders')->insert($order));
            $orderId = (int)$db->insertID();

            $orderNumber = sprintf('GT-%04d', $orderId);
            $this->exec($db, $db->table('orders')->where('id', $orderId)->update(['order_number' => $orderNumber]));
            $order['id']           = $orderId;
            $order['order_number'] = $orderNumber;

            // 5. Itens e baixa de estoque (RN04, RN06, RN10)
            foreach ($lines as $line) {
                $this->exec($db, $db->table('order_items')->insert([
                    'order_id'     => $orderId,
                    'product_id'   => $line['id'],
                    'product_name' => $line['name'],
                    'price'        => $line['price'],
                    'quantity'     => $line['quantity'],
                    'subtotal'     => $line['subtotal'],
                    'created_at'   => $now,
                ]));

                $this->exec($db, $db->query(
                    'UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?',
                    [$line['quantity'], $line['id'], $line['quantity']]
                ));
                if ($db->affectedRows() !== 1) {
                    throw new \DomainException('Estoque insuficiente para "' . $line['name'] . '".');
                }
            }

            $db->transCommit();
            return ['success' => true, 'order' => $order, 'items' => $lines];
        } catch (\DomainException $e) {
            $db->transRollback();
            return ['success' => false, 'message' => $e->getMessage()];
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Falha ao criar pedido: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Não foi possível concluir o pedido. Tente novamente.'];
        }
    }

    /**
     * Guarda o endereço do checkout na tabela addresses (se ainda não existir).
     * O primeiro endereço do usuário vira o principal (RN12).
     * Falha aqui não derruba o pedido: o pedido guarda a própria cópia.
     */
    protected function saveAddress($db, int $userId, array $c, string $now): ?int
    {
        if (!preg_match('/^[A-Z]{2}$/', (string)$c['state'])) {
            return null;
        }

        try {
            $existing = $db->query(
                'SELECT id FROM addresses WHERE user_id = ? AND zipcode = ? AND street = ? AND number = ? LIMIT 1',
                [$userId, $c['zipcode'], $c['address'], $c['number']]
            )->getRowArray();
            if ($existing) {
                return (int)$existing['id'];
            }

            $hasAny = (int)($db->query('SELECT COUNT(*) AS n FROM addresses WHERE user_id = ?', [$userId])
                               ->getRowArray()['n'] ?? 0);

            $ok = $db->table('addresses')->insert([
                'user_id'      => $userId,
                'zipcode'      => $c['zipcode'],
                'street'       => $c['address'],
                'number'       => $c['number'],
                'complement'   => $c['complement'] ?: null,
                'neighborhood' => $c['neighborhood'],
                'city'         => $c['city'],
                'state'        => $c['state'],
                'is_primary'   => $hasAny === 0 ? 1 : 0,
                'created_at'   => $now,
            ]);

            return $ok ? (int)$db->insertID() : null;
        } catch (\Throwable $e) {
            log_message('error', 'Falha ao salvar endereço: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Lança exceção se a consulta falhou (quando DBDebug está desligado o
     * CodeIgniter devolve false em vez de lançar).
     */
    protected function exec($db, $result)
    {
        if ($result === false) {
            $err = $db->error();
            throw new \RuntimeException($err['message'] ?? 'Erro no banco de dados.');
        }
        return $result;
    }

    /**
     * Pedidos de um usuário (RN07: todo pedido tem usuário).
     */
    public function getUserOrders(?int $userId, ?string $email = null): array
    {
        if (!$userId) {
            return [];
        }

        $db = \Config\Database::connect();
        $orders = $db->table('orders')
                     ->where('user_id', $userId)
                     ->orderBy('id', 'DESC')
                     ->get()
                     ->getResultArray();

        foreach ($orders as &$ord) {
            $ord['items'] = $db->table('order_items')
                               ->select('order_items.*, products.image')
                               ->join('products', 'products.id = order_items.product_id', 'left')
                               ->where('order_items.order_id', $ord['id'])
                               ->get()
                               ->getResultArray();
        }

        return $orders;
    }

    /**
     * Get all purchases grouped by user using Query Builder
     */
    public function getPurchasesByUser(?int $filterUserId = null): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('order_items');
        $builder->select('
            users.id as user_id,
            users.name as user_name,
            users.email as user_email,
            orders.id as order_id,
            orders.order_number,
            orders.created_at as order_date,
            orders.status as order_status,
            order_items.product_id,
            order_items.product_name,
            order_items.price,
            order_items.quantity,
            order_items.subtotal,
            products.image as product_image
        ');
        $builder->join('orders', 'orders.id = order_items.order_id');
        $builder->join('users', 'users.id = orders.user_id', 'left');
        $builder->join('products', 'products.id = order_items.product_id', 'left');

        if ($filterUserId) {
            $builder->where('orders.user_id', $filterUserId);
        }

        $builder->orderBy('orders.id', 'DESC');
        return $builder->get()->getResultArray();
    }
}
