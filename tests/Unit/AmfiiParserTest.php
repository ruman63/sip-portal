<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AmfiiParserTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function parser_decodes_file_to_associative_array()
    {
        
        $this->assertArrayHasKey(
            'scheme_code', 
            (new \App\Parsers\AmfiiNavParser(null, $remote = false))->parse()->records()->random()
        );
    }

    /** @test */
    public function it_decodes_file_and_persists_to_database()
    {
        $BSEParser = new \App\Parsers\BSEParser();
        $BSEParser->parse()->save(10);
        $AmfiiParser = new \App\Parsers\AmfiiNavParser(null, $remote=false);
        $AmfiiParser->parse()->update();
        $this->assertTrue(\App\Scheme::whereNotNull('nav')->count() > 1);
    }
}
