<?php

namespace App\ApiCustomer\Order\Controllers;

use App\ApiCustomer\Order\Resources\OrderCollection;
use App\Controller;
use Domain\Order\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): OrderCollection
    {
        //$this->authorize('view-any', new Product());

        $orders = new OrderCollection(
            Order::with(['user', 'products.category'])
                ->select(['id', 'code', 'total', 'provider', 'request_id', 'url', 'user_id', 'state', 'created_at'])
                ->where('user_id', $request->user()?->id)
                ->latest()
                ->paginate(10)
        );

        return $orders;
    }
}
