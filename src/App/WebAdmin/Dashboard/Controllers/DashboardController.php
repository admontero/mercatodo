<?php

namespace App\WebAdmin\Dashboard\Controllers;

use App\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $this->authorize('access-admin-dashboard');

        return view('backoffice.dashboard');
    }
}
