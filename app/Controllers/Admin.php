<?php

namespace App\Controllers;

class Admin extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Check if currently logged in user has admin role in database via Query Builder
     */
    protected function checkAdminAccess()
    {
        $sessionUser = session('user');
        if (!$sessionUser) {
            return redirect()->to(base_url('conta'))->with('error', 'Por favor, faça login para acessar o painel.');
        }

        // Query Builder: check directly in users table
        $userInDb = $this->db->table('users')
                             ->select('id, name, email, role')
                             ->where('id', $sessionUser['id'])
                             ->get()
                             ->getRowArray();

        if (!$userInDb || $userInDb['role'] !== 'admin') {
            return redirect()->to(base_url('conta'))->with('error', 'Acesso negado: sua conta não possui privilégios de administrador no banco de dados.');
        }

        return null; // Allowed
    }

    /**
     * Admin Dashboard: Overview & User Access Management
     */
    public function index()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        // Query Builder: fetch statistics
        $totalUsers = $this->db->table('users')->countAllResults();
        $totalOrders = $this->db->table('orders')->countAllResults();
        $revenueRow = $this->db->table('orders')->selectSum('total')->get()->getRowArray();
        $totalRevenue = $revenueRow['total'] ?? 0;

        // Query Builder: list all users
        $users = $this->db->table('users')
                          ->select('id, name, email, phone, role, created_at')
                          ->orderBy('id', 'ASC')
                          ->get()
                          ->getResultArray();

        return view('admin/dashboard', [
            'totalUsers'   => $totalUsers,
            'totalOrders'  => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'users'        => $users,
            'pageTitle'    => 'GuitAIr - Painel Administrativo'
        ]);
    }

    /**
     * Change user role in the database using Query Builder
     */
    public function changeRole()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $userId = (int)$this->request->getPost('user_id');
        $newRole = $this->request->getPost('role');

        if (!in_array($newRole, ['user', 'admin'])) {
            return redirect()->back()->with('error', 'Função inválida.');
        }

        // Query Builder: update role directly in database
        $this->db->table('users')
                 ->where('id', $userId)
                 ->update([
                     'role'       => $newRole,
                     'updated_at' => date('Y-m-d H:i:s')
                 ]);

        // If the logged in user changed their own role, update session
        $sessionUser = session('user');
        if ($sessionUser && $sessionUser['id'] == $userId) {
            $sessionUser['role'] = $newRole;
            session()->set('user', $sessionUser);
        }

        return redirect()->back()->with('success', 'Nível de acesso do usuário ID #' . $userId . ' alterado para "' . strtoupper($newRole) . '" com sucesso!');
    }

    /**
     * View products bought by user (Query Builder)
     */
    public function userPurchases()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $filterUserId = $this->request->getGet('usuario_id');

        // Query Builder: list all users for dropdown filter
        $allUsers = $this->db->table('users')
                             ->select('id, name, email')
                             ->orderBy('name', 'ASC')
                             ->get()
                             ->getResultArray();

        // Query Builder: fetch purchases joined across order_items, orders, users, and products
        $builder = $this->db->table('order_items');
        $builder->select('
            users.id as user_id,
            users.name as user_name,
            users.email as user_email,
            orders.id as order_id,
            orders.order_number,
            orders.created_at as order_date,
            orders.status as order_status,
            order_items.product_name,
            order_items.price,
            order_items.quantity,
            order_items.subtotal,
            products.image as product_image
        ');
        $builder->join('orders', 'orders.id = order_items.order_id', 'inner');
        $builder->join('users', 'users.id = orders.user_id', 'left');
        $builder->join('products', 'products.id = order_items.product_id', 'left');

        if (!empty($filterUserId)) {
            $builder->where('orders.user_id', (int)$filterUserId);
        }

        $builder->orderBy('orders.id', 'DESC');
        $purchases = $builder->get()->getResultArray();

        // Group by user for structured presentation
        $groupedByUser = [];
        foreach ($purchases as $row) {
            $uId = $row['user_id'] ?? 'guest';
            $uName = $row['user_name'] ?? 'Cliente Convidado (' . $row['user_email'] . ')';
            
            if (!isset($groupedByUser[$uId])) {
                $groupedByUser[$uId] = [
                    'user_id'    => $uId,
                    'user_name'  => $uName,
                    'user_email' => $row['user_email'],
                    'items'      => [],
                    'total_spent'=> 0
                ];
            }
            $groupedByUser[$uId]['items'][] = $row;
            $groupedByUser[$uId]['total_spent'] += $row['subtotal'];
        }

        return view('admin/user_purchases', [
            'allUsers'       => $allUsers,
            'groupedByUser'  => $groupedByUser,
            'selectedUserId' => $filterUserId,
            'totalRecords'   => count($purchases),
            'pageTitle'      => 'GuitAIr Admin - Produtos Comprados por Usuário'
        ]);
    }

    /**
     * Orders management view
     */
    public function orders()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        // Query Builder: list all orders
        $orders = $this->db->table('orders')
                           ->select('orders.*, users.name as registered_name')
                           ->join('users', 'users.id = orders.user_id', 'left')
                           ->orderBy('orders.id', 'DESC')
                           ->get()
                           ->getResultArray();

        // Attach items to each order
        foreach ($orders as &$ord) {
            $ord['items'] = $this->db->table('order_items')
                                     ->where('order_id', $ord['id'])
                                     ->get()
                                     ->getResultArray();
        }

        return view('admin/orders', [
            'orders'    => $orders,
            'pageTitle' => 'GuitAIr Admin - Gerenciamento de Pedidos'
        ]);
    }

    /**
     * Update order status via Query Builder
     */
    public function updateOrderStatus()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $orderId = (int)$this->request->getPost('order_id');
        $status = $this->request->getPost('status');

        $validStatuses = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'Status inválido.');
        }

        // Query Builder: update order status
        $this->db->table('orders')
                 ->where('id', $orderId)
                 ->update([
                     'status'     => $status,
                     'updated_at' => date('Y-m-d H:i:s')
                 ]);

        return redirect()->back()->with('success', 'Status do Pedido #' . $orderId . ' atualizado para ' . strtoupper($status) . '!');
    }
}
