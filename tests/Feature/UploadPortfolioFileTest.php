<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Jobs\GenerateCamsPortfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Queue;

class UploadPortfolioFileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_cams_csv_files_are_accepted()
    {
        $this->signInAdmin();

        $response = $this->withExceptionHandling()->postJson(route('admin.generate-portfolios.store'), [
            'csvFile' => stubFile(stubs_path('test.csv'), 'camsFile.csv'),
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors('csvFile');
    }

    /** @test */
    public function it_performs_portfolio_generation_when_a_correct_csv_file_is_uploaded()
    {
        $this->signInAdmin();

        Queue::fake();

        $this->post(route('admin.generate-portfolios.store'), [
            'csvFile' => stubFile(stubs_path('sample_cams.csv'), 'camsFile.csv'),
        ])->assertStatus(201);

        Queue::assertPushed(GenerateCamsPortfolio::class, function ($job) {
            $this->assertInstanceOf(Collection::class, $job->data);
            return true;
        });
    }
}
