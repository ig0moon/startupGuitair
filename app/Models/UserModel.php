<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'email', 'password_hash', 'phone', 'role'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', strtolower(trim($email)))->first();
    }

    /**
     * Cria o usuário (RN01: e-mail único e com formato válido).
     * Se o banco recusar o dado, o erro é devolvido; nada é simulado na sessão.
     */
    public function registerUser(array $data): array
    {
        $email = strtolower(trim($data['email']));

        if ($this->findByEmail($email)) {
            return ['success' => false, 'message' => 'Este e-mail já está cadastrado.'];
        }

        $userRecord = [
            'name'          => trim($data['name']),
            'email'         => $email,
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'phone'         => !empty($data['phone']) ? $data['phone'] : null,
            'role'          => 'user',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        try {
            $insertedId = $this->insert($userRecord, true);
        } catch (\Throwable $e) {
            log_message('error', 'Falha ao cadastrar usuário: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Não foi possível cadastrar. Confira os dados e tente novamente.'];
        }

        if (!$insertedId) {
            return ['success' => false, 'message' => 'Não foi possível cadastrar. Confira os dados e tente novamente.'];
        }

        $userRecord['id'] = $insertedId;
        unset($userRecord['password_hash']);
        return ['success' => true, 'user' => $userRecord];
    }

    /**
     * Authenticate user credentials
     */
    public function authenticate(string $email, string $password): array
    {
        $user = $this->findByEmail($email);
        if (!$user) {
            return ['success' => false, 'message' => 'E-mail ou usuário não encontrado.'];
        }

        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Senha incorreta. Tente novamente.'];
        }

        unset($user['password_hash']);
        return ['success' => true, 'user' => $user];
    }
}
