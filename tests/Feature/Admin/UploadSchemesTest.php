<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Parsers\BSEParser;
use Illuminate\Filesystem\Filesystem;
use App\Scheme;

class UploadSchemesTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function uploading_scheme_files_adds_the_schemes()
    {
        $this->signInAdmin();
        
        $pathToFile = sys_get_temp_dir().'/schemes.txt';
        copy(base_path().'/tests/res/sample_schemes.txt', $pathToFile);
        
        Storage::fake('local');
        
        $this->postJson(route('admin.schemes.store'), [
            'schemesFile' => new UploadedFile($pathToFile, 'schemes.txt')
        ])->assertStatus(201);
        
        Storage::assertExists('schemes.txt');
        $this->assertEquals(21, Scheme::count());
    }
}
