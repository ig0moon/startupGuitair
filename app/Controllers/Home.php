<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Home extends BaseController
{
    public function index(): string
    {
        $productModel = new ProductModel();
        $products = $productModel->getActiveProducts();

        return view('home', [
            'products' => $products,
            'pageTitle' => 'GuitAIr - Instrumentos Inteligentes com Inteligência Artificial'
        ]);
    }
}
