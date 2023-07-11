<?php

namespace Tests\Feature;

use App\ApiAdmin\Product\Jobs\ProductImportJob;
use Domain\Product\Mails\ProductImportMail;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Mockery;
use Tests\TestCase;

class ImportProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_import_products(): void
    {
        Bus::fake();
        Mail::fake();
        Storage::fake(config()->get('filesystem.default'));

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $header = 'name,code,description,price,stock,category';
        $row1 = 'Balón Golty 2023,12345678,,125999,20,Deportes';

        $content = implode("\n", [$header, $row1]);

        $file = UploadedFile::fake()->createWithContent('archivo.csv', $content);

        $response = $this->postJson(route('api.admin.products.import'), [
            'file' => $file,
        ]);

        Bus::assertDispatched(ProductImportJob::class, function (ProductImportJob $job) {
            $job->handle();

            return true;
        });

        Mail::assertSent(ProductImportMail::class, function ($mail) {
            $this->assertIsArray($mail->attachments());
            $this->assertEquals($mail->attachments(), []);
            $this->assertEquals('emails.products.import', $mail->content()->markdown);

            return true;
        });

        $response
            ->assertOk()
            ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('products', [
            'name' => 'Balón Golty 2023',
            'code' => '12345678',
            'price' => 125999,
            'stock' => 20,
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Deportes',
        ]);

        Storage::disk(config()->get('filesystem.default'))->assertExists('imports/products.csv');
    }

    /**
     * @test
     */
    public function it_sends_a_failed_email_when_the_import_fail(): void
    {
        Bus::fake();
        Mail::fake();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $header = 'name,code,description,price,stock,category';
        $row1 = 'Balón Golty 2023';

        $content = implode("\n", [$header, $row1]);

        $file = UploadedFile::fake()->createWithContent('archivo.csv', $content);

        $response = $this->postJson(route('api.admin.products.import'), [
            'file' => $file,
        ]);

        Bus::assertDispatched(ProductImportJob::class, function (ProductImportJob $job) {
            $job->handle();

            return true;
        });

        Mail::assertSent(ProductImportMail::class, function ($mail) {
            $this->assertEquals($mail->content, 'Products import failed, please try again later');
            $this->assertEquals($mail->subject, 'Products Import Failed');
            $this->assertIsArray($mail->attachments());
            $this->assertEquals($mail->attachments(), []);

            return true;
        });

        $response
            ->assertOk()
            ->assertJsonStructure(['message']);
    }
}
