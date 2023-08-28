<?php

namespace Domain\OrderProduct\QueryBuilders;

use Domain\Shared\DTOs\ReportDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @template TModelClass of \Domain\OrderProduct\Models\OrderProduct
 * @extends Builder<TModelClass>
 */
class OrderProductBuilder extends Builder
{
    /**
     * @return \Domain\OrderProduct\QueryBuilders\OrderProductBuilder<\Domain\OrderProduct\Models\OrderProduct>
     */
    public function getBestSellingProduct(ReportDTO $dto): self
    {
        return $this->select([
            DB::raw('products.code AS code'),
            DB::raw('products.name AS name'),
            DB::raw('COUNT(*) AS orders_completed'),
            DB::raw('SUM(quantity) AS units_purchased'),
        ])
            ->join('products', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->where('orders.state', 'Domain\\Order\\States\\Completed')
            ->groupBy('order_product.product_id')
            ->when($dto->records, function ($query) use ($dto) {
                $query->take((int) $dto->records);
            }, function ($query) {
                $query->take(10);
            });
    }

    /**
     * @return \Domain\OrderProduct\QueryBuilders\OrderProductBuilder<\Domain\OrderProduct\Models\OrderProduct>
     */
    public function getBestSellingCategory(ReportDTO $dto): self
    {
        return $this->select([
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
            ->when($dto->records, function ($query) use ($dto) {
                $query->take((int) $dto->records);
            }, function ($query) {
                $query->take(10);
            });
    }
}
