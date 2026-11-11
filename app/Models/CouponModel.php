<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Cupons de desconto (tabela coupons, RN11).
 * Antes os cupons ficavam fixos dentro do Cart.php.
 */
class CouponModel extends Model
{
    protected $table            = 'coupons';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'code', 'label', 'discount_percent', 'free_shipping', 'valid_until', 'active'
    ];

    /**
     * Retorna o cupom se ele existe, está ativo e dentro da validade.
     */
    public function findValid(string $code): ?array
    {
        $code = strtoupper(trim($code));
        if ($code === '') {
            return null;
        }

        return $this->where('code', $code)
                    ->where('active', 1)
                    ->groupStart()
                        ->where('valid_until', null)
                        ->orWhere('valid_until >=', date('Y-m-d'))
                    ->groupEnd()
                    ->first();
    }
}
