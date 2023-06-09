<?php

namespace App\Web\Profile\Controllers;

use App\Controller;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $this->authorize('access-profile-edit');

        return view('profile.edit');
    }
}
