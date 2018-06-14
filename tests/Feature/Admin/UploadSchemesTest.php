<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use App\Scheme;

class UploadSchemesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function uploading_scheme_files_adds_the_schemes()
    {
        $this->signInAdmin();

        $file = stubFile(
            base_path('tests/res/sample_schemes.txt'),
            'schemes.txt'
        );

        Storage::fake('local');
        $this->postJson(route('admin.schemes.store'), [
            'schemesFile' => $file,
        ])->assertStatus(201);

        Storage::assertExists('schemes.txt');
        $this->assertEquals(21, Scheme::count());
    }
}
