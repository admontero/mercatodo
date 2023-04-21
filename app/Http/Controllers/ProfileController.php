<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProfileController extends Controller
{
    public function edit()
    {
        if (! Gate::allows('update-profile', )) {
            abort(403);
        }

        return view('profile.edit');
    }
}
