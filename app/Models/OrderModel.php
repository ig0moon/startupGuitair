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
        'shipping_cost', 'discount', 'total', 'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Create order with strictly sequential number (GT-0001, GT-0002, etc.) using Query Builder
     */
    public function createOrder(array $orderData, array $items): array
    {
        $db = \Config\Database::connect();
        $orderData['status'] = 'pending';
        $orderData['created_at'] = date('Y-m-d H:i:s');
        $orderData['updated_at'] = date('Y-m-d H:i:s');
        $orderData['order_number'] = 'GERANDO...';

        try {
            // 1. Insert order using Query Builder
            $ordersBuilder = $db->table('orders');
            $ordersBuilder->insert($orderData);
            $orderId = $db->insertID();

            // 2. Generate strictly sequential order number
            $seqNumber = sprintf('GT-%04d', $orderId);
            $ordersBuilder->where('id', $orderId)->update(['order_number' => $seqNumber]);
            $orderData['id'] = $orderId;
            $orderData['order_number'] = $seqNumber;

            // 3. Insert items via Query Builder
            $itemsBuilder = $db->table('order_items');
            foreach ($items as $item) {
                $itemsBuilder->insert([
                    'order_id'     => $orderId,
                    'product_id'   => $item['id'] ?? null,
                    'product_name' => $item['name'] ?? 'Produto GuitAIr',
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'] ?? 1,
                    'subtotal'     => ($item['price'] * ($item['quantity'] ?? 1)),
                    'created_at'   => date('Y-m-d H:i:s')
                ]);
            }

            return ['success' => true, 'order' => $orderData];
        } catch (\Throwable $e) {
            // Fallback for session
            $simulatedCount = count(session('user_orders') ?? []) + 1;
            $orderData['id'] = $simulatedCount;
            $orderData['order_number'] = sprintf('GT-%04d', $simulatedCount);

            $userOrders = session('user_orders') ?? [];
            $userOrders[] = [
                'order' => $orderData,
                'items' => $items
            ];
            session()->set('user_orders', $userOrders);

            return ['success' => true, 'order' => $orderData, 'simulated' => true];
        }
    }

    /**
     * Get orders for a given user using Query Builder
     */
    public function getUserOrders(?int $userId, ?string $email = null): array
    {
        try {
            $db = \Config\Database::connect();
            $builder = $db->table('orders');
            $builder->orderBy('id', 'DESC');

            if ($userId) {
                $builder->where('user_id', $userId);
            } elseif ($email) {
                $builder->where('customer_email', $email);
            }

            $orders = $builder->get()->getResultArray();

            // Attach items to each order using Query Builder
            foreach ($orders as &$ord) {
                $itemsBuilder = $db->table('order_items');
                $itemsBuilder->select('order_items.*, products.image');
                $itemsBuilder->join('products', 'products.id = order_items.product_id', 'left');
                $itemsBuilder->where('order_items.order_id', $ord['id']);
                $ord['items'] = $itemsBuilder->get()->getResultArray();
            }

            return $orders;
        } catch (\Throwable $e) {
            $stored = session('user_orders') ?? [];
            return array_map(function($entry) {
                $o = $entry['order'];
                $o['items'] = $entry['items'];
                return $o;
            }, $stored);
        }
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
