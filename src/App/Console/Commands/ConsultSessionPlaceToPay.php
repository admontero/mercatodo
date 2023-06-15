<?php

namespace App\Console\Commands;

use Domain\Order\Models\Order;
use Domain\Order\Services\OrderService;
use Domain\Order\States\Pending;
use Domain\Order\Traits\OrderTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ConsultSessionPlaceToPay extends Command
{
    use OrderTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:consult-session-place-to-pay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It check payment status to update the order status.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $orders = Order::where('state', Pending::class)->get();

        foreach ($orders as $order) {
            $result = Http::post(config('placetopay.url') . '/api/session/' . $order->request_id, [
                'auth' => $this->getAuth(),
            ]);

            if ($result->ok()) {
                $status = $result->json()['status']['status'];

                match($status) {
                    'APPROVED' => (new OrderService())->updateToCompleted($order),
                    'REJECTED' => (new OrderService())->updateToCanceled($order),
                    default => null,
                };
            }

        }
    }
}
