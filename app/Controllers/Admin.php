<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\UserModel;

/**
 * Controller do painel administrativo.
 * Só lê a requisição, valida, chama os Models (DAO) e escolhe a View.
 * Nenhum SQL é escrito aqui.
 */
class Admin extends BaseController
{
    protected UserModel $users;
    protected ProductModel $products;
    protected CategoryModel $categories;
    protected OrderModel $orders;

    public function __construct()
    {
        $this->users      = new UserModel();
        $this->products   = new ProductModel();
        $this->categories = new CategoryModel();
        $this->orders     = new OrderModel();
    }

    /**
     * Confere no banco se o usuário logado é administrador.
     */
    protected function checkAdminAccess()
    {
        $sessionUser = session('user');
        if (!$sessionUser) {
            return redirect()->to(base_url('conta'))->with('error', 'Por favor, faça login para acessar o painel.');
        }

        if (!$this->users->isAdmin((int)$sessionUser['id'])) {
            return redirect()->to(base_url('conta'))->with('error', 'Acesso negado: sua conta não possui privilégios de administrador no banco de dados.');
        }

        return null;
    }

    /* =====================================================================
     * USUÁRIOS
     * ===================================================================== */

    public function index()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $users = $this->users->listForAdmin();
        $stats = $this->orders->adminStats();

