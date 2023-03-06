<?php

namespace App\Controllers;

use App\View;

class IndexController extends Controller
{
    public function index()
    {
        return (new View())->render('index');
    }
}
