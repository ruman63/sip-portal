<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BSEParserTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_decodes_file_to_collections()
    {
        $this->assertArrayHasKey(
            'scheme_code', 
            (new \App\BSEParser())->parse()->records()->random()
        );
    }
    
    /** @test */
    public function it_decodes_file_and_persists_to_database()
    {
        $parser = new \App\BSEParser();
        $records = $parser->parse()->records(5);
        $parser->save(5);
        $this->assertDatabaseHas('schemes', $records->first());
    }
}