        return view('admin/dashboard', [
            'totalUsers'   => count($users),
            'totalOrders'  => $stats['totalOrders'],
            'totalRevenue' => $stats['totalRevenue'],
            'users'        => $users,
            'pageTitle'    => 'GuitAIr - Painel Administrativo'
        ]);
    }

    public function changeRole()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $userId  = (int)$this->request->getPost('user_id');
        $newRole = (string)$this->request->getPost('role');

        if (!$this->users->changeRole($userId, $newRole)) {
            return redirect()->back()->with('error', 'Função inválida.');
        }

        // Se o admin mudou o próprio papel, atualiza a sessão
        $sessionUser = session('user');
        if ($sessionUser && $sessionUser['id'] == $userId) {
            $sessionUser['role'] = $newRole;
            session()->set('user', $sessionUser);
        }

        return redirect()->back()->with('success', 'Nível de acesso do usuário ID #' . $userId . ' alterado para "' . strtoupper($newRole) . '" com sucesso!');
    }

    /* =====================================================================
     * PEDIDOS / COMPRAS
     * ===================================================================== */

    public function userPurchases()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $search    = trim((string)($this->request->getGet('busca') ?? $this->request->getGet('q') ?? $this->request->getGet('usuario_id') ?? ''));
        $purchases = $this->orders->searchPurchases($search);

        // Agrupa por usuário para a apresentação
        $groupedByUser = [];
        foreach ($purchases as $row) {
            $uId    = $row['user_id'] ?? 'guest';
            $uName  = !empty($row['user_name']) ? $row['user_name'] : (!empty($row['customer_name']) ? $row['customer_name'] : 'Cliente Convidado');
            $uEmail = !empty($row['user_email']) ? $row['user_email'] : ($row['customer_email'] ?? 'Sem e-mail');

            if (!isset($groupedByUser[$uId])) {
                $groupedByUser[$uId] = [
                    'user_id'     => $uId,
                    'user_name'   => $uName,
                    'user_email'  => $uEmail,
                    'items'       => [],
                    'total_spent' => 0
                ];
            }
            $groupedByUser[$uId]['items'][] = $row;
            $groupedByUser[$uId]['total_spent'] += $row['subtotal'];
        }

        return view('admin/user_purchases', [
            'allUsers'      => $this->users->listForAdmin('name'),
            'groupedByUser' => $groupedByUser,
            'searchQuery'   => $search,
            'totalRecords'  => count($purchases),
            'pageTitle'     => 'GuitAIr Admin - Produtos Comprados por Usuário'
        ]);
    }

    public function orders()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        return view('admin/orders', [
            'orders'    => $this->orders->listWithItems(),
            'pageTitle' => 'GuitAIr Admin - Gerenciamento de Pedidos'
        ]);
    }

    public function updateOrderStatus()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $orderId = (int)$this->request->getPost('order_id');
        $status  = (string)$this->request->getPost('status');

        if (!$this->orders->updateStatus($orderId, $status)) {
            return redirect()->back()->with('error', 'Status inválido.');
        }

        return redirect()->back()->with('success', 'Status do Pedido #' . $orderId . ' alterado para "' . order_status_label($status) . '" com sucesso!');
    }

    /* =====================================================================
     * CATEGORIAS (tabela pai) — CRUD completo
     * ===================================================================== */

    /** READ */
    public function categories()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $categories = $this->categories->listWithProductCount();

        return view('admin/categories', [
            'categories'    => $categories,
            'totalProducts' => array_sum(array_column($categories, 'product_count')),
            'pageTitle'     => 'GuitAIr Admin - Gestão de Categorias'
        ]);
    }

    /** CREATE */
    public function saveCategory()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $result = $this->categories->createCategory(
            (string)$this->request->getPost('name'),
            (string)$this->request->getPost('slug'),
            (string)$this->request->getPost('description')
        );

        return redirect()->to(base_url('admin/categorias'))
                         ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /** UPDATE */
    public function updateCategory()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $result = $this->categories->updateCategory(
            (int)$this->request->getPost('category_id'),
            (string)$this->request->getPost('name'),
            (string)$this->request->getPost('slug'),
            (string)$this->request->getPost('description')
        );

        return redirect()->to(base_url('admin/categorias'))
                         ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /** DELETE */
    public function deleteCategory()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $result = $this->categories->deleteCategory((int)$this->request->getPost('category_id'));

        return redirect()->to(base_url('admin/categorias'))
                         ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /* =====================================================================
     * PRODUTOS (tabela filha) — CRUD completo
     * ===================================================================== */

    /** READ */
    public function products()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $products = $this->products->adminList();

        // Filtro opcional por categoria (?categoria=slug), vindo da tela de categorias
        $filter = trim((string)$this->request->getGet('categoria'));
        if ($filter !== '') {
            $products = array_values(array_filter($products, fn($p) => ($p['category'] ?? '') === $filter));
        }

        return view('admin/products', [
            'products'       => $products,
            'categories'     => $this->categories->listAll(),
            'categoryFilter' => $filter,
            'totalProducts'  => count($products),
            'totalStock'     => array_sum(array_column($products, 'stock')),
            'featuredCount'  => count(array_filter($products, fn($p) => !empty($p['featured']))),
            'pageTitle'      => 'GuitAIr Admin - Gestão de Produtos'
        ]);
    }

    /** CREATE */
    public function saveProduct()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $name = trim((string)$this->request->getPost('name'));
        if ($name === '') {
            return redirect()->back()->with('error', 'O nome do produto é obrigatório.');
        }

        $categoryId = $this->categories->idBySlug((string)$this->request->getPost('category'));
        if (!$categoryId) {
            return redirect()->back()->with('error', 'Categoria inválida.');
        }

        $tagline = trim((string)$this->request->getPost('tagline')) ?: 'Instrumento musical inteligente com inteligência artificial embarcada.';

        $price = parse_money($this->request->getPost('price'));
        if ($price <= 0) {
            return redirect()->back()->with('error', 'Informe um preço maior que zero.');
        }
        $oldPrice = parse_money($this->request->getPost('old_price'));

        $features = $this->linesFromPost('features') ?: [
            'Processamento neural DSP em tempo real',
            'Conexão sem fio de ultrabaixa latência',
            'Integração nativa com app GuitAIr'
        ];

        $specs = array_merge([
            'Conectividade' => 'Bluetooth 5.3 / USB-C Áudio',
            'Latência'      => '< 1.2ms',
            'Garantia'      => '12 Meses Oficial'
        ], $this->specsFromPost());

        $preset    = trim((string)$this->request->getPost('preset_image'));
        $imagePath = $this->uploadedImage() ?? ($preset !== '' ? $preset : 'img/guitarra.png');

        try {
            $this->products->createProduct([
                'slug'        => $this->products->uniqueSlug((string)$this->request->getPost('slug') ?: $name),
                'name'        => $name,
                'tagline'     => $tagline,
                'category_id' => $categoryId,
                'price'       => $price,
                'old_price'   => $oldPrice > 0 ? $oldPrice : null,
                'image'       => $imagePath,
                'badge'       => trim((string)$this->request->getPost('badge')) ?: null,
                'description' => trim((string)$this->request->getPost('description')) ?: $tagline,
                'features'    => json_encode($features, JSON_UNESCAPED_UNICODE),
                'specs'       => json_encode($specs, JSON_UNESCAPED_UNICODE),
                'stock'       => max(0, (int)$this->request->getPost('stock')),
                'featured'    => $this->request->getPost('featured') ? 1 : 0,
                'active'      => 1,
            ]);
            return redirect()->to(base_url('admin/produtos'))->with('success', 'Produto "' . esc($name) . '" cadastrado com sucesso no catálogo!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erro ao salvar no banco de dados: ' . $e->getMessage());
        }
    }

    /** UPDATE */
    public function updateProduct()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $productId = (int)$this->request->getPost('product_id');
        $existing  = $productId > 0 ? $this->products->findRaw($productId) : null;
        if (!$existing) {
            return redirect()->back()->with('error', 'Produto não encontrado para edição.');
        }

        $name = trim((string)$this->request->getPost('name'));
        if ($name === '') {
            return redirect()->back()->with('error', 'O nome do produto é obrigatório.');
        }

        $categorySlug = trim((string)$this->request->getPost('category'));
        $categoryId   = $categorySlug !== '' ? $this->categories->idBySlug($categorySlug) : (int)$existing['category_id'];
        if (!$categoryId) {
            return redirect()->back()->with('error', 'Categoria inválida.');
        }

        $tagline  = trim((string)$this->request->getPost('tagline')) ?: $existing['tagline'];
        $price    = parse_money($this->request->getPost('price'));
        $oldPrice = parse_money($this->request->getPost('old_price'));

        $features = $this->linesFromPost('features')
            ?: (json_decode((string)$existing['features'], true) ?: []);
        $specs = $this->specsFromPost()
            ?: (json_decode((string)$existing['specs'], true) ?: []);

        $imagePath = $existing['image'];
        $preset    = trim((string)$this->request->getPost('preset_image'));
        if ($preset !== '' && $preset !== 'keep_current') {
            $imagePath = $preset;
        }
        $imagePath = $this->uploadedImage() ?? $imagePath;

        try {
            $this->products->updateProduct($productId, [
                'slug'        => $this->products->uniqueSlug((string)$this->request->getPost('slug') ?: $name, $productId),
                'name'        => $name,
                'tagline'     => $tagline,
                'category_id' => $categoryId,
                'price'       => $price > 0 ? $price : (float)$existing['price'],
                'old_price'   => $oldPrice > 0 ? $oldPrice : null,
                'image'       => $imagePath,
                'badge'       => trim((string)$this->request->getPost('badge')) ?: null,
                'description' => trim((string)$this->request->getPost('description')) ?: $tagline,
                'features'    => json_encode($features, JSON_UNESCAPED_UNICODE),
                'specs'       => json_encode($specs, JSON_UNESCAPED_UNICODE),
                'stock'       => max(0, (int)$this->request->getPost('stock')),
                'featured'    => $this->request->getPost('featured') ? 1 : 0,
            ]);
            return redirect()->to(base_url('admin/produtos'))->with('success', 'Produto "' . esc($name) . '" atualizado com sucesso!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar no banco de dados: ' . $e->getMessage());
        }
    }

    /** DELETE */
    public function deleteProduct()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;

        $productId = (int)$this->request->getPost('product_id');
        $product   = $productId > 0 ? $this->products->findRaw($productId) : null;
        if (!$product) {
            return redirect()->back()->with('error', 'Produto não encontrado.');
        }

        try {
            $result = $this->products->removeProduct($productId);
            $msg = $result === 'deleted'
                ? 'Produto "' . esc($product['name']) . '" excluído do banco de dados!'
                : 'Produto "' . esc($product['name']) . '" já tinha vendas, então foi desativado (o histórico de pedidos foi mantido).';
            return redirect()->to(base_url('admin/produtos'))->with('success', $msg);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erro ao excluir produto: ' . $e->getMessage());
        }
    }

    /* ---------------- auxiliares de formulário ---------------- */

    /** Textarea com um item por linha -> array. */
    protected function linesFromPost(string $field): array
    {
        $raw = (string)$this->request->getPost($field);
        return array_values(array_filter(array_map('trim', explode("\n", $raw))));
    }

    /** Textarea "Chave: Valor" por linha -> array associativo. */
    protected function specsFromPost(): array
    {
        $specs = [];
        foreach ($this->linesFromPost('specs') as $line) {
            if (strpos($line, ':') !== false) {
                [$k, $v] = explode(':', $line, 2);
                $specs[trim($k)] = trim($v);
            }
        }
        return $specs;
    }

    /** Move a imagem enviada para public/img/uploads e devolve o caminho, ou null. */
    protected function uploadedImage(): ?string
    {
        $file = $this->request->getFile('image_file');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return null;
        }
        if (!in_array($file->getMimeType(), ['image/png', 'image/jpeg', 'image/webp', 'image/gif'], true)) {
            return null;
        }

        $uploadDir = FCPATH . 'img/uploads/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }
        $newName = $file->getRandomName();
        $file->move($uploadDir, $newName);
        return 'img/uploads/' . $newName;
    }
}
