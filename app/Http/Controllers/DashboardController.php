<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class DashboardController extends Controller
{
    public function dashboard(): Response|ResponseFactory
    {
        return inertia('Admin/Dashboard');
    }
}
