<?php

namespace Tests\Unit\Http\Controllers\Admin;

use Domain\Category\Models\Category;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_returns_the_report_index_view(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->get(route('admin.reports.index'));

        $response
            ->assertSuccessful()
            ->assertViewIs('backoffice.reports.index');
    }
}
