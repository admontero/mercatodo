<?php

namespace Tests\Unit\Commands;

use App\Console\Commands\ConsultSessionPlaceToPay;
use Database\Seeders\OrderSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\UserSeeder;
use Domain\Order\Models\Order;
use Domain\Order\States\Canceled;
use Domain\Order\States\Completed;
use Domain\Order\States\Pending;
use Illuminate\Console\Events\ScheduledTaskFinished;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ConsultSessionPlaceToPayTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function consult_session_place_to_pay_command_executes_each_minute(): void
    {
        $schedule = app()->make(Schedule::class);

        $events = collect($schedule->events())->filter(function (Event $event) {
            return stripos($event->command, 'app:consult-session-place-to-pay');
        });

        if ($events->count() == 0) {
            $this->fail('No events found');
        }

        $events->each(function (Event $event) {
            $this->assertEquals('* * * * *', $event->expression);
        });
    }

    /**
     * @test
     */
    public function consult_session_place_to_pay_command_is_succesful_when_order_is_completed(): void
    {
        Order::factory(3)->create(['state' => Pending::class]);

        $mockResponse = [
            'requestId' => 1,
            'status' => [
                'status' => 'APPROVED',
                'reason' => '00',
                'message' => 'La petición ha sido aprobada exitosamente',
                'date' => '2022-07-27T14:51:27-05:00'
            ],
        ];

        Http::fake([config('placetopay.url') . '/*' => Http::response($mockResponse)]);

        $this->artisan('app:consult-session-place-to-pay')
            ->assertSuccessful()
            ->assertExitCode(0);

        $this->assertEquals(Completed::class, Order::first()->state->getValue());
        $this->assertEquals(3, Order::where('state', Completed::class)->count());
    }

    /**
     * @test
     */
    public function consult_session_place_to_pay_command_is_succesful_when_order_is_canceled(): void
    {
        Order::factory(2)->create(['state' => Pending::class]);

        $mockResponse = [
            'requestId' => 1,
            'status' => [
                'status' => 'REJECTED',
                'reason' => 'XN',
                'message' => 'Se ha rechazado la petición',
                'date' => '2021-11-30T16:44:24-05:00'
            ],
        ];

        Http::fake([config('placetopay.url') . '/*' => Http::response($mockResponse)]);

        $this->artisan('app:consult-session-place-to-pay')
            ->assertSuccessful()
            ->assertExitCode(0);

        $this->assertEquals(Canceled::class, Order::first()->state->getValue());
        $this->assertEquals(2, Order::where('state', Canceled::class)->count());
    }

    /**
     * @test
     */
    public function consult_session_place_to_pay_command_is_succesful_when_order_still_pending(): void
    {
        Order::factory(5)->create(['state' => Pending::class]);

        $mockResponse = [
            'requestId' => 1,
            'status' => [
                'status' => 'PENDING',
                'reason' => 'PT',
                'message' => 'La petición se encuentra pendiente',
                'date' => '2021-11-30T15:45:57-05:00'
            ],
        ];

        Http::fake([config('placetopay.url') . '/*' => Http::response($mockResponse)]);

        $this->artisan('app:consult-session-place-to-pay')
            ->assertSuccessful()
            ->assertExitCode(0);

        $this->assertEquals(Pending::class, Order::first()->state->getValue());
        $this->assertEquals(5, Order::where('state', Pending::class)->count());
    }
}
