<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OrderModel;

class Account extends BaseController
{
    /**
     * Account Index: Dashboard if logged in, or Auth Form if guest
     */
    public function index(): string
    {
        $user = $this->session->get('user');

        if ($user) {
            $orderModel = new OrderModel();
            $orders = $orderModel->getUserOrders($user['id'] ?? null, $user['email'] ?? null);

            return view('account_dashboard', [
                'user'      => $user,
                'orders'    => $orders,
                'pageTitle' => 'GuitAIr - Painel do Usuário'
            ]);
        }

        return view('account', [
            'pageTitle' => 'GuitAIr - Entrar ou Criar Conta'
        ]);
    }

    /**
     * Process Login
     */
    public function login()
    {
        $email    = $this->request->getPost('email') ?? $this->request->getPost('usuario') ?? '';
        $password = $this->request->getPost('senha') ?? $this->request->getPost('password') ?? '';

        if (empty($email) || empty($password)) {
            $this->session->setFlashdata('error', 'Por favor, preencha o e-mail e a senha.');
            return redirect()->to(base_url('conta'));
        }

        $userModel = new UserModel();
        $auth = $userModel->authenticate($email, $password);

        if (!$auth['success']) {
            $this->session->setFlashdata('error', $auth['message']);
            return redirect()->to(base_url('conta'));
        }

        $this->session->set('user', $auth['user']);
        $this->session->setFlashdata('success', 'Bem-vindo de volta, ' . esc($auth['user']['name']) . '!');

        return redirect()->to(base_url('conta'));
    }

    /**
     * Process Registration
     */
    public function register()
    {
        $name     = trim($this->request->getPost('nome') ?? $this->request->getPost('name') ?? '');
        $email    = trim($this->request->getPost('email') ?? '');
        $phone    = trim($this->request->getPost('telefone') ?? $this->request->getPost('phone') ?? '');
        $password = $this->request->getPost('senha') ?? $this->request->getPost('password') ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            $this->session->setFlashdata('error', 'Preencha todos os campos obrigatórios para se cadastrar.');
            return redirect()->to(base_url('conta'));
        }

        if (strlen($password) < 6) {
            $this->session->setFlashdata('error', 'A senha deve possuir no mínimo 6 caracteres.');
            return redirect()->to(base_url('conta'));
        }

        $userModel = new UserModel();
        $result = $userModel->registerUser([
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'password' => $password,
        ]);

        if (!$result['success']) {
            $this->session->setFlashdata('error', $result['message'] ?? 'Erro ao cadastrar usuário.');
            return redirect()->to(base_url('conta'));
        }

        $this->session->set('user', $result['user']);
        $this->session->setFlashdata('success', 'Conta criada com sucesso! Seja bem-vindo à GuitAIr.');

        return redirect()->to(base_url('conta'));
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->session->remove('user');
        $this->session->setFlashdata('success', 'Você encerrou sua sessão com segurança.');

        return redirect()->to(base_url('conta'));
    }
}
