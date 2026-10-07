<?php

namespace App\Controllers;

class Help extends BaseController
{
    public function index(): string
    {
        return view('help');
    }
}
