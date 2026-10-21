<?php

namespace App\Controllers;

use App\Models\ProductModel;

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

    public function show(string $slug): string
    {
        $productModel = new ProductModel();
        $product = $productModel->findProduct($slug);

        if (!$product) {
            return redirect()->to(base_url('produtos'));
        }

        return view('products', [
            'products'        => [$product],
            'selectedProduct' => $product,
            'currentCategory' => $product['category'] ?? 'todos',
            'pageTitle'       => 'GuitAIr - ' . esc($product['name'])
        ]);
    }
}
