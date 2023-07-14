<?php

namespace Domain\OrderProduct\Services;

use Domain\OrderProduct\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderProductService
{
    /** @return array<string, mixed> */
    public function getBestSellingProduct(Request $request): array
    {
        return OrderProduct::select([
            DB::raw('products.code AS code'),
            DB::raw('products.name AS name'),
            DB::raw('COUNT(*) AS orders_completed'),
            DB::raw('SUM(quantity) AS units_purchased'),
        ])
            ->join('products', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->where('orders.state', 'Domain\\Order\\States\\Completed')
            ->groupBy('order_product.product_id')
            ->orderBy('orders_completed', 'DESC')
            ->orderBy('units_purchased', 'DESC')
            ->when($request->records, function ($q) use ($request) {
                $q->take($request->records);
            }, function ($q) {
                $q->take(10);
            })
            ->get()
            ->toArray();
    }

    /** @return array<string, mixed> */
    public function getBestSellingCategory(Request $request): array
    {
        return OrderProduct::select([
            DB::raw('categories.name AS name'),
            DB::raw('COUNT(order_product.id) AS sales'),
            DB::raw('SUM(order_product.quantity) AS products'),
            DB::raw('SUM(order_product.quantity * order_product.price) AS total'),
        ])
            ->join('products', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->where('orders.state', 'Domain\\Order\\States\\Completed')
            ->groupBy('categories.id')
            ->orderBy('sales', 'DESC')
            ->orderBy('products', 'DESC')
            ->orderBy('total', 'DESC')
            ->when($request->records, function ($q) use ($request) {
                $q->take($request->records);
            }, function ($q) {
                $q->take(10);
            })
            ->get()
            ->toArray();
    }
}
