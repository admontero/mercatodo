<?php

namespace Domain\Order\QueryBuilders;

use Domain\Shared\DTOs\ReportDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @template TModelClass of \Domain\Order\Models\Order
 * @extends Builder<TModelClass>
 */
class OrderBuilder extends Builder
{
    /**
     * @return \Domain\Order\QueryBuilders\OrderBuilder<\Domain\Order\Models\Order>
     */
    public function getBestBuyer(ReportDTO $dto): self
    {
        return $this->select([
            DB::raw('users.email AS email'),
            DB::raw('COUNT(*) AS orders_completed'),
            DB::raw('SUM(orders.total) AS total'),
        ])
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->where('orders.state', 'Domain\\Order\\States\\Completed')
            ->groupBy('orders.user_id')
            ->when($dto->records, function ($query) use ($dto) {
                $query->take((int) $dto->records);
            }, function ($query) {
                $query->take(10);
            });
    }

    /**
     * @return \Domain\Order\QueryBuilders\OrderBuilder<\Domain\Order\Models\Order>
     */
    public function getCompletedOrdersAndUsersByState(): self
    {
        return $this->select([
            DB::raw('states.name'),
            DB::raw('COUNT(*) AS orders_completed'),
            DB::raw('(SELECT COUNT(*) FROM customer_profiles WHERE state_id = states.id) AS users_num'),
        ])
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('customer_profiles', 'customer_profiles.id', '=', 'users.profileable_id')
            ->join('states', 'states.id', '=', 'customer_profiles.state_id')
            ->where('orders.state', 'Domain\\Order\\States\\Completed')
            ->groupBy('customer_profiles.state_id');
    }

    /**
     * @return \Domain\Order\QueryBuilders\OrderBuilder<\Domain\Order\Models\Order>
     */
    public function getCompletedOrdersByMonth(): self
    {
        return $this->select([
            DB::raw('COUNT(id) AS orders_completed'),
            DB::raw(env('DB_CONNECTION') === 'sqlite' ? 'strftime("%m", created_at) AS month' : 'MONTH(created_at) AS month'),
            DB::raw(env('DB_CONNECTION') === 'sqlite' ? 'strftime("%Y", created_at) AS year' : 'YEAR(created_at) AS year'),
            DB::raw('SUM(total) AS total'),
        ])
            ->where('orders.state', 'Domain\\Order\\States\\Completed')
            ->groupBy(['year', 'month']);
    }
}
