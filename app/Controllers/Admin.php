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

        $search = trim((string)($this->request->getGet('busca') ?? $this->request->getGet('q') ?? $this->request->getGet('usuario_id') ?? ''));

        // Query Builder: list all users for reference
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
            orders.customer_name,
            orders.customer_email,
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

        if (!empty($search)) {
            if (is_numeric($search)) {
                $builder->groupStart()
                        ->where('orders.user_id', (int)$search)
                        ->orWhere('orders.id', (int)$search)
                        ->groupEnd();
            } else {
                $builder->groupStart()
                        ->like('users.name', $search)
                        ->orLike('users.email', $search)
                        ->orLike('orders.customer_name', $search)
                        ->orLike('orders.customer_email', $search)
                        ->orLike('orders.order_number', $search)
                        ->orLike('order_items.product_name', $search)
                        ->groupEnd();
            }
        }

        $builder->orderBy('orders.id', 'DESC');
        $purchases = $builder->get()->getResultArray();

        // Group by user for structured presentation
        $groupedByUser = [];
        foreach ($purchases as $row) {
            $uId = $row['user_id'] ?? 'guest';
            $uName = !empty($row['user_name']) ? $row['user_name'] : (!empty($row['customer_name']) ? $row['customer_name'] : 'Cliente Convidado');
            $uEmail = !empty($row['user_email']) ? $row['user_email'] : ($row['customer_email'] ?? 'Sem e-mail');
            
            if (!isset($groupedByUser[$uId])) {
                $groupedByUser[$uId] = [
                    'user_id'    => $uId,
                    'user_name'  => $uName,
                    'user_email' => $uEmail,
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
            'searchQuery'    => $search,
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

        $statusName = function_exists('order_status_label') ? order_status_label($status) : strtoupper($status);
        return redirect()->back()->with('success', 'Status do Pedido #' . $orderId . ' alterado para "' . $statusName . '" com sucesso!');
    }

    /**
     * Admin Products Management: View all products & display addition form
     */
    public function products()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $productModel = new \App\Models\ProductModel();

        $products = [];
        try {
            $products = $this->db->table('products')->orderBy('id', 'DESC')->get()->getResultArray();
            foreach ($products as &$p) {
                if (is_string($p['features'] ?? null)) {
                    $p['features'] = json_decode($p['features'], true) ?: [];
                }
                if (is_string($p['specs'] ?? null)) {
                    $p['specs'] = json_decode($p['specs'], true) ?: [];
                }
            }
        } catch (\Throwable $e) {
            $products = $productModel->getActiveProducts();
        }

        $totalProducts = count($products);
        $totalStock = array_sum(array_column($products, 'stock'));
        $featuredCount = count(array_filter($products, fn($p) => !empty($p['featured'])));

        return view('admin/products', [
            'products'       => $products,
            'totalProducts'  => $totalProducts,
            'totalStock'     => $totalStock,
            'featuredCount'  => $featuredCount,
            'pageTitle'      => 'GuitAIr Admin - Gestão de Produtos'
        ]);
    }

    /**
     * Create / Add new product to MySQL database
     */
    public function saveProduct()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $name = trim((string)$this->request->getPost('name'));
        if (empty($name)) {
            return redirect()->back()->with('error', 'O nome do produto é obrigatório.');
        }

        $category = trim((string)$this->request->getPost('category')) ?: 'guitarra';
        $tagline = trim((string)$this->request->getPost('tagline')) ?: 'Instrumento musical inteligente com inteligência artificial embarcada.';
        
        // Clean price
        $rawPrice = (string)$this->request->getPost('price');
        $cleanPrice = str_replace(['R$', ' ', '.'], '', $rawPrice);
        $cleanPrice = str_replace(',', '.', $cleanPrice);
        $price = (float)$cleanPrice;
        if ($price <= 0) $price = 999.00;

        $rawOldPrice = (string)$this->request->getPost('old_price');
        $oldPrice = null;
        if (!empty($rawOldPrice)) {
            $cleanOld = str_replace(['R$', ' ', '.'], '', $rawOldPrice);
            $cleanOld = str_replace(',', '.', $cleanOld);
            $oldVal = (float)$cleanOld;
            if ($oldVal > 0) $oldPrice = $oldVal;
        }

        $stock = max(0, (int)$this->request->getPost('stock'));
        $badge = trim((string)$this->request->getPost('badge')) ?: null;
        $description = trim((string)$this->request->getPost('description')) ?: $tagline;
        $featured = $this->request->getPost('featured') ? 1 : 0;

        // Slug generation
        $slugInput = trim((string)$this->request->getPost('slug'));
        if (empty($slugInput)) {
            $slugInput = $name;
        }
        $slug = preg_replace('~[^\pL\d]+~u', '-', $slugInput);
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT//IGNORE', $slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        $slug = trim($slug, '-');
        $slug = strtolower($slug);
        if (empty($slug)) {
            $slug = 'produto-' . time();
        }

        // Check uniqueness in database
        try {
            $existing = $this->db->table('products')->where('slug', $slug)->countAllResults();
            if ($existing > 0) {
                $slug .= '-' . rand(100, 999);
            }
        } catch (\Throwable $e) {}

        // Features list
        $featuresRaw = (string)$this->request->getPost('features');
        $featuresList = array_values(array_filter(array_map('trim', explode("\n", $featuresRaw))));
        if (empty($featuresList)) {
            $featuresList = [
                'Processamento neural DSP em tempo real',
                'Conexão sem fio de ultrabaixa latência',
                'Integração nativa com app GuitAIr'
            ];
        }

        // Technical specs
        $specs = [
            'Categoria'    => ucfirst($category),
            'Conectividade'=> 'Bluetooth 5.3 / USB-C Áudio',
            'Latência'     => '< 1.2ms',
            'Garantia'     => '12 Meses Oficial'
        ];
        $customSpecs = trim((string)$this->request->getPost('specs'));
        if (!empty($customSpecs)) {
            foreach (explode("\n", $customSpecs) as $line) {
                if (strpos($line, ':') !== false) {
                    [$k, $v] = explode(':', $line, 2);
                    $specs[trim($k)] = trim($v);
                }
            }
        }

        // Image Handling
        $imagePath = 'img/guitarra.png';
        $preset = trim((string)$this->request->getPost('preset_image'));
        if (!empty($preset)) {
            $imagePath = $preset;
        }

        $imageFile = $this->request->getFile('image_file');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            $uploadDir = FCPATH . 'img/uploads/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            $imageFile->move($uploadDir, $newName);
            $imagePath = 'img/uploads/' . $newName;
        }

        $insertData = [
            'slug'          => $slug,
            'name'          => $name,
            'tagline'       => $tagline,
            'category'      => $category,
            'price'         => $price,
            'old_price'     => $oldPrice,
            'image'         => $imagePath,
            'badge'         => $badge,
            'description'   => $description,
            'features'      => json_encode($featuresList, JSON_UNESCAPED_UNICODE),
            'specs'         => json_encode($specs, JSON_UNESCAPED_UNICODE),
            'stock'         => $stock,
            'rating'        => 5.0,
            'reviews_count' => 0,
            'featured'      => $featured,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        try {
            $this->db->table('products')->insert($insertData);
            return redirect()->to(base_url('admin/produtos'))->with('success', 'Produto "' . esc($name) . '" cadastrado com sucesso no catálogo!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erro ao salvar no banco de dados: ' . $e->getMessage());
        }
    }

    /**
     * Update existing product in MySQL database
     */
    public function updateProduct()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $productId = (int)$this->request->getPost('product_id');
        if ($productId <= 0) {
            return redirect()->back()->with('error', 'ID do produto não informado ou inválido.');
        }

        $existingProduct = $this->db->table('products')->where('id', $productId)->get()->getRowArray();
        if (!$existingProduct) {
            return redirect()->back()->with('error', 'Produto não encontrado para edição.');
        }

        $name = trim((string)$this->request->getPost('name'));
        if (empty($name)) {
            return redirect()->back()->with('error', 'O nome do produto é obrigatório.');
        }

        $category = trim((string)$this->request->getPost('category')) ?: $existingProduct['category'];
        $tagline = trim((string)$this->request->getPost('tagline')) ?: $existingProduct['tagline'];

        // Clean price
        $rawPrice = (string)$this->request->getPost('price');
        $cleanPrice = str_replace(['R$', ' ', '.'], '', $rawPrice);
        $cleanPrice = str_replace(',', '.', $cleanPrice);
        $price = (float)$cleanPrice;
        if ($price <= 0) $price = (float)$existingProduct['price'];

        $rawOldPrice = (string)$this->request->getPost('old_price');
        $oldPrice = null;
        if (!empty($rawOldPrice)) {
            $cleanOld = str_replace(['R$', ' ', '.'], '', $rawOldPrice);
            $cleanOld = str_replace(',', '.', $cleanOld);
            $oldVal = (float)$cleanOld;
            if ($oldVal > 0) $oldPrice = $oldVal;
        }

        $stock = max(0, (int)$this->request->getPost('stock'));
        $badge = trim((string)$this->request->getPost('badge')) ?: null;
        $description = trim((string)$this->request->getPost('description')) ?: $tagline;
        $featured = $this->request->getPost('featured') ? 1 : 0;

        // Slug handling
        $slugInput = trim((string)$this->request->getPost('slug'));
        if (empty($slugInput)) {
            $slugInput = $name;
        }
        $slug = preg_replace('~[^\pL\d]+~u', '-', $slugInput);
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT//IGNORE', $slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        $slug = trim($slug, '-');
        $slug = strtolower($slug);
        if (empty($slug)) {
            $slug = $existingProduct['slug'];
        }

        // Uniqueness check excluding current product
        try {
            $slugConflict = $this->db->table('products')
                                     ->where('slug', $slug)
                                     ->where('id !=', $productId)
                                     ->countAllResults();
            if ($slugConflict > 0) {
                $slug .= '-' . rand(100, 999);
            }
        } catch (\Throwable $e) {}

        // Features list
        $featuresRaw = (string)$this->request->getPost('features');
        $featuresList = array_values(array_filter(array_map('trim', explode("\n", $featuresRaw))));
        if (empty($featuresList)) {
            $featuresList = is_string($existingProduct['features']) ? json_decode($existingProduct['features'], true) : [];
        }

        // Technical specs
        $specs = [];
        $customSpecs = trim((string)$this->request->getPost('specs'));
        if (!empty($customSpecs)) {
            foreach (explode("\n", $customSpecs) as $line) {
                if (strpos($line, ':') !== false) {
                    [$k, $v] = explode(':', $line, 2);
                    $specs[trim($k)] = trim($v);
                }
            }
        } else {
            $specs = is_string($existingProduct['specs']) ? json_decode($existingProduct['specs'], true) : [];
        }

        // Image Handling
        $imagePath = $existingProduct['image'];
        $preset = trim((string)$this->request->getPost('preset_image'));
        if (!empty($preset) && $preset !== 'keep_current') {
            $imagePath = $preset;
        }

        $imageFile = $this->request->getFile('image_file');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            $uploadDir = FCPATH . 'img/uploads/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            $imageFile->move($uploadDir, $newName);
            $imagePath = 'img/uploads/' . $newName;
        }

        $updateData = [
            'slug'        => $slug,
            'name'        => $name,
            'tagline'     => $tagline,
            'category'    => $category,
            'price'       => $price,
            'old_price'   => $oldPrice,
            'image'       => $imagePath,
            'badge'       => $badge,
            'description' => $description,
            'features'    => json_encode($featuresList, JSON_UNESCAPED_UNICODE),
            'specs'       => json_encode($specs, JSON_UNESCAPED_UNICODE),
            'stock'       => $stock,
            'featured'    => $featured,
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        try {
            $this->db->table('products')->where('id', $productId)->update($updateData);
            return redirect()->to(base_url('admin/produtos'))->with('success', 'Produto "' . esc($name) . '" atualizado com sucesso!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar no banco de dados: ' . $e->getMessage());
        }
    }

    /**
     * Delete product from database
     */
    public function deleteProduct()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $productId = (int)$this->request->getPost('product_id');
        if ($productId <= 0) {
            return redirect()->back()->with('error', 'ID de produto inválido.');
        }

        try {
            $product = $this->db->table('products')->where('id', $productId)->get()->getRowArray();
            if ($product) {
                $this->db->table('products')->where('id', $productId)->delete();
                return redirect()->to(base_url('admin/produtos'))->with('success', 'Produto "' . esc($product['name']) . '" removido com sucesso!');
            }
            return redirect()->back()->with('error', 'Produto não encontrado.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erro ao excluir produto: ' . $e->getMessage());
        }
    }
}
