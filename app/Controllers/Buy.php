<?php

namespace App\Controllers;

class Buy extends BaseController
{
    public function index(): string
    {
        $modelo = $this->request->getPost('modelo') ?? $this->request->getGet('modelo') ?? '';
        return view('buy', [
            'modelo' => $modelo
        ]);
    }
}
