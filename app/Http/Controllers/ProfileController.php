<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        if (! Gate::allows('update-profile')) {
            abort(403);
        }

        return view('profile.edit');
    }
}
