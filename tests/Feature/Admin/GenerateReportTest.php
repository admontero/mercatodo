<?php

namespace Tests\Feature\Admin;

use App\ApiAdmin\Shared\Jobs\GenerateReportJob;
use Barryvdh\DomPDF\Facade\Pdf;
use Domain\Product\Mails\ProductExportMail;
use Domain\Product\Services\ProductService;
use Domain\Shared\Mails\ReportMail;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Passport;
use Mockery;
use Tests\TestCase;

class GenerateReportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_generate_best_selling_product_report(): void
    {
        Bus::fake();
        Mail::fake();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->getJson(route('api.admin.reports.best-selling-product', ['records' => 10]));

        Bus::assertDispatched(GenerateReportJob::class, function (GenerateReportJob $job) {
            $job->handle();

            return true;
        });

        Mail::assertSent(ReportMail::class, function ($mail) {
            $this->assertIsArray($mail->attachments());
            $this->assertInstanceOf(Attachment::class, $mail->attachments()[0]);
            $this->assertEquals('emails.report', $mail->content()->markdown);

            return true;
        });

        $response
            ->assertOk()
            ->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function it_sends_an_email_without_attachment_when_the_report_fail(): void
    {
        Bus::fake();
        Mail::fake();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        Pdf::shouldReceive('loadView')
            ->once()
            ->andThrow(new \Exception());

        $response = $this->getJson(route('api.admin.reports.best-selling-product'));

        Bus::assertDispatched(GenerateReportJob::class, function (GenerateReportJob $job) {
            $job->handle();

            return true;
        });

        Mail::assertSent(ReportMail::class, function ($mail) {
            $this->assertIsArray($mail->attachments());
            $this->assertEquals($mail->attachments(), []);

            return true;
        });

        $response
            ->assertOk()
            ->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function it_can_generate_best_buyer_report(): void
    {
        Bus::fake();
        Mail::fake();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->getJson(route('api.admin.reports.best-buyer', ['records' => 50]));

        Bus::assertDispatched(GenerateReportJob::class, function (GenerateReportJob $job) {
            $job->handle();

            return true;
        });

        Mail::assertSent(ReportMail::class, function ($mail) {
            $this->assertIsArray($mail->attachments());
            $this->assertInstanceOf(Attachment::class, $mail->attachments()[0]);
            $this->assertEquals('emails.report', $mail->content()->markdown);

            return true;
        });

        $response
            ->assertOk()
            ->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function it_can_generate_best_buyer_report_without_query_params(): void
    {
        Bus::fake();
        Mail::fake();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->getJson(route('api.admin.reports.best-buyer'));

        Bus::assertDispatched(GenerateReportJob::class, function (GenerateReportJob $job) {
            $job->handle();

            return true;
        });

        Mail::assertSent(ReportMail::class, function ($mail) {
            $this->assertIsArray($mail->attachments());
            $this->assertInstanceOf(Attachment::class, $mail->attachments()[0]);
            $this->assertEquals('emails.report', $mail->content()->markdown);

            return true;
        });

        $response
            ->assertOk()
            ->assertJsonStructure(['message']);
    }
}