<?php

namespace App\ApiAdmin\User\Controllers;

use App\Controller;
use Domain\User\Models\User;
use Domain\User\States\Activated;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerActivationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, User $user): JsonResponse
    {
        $this->authorize('update-status', $user);

        $user->state->transitionTo(Activated::class);

        return response()->json(['state' => (string) $user->state]);
    }
}
