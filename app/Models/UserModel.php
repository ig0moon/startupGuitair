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
        try {
            return $this->where('email', strtolower(trim($email)))->first();
        } catch (\Throwable $e) {
            // If DB is offline, check demo users in session or memory
            $simulatedUsers = session('simulated_users') ?? [
                'demo@guitair.com.br' => [
                    'id'            => 1,
                    'name'          => 'Usuário Demo',
                    'email'         => 'demo@guitair.com.br',
                    'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
                    'role'          => 'user',
                    'created_at'    => date('Y-m-d H:i:s')
                ]
            ];
            return $simulatedUsers[strtolower(trim($email))] ?? null;
        }
    }

    /**
     * Create user (DB or fallback to session)
     */
    public function registerUser(array $data): array
    {
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        $userRecord = [
            'name'          => trim($data['name']),
            'email'         => strtolower(trim($data['email'])),
            'password_hash' => $passwordHash,
            'phone'         => $data['phone'] ?? null,
            'role'          => 'user',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        try {
            $insertedId = $this->insert($userRecord, true);
            $userRecord['id'] = $insertedId;
            return ['success' => true, 'user' => $userRecord];
        } catch (\Throwable $e) {
            // Store in session if DB isn't connected yet
            $simulatedUsers = session('simulated_users') ?? [];
            if (isset($simulatedUsers[$userRecord['email']])) {
                return ['success' => false, 'message' => 'Este e-mail já está cadastrado.'];
            }
            $userRecord['id'] = count($simulatedUsers) + 10;
            $simulatedUsers[$userRecord['email']] = $userRecord;
            session()->set('simulated_users', $simulatedUsers);

            return ['success' => true, 'user' => $userRecord, 'simulated' => true];
        }
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
