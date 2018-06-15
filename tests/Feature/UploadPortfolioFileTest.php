<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Jobs\GeneratePortfolios;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Queue;

class UploadPortfolioFileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_performs_portfolio_generation_when_a_correct_csv_file_is_uploaded()
    {
        $this->signInAdmin();

        Queue::fake();
        // \Mockery::mock(GeneratePortfolios::class)->shouldReceive('handle')->once()->andReturn(null);

        $this->post(route('admin.generate-portfolios.store'), [
            'csvFile' => stubFile(stubs_path('txns.csv'), 'camsFile.csv'),
        ])->assertSuccessful();

        Queue::assertPushed(GeneratePortfolios::class, function ($job) {
            $this->assertInstanceOf(Collection::class, $job->data);
            return true;
        });
    }
}
