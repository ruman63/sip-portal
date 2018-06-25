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
    public function rta_is_required()
    {
        $this->signInAdmin();

        $response = $this->withExceptionHandling()->postJson(route('admin.generate-portfolios.store'));

        $response->assertStatus(422)->assertJsonValidationErrors('rta');
    }

    /** @test */
    public function rta_should_be_in_cams_karvy_franklin_sundaram()
    {
        $this->signInAdmin();

        $response = $this->withExceptionHandling()->postJson(route('admin.generate-portfolios.store'), [
            'rta' => 'abcd',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors('rta');
    }

    /** @test */
    public function when_cams_csv_files_are_uploaded_it_should_be_valid()
    {
        $this->signInAdmin();

        $response = $this->withExceptionHandling()->postJson(route('admin.generate-portfolios.store'), [
            'rta' => 'cams',
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
            'rta' => 'cams',
            'csvFile' => stubFile(stubs_path('sample_cams.csv'), 'camsFile.csv'),
        ])->assertStatus(201);

        Queue::assertPushed(GenerateCamsPortfolio::class, function ($job) {
            $this->assertInstanceOf(Collection::class, $job->data);
            return true;
        });
    }
}
