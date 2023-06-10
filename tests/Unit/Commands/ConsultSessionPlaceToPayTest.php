<?php

namespace Tests\Unit\Commands;

use Domain\Order\Models\Order;
use Domain\Order\States\Canceled;
use Domain\Order\States\Completed;
use Domain\Order\States\Pending;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ConsultSessionPlaceToPayTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function consult_session_place_to_pay_command_is_succesful_when_order_is_completed(): void
    {
        Order::factory(1)->create(['state' => Pending::class]);

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
    }

    /**
     * @test
     */
    public function consult_session_place_to_pay_command_is_succesful_when_order_is_canceled(): void
    {
        Order::factory(1)->create(['state' => Pending::class]);

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
    }

    /**
     * @test
     */
    public function consult_session_place_to_pay_command_is_succesful_when_order_still_pending(): void
    {
        Order::factory(1)->create(['state' => Pending::class]);

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
    }
}
