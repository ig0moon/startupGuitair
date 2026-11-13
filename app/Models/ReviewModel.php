<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Avaliações de produtos (RN14): nota de 1 a 5, comentário opcional, uma
 * avaliação por usuário em cada produto (UNIQUE uq_reviews_user_product).
 */
class ReviewModel extends Model
{
    protected $table            = 'reviews';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['product_id', 'user_id', 'rating', 'comment'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /** Avaliações de um produto, da mais recente para a mais antiga, com o nome do autor. */
    public function forProduct(int $productId): array
    {
        try {
            return $this->select('reviews.*, users.name AS author')
                        ->join('users', 'users.id = reviews.user_id')
                        ->where('reviews.product_id', $productId)
                        ->orderBy('reviews.created_at', 'DESC')
                        ->findAll();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /** Avaliação que o usuário já fez deste produto, ou null. */
    public function findByUser(int $userId, int $productId): ?array
    {
        try {
            return $this->where('user_id', $userId)->where('product_id', $productId)->first();
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Grava a avaliação: cria se o usuário ainda não avaliou o produto e
     * atualiza se já avaliou (o banco só aceita uma por usuário e produto).
     */
    public function saveReview(int $userId, int $productId, int $rating, ?string $comment): array
    {
        if ($rating < 1 || $rating > 5) {
            return ['success' => false, 'message' => 'A nota deve ser de 1 a 5.'];
        }

        $comment = $comment !== null ? trim($comment) : null;
        if ($comment === '') {
            $comment = null;
        }
        if ($comment !== null && mb_strlen($comment) > 500) {
            return ['success' => false, 'message' => 'O comentário pode ter no máximo 500 caracteres.'];
        }

        try {
            $existing = $this->findByUser($userId, $productId);
            $data = ['rating' => $rating, 'comment' => $comment];

            if ($existing) {
                $this->update($existing['id'], $data);
                return ['success' => true, 'message' => 'Sua avaliação foi atualizada.'];
            }

            $this->insert($data + ['user_id' => $userId, 'product_id' => $productId]);
            return ['success' => true, 'message' => 'Obrigado! Sua avaliação foi publicada.'];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Não foi possível salvar sua avaliação agora.'];
        }
    }
}
