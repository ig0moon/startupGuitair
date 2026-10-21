<?php

namespace App\Controllers;

class About extends BaseController
{
    public function index(): string
    {
        return view('about', [
            'pageTitle' => 'GuitAIr - Nossa História, Missão e Tecnologia com IA'
        ]);
    }
}
