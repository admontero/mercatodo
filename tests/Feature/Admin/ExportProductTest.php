<?php

namespace Tests\Feature\Admin;

use App\ApiAdmin\Product\Jobs\ProductExportJob;
use Domain\Product\Mails\ProductExportMail;
use Domain\Product\Services\ProductService;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Passport;
use Mockery;
use Tests\TestCase;

class ExportProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_export_products(): void
    {
        Bus::fake();
        Mail::fake();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->getJson(route('api.admin.products.export'));

        Bus::assertDispatched(ProductExportJob::class, function (ProductExportJob $job) {
            $job->handle();

            return true;
        });

        Mail::assertSent(ProductExportMail::class, function ($mail) {
            $this->assertIsArray($mail->attachments());
            $this->assertInstanceOf(Attachment::class, $mail->attachments()[0]);
            $this->assertEquals('emails.products.export', $mail->content()->markdown);

            return true;
        });

        $response
            ->assertOk()
            ->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function it_sends_an_email_without_attachment_when_the_export_fail(): void
    {
        Bus::fake();
        Mail::fake();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $serviceMock = Mockery::mock(ProductService::class);
        $serviceMock->shouldReceive('createExcelFile')
                    ->once()
                    ->andThrow(new \Exception());

        $this->app->instance(ProductService::class, $serviceMock);

        $response = $this->getJson(route('api.admin.products.export'));

        Bus::assertDispatched(ProductExportJob::class, function (ProductExportJob $job) {
            $job->handle();

            return true;
        });

        Mail::assertSent(ProductExportMail::class, function ($mail) {
            $this->assertIsArray($mail->attachments());
            $this->assertEquals($mail->attachments(), []);

            return true;
        });

        $response
            ->assertOk()
            ->assertJsonStructure(['message']);
    }
}
