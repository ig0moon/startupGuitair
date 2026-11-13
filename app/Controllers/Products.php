<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ReviewModel;

class Products extends BaseController
{
    public function index(): string
    {
        $productModel = new ProductModel();
        $category = $this->request->getGet('categoria') ?? 'todos';
        $products = $productModel->getActiveProducts($category);

        return view('products', [
            'products'        => $products,
            'currentCategory' => $category,
            'pageTitle'       => 'GuitAIr - Conheça Nossa Linha de Instrumentos Inteligentes'
        ]);
    }

    public function show(string $slug): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $productModel = new ProductModel();
        $product = $productModel->findProduct($slug);

        if (!$product) {
            return redirect()->to(base_url('produtos'));
        }

        $reviewModel = new ReviewModel();
        $user        = $this->session->get('user');

        return view('products', [
            'products'        => [$product],
            'selectedProduct' => $product,
            'reviews'         => $reviewModel->forProduct((int)$product['id']),
            'myReview'        => !empty($user['id']) ? $reviewModel->findByUser((int)$user['id'], (int)$product['id']) : null,
            'loggedUser'      => $user,
            'currentCategory' => $product['category'] ?? 'todos',
            'pageTitle'       => 'GuitAIr - ' . esc($product['name'])
        ]);
    }
}
