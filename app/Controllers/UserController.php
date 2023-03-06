<?php

namespace App\Controllers;

use App\View;

class UserController extends Controller
{
    public function __construct(protected View $view)
    {
    }

    public function index()
    {
        return $this->view->render('user/index', ['id' => 1, 'name' => 'Test']);
    }

    public function create()
    {
        return $this->view->render('user/create');
    }
}
