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
            (new \App\Parsers\BSEParser())->parse()->records()->random()
        );
    }
    
    /** @test */
    public function it_decodes_file_and_persists_to_database()
    {
        $parser = new \App\Parsers\BSEParser();
        $records = $parser->parse()->records(2);
        $parser->save(null, 2);
        $this->assertDatabaseHas('schemes', $records->first());
    }
}
