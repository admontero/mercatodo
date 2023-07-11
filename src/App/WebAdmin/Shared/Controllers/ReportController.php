<?php

namespace App\WebAdmin\Shared\Controllers;

use App\Controller;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $this->authorize('access-report-views');

        return view('backoffice.reports.index');
    }
}
