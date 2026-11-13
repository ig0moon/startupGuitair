<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ReviewModel;

class Reviews extends BaseController
{
    /** Recebe o formulário de avaliação da página do produto. */
    public function save()
    {
        $user = $this->session->get('user');
        if (empty($user['id'])) {
            $this->session->setFlashdata('error', 'Entre na sua conta para avaliar um produto.');
            return redirect()->to(base_url('conta'));
        }

        $productId = (int)$this->request->getPost('product_id');
        $product   = (new ProductModel())->findProduct($productId);
        if (!$product) {
            $this->session->setFlashdata('error', 'Produto não encontrado.');
            return redirect()->to(base_url('produtos'));
        }

        $result = (new ReviewModel())->saveReview(
            (int)$user['id'],
            $productId,
            (int)$this->request->getPost('rating'),
            $this->request->getPost('comment')
        );

        $this->session->setFlashdata($result['success'] ? 'success' : 'error', $result['message']);
        return redirect()->to(base_url('produtos/' . $product['slug']) . '#avaliacoes');
    }
}
