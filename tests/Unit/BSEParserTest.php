<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use App\Scheme;
use Illuminate\Support\Carbon;

class BSEParserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->parser = new \App\Parsers\BSEParser();
        $this->parser->parse();
        $this->activeSchemesCountInSampleFile = 21;
        $this->totalSchemesCountInSampleFile = 25;
    }

    /** @test */
    public function it_decodes_file_to_collections_of_Only_active_schemes()
    {
        $this->assertInstanceOf(
            Collection::class,
            $this->parser->records()
        );
        $this->assertCount($this->totalSchemesCountInSampleFile, $this->parser->records());
    }

    /** @test */
    public function it_decodes_file_and_persists_to_database()
    {
        $activeRecords = $this->parser->records()->filter(function ($recrd) {
            return Carbon::parse($recrd['end_date'])->isFuture();
        });

        $this->assertEquals(0, Scheme::count());

        $this->parser->save(null, 2);

        $this->assertEquals($this->activeSchemesCountInSampleFile, Scheme::count());

        $this->assertDatabaseHas('schemes', $activeRecords->random());
        $this->assertDatabaseHas('schemes', $activeRecords->random());
        $this->assertDatabaseHas('schemes', $activeRecords->random());
        $this->assertDatabaseHas('schemes', $activeRecords->random());
    }

    /** @test */
    public function each_scheme_has_a_all_needed_fields()
    {
        $records = $this->parser->records();
        $this->parser->save();

        $schemes = Scheme::all()->random(5)->map->toArray()->map('collect');

        $schemes->each->assertHasKeyAndNotNull('unique_no');
        $schemes->each->assertHasKeyAndNotNull('scheme_code');
        $schemes->each->assertHasKeyAndNotNull('scheme_name');
        $schemes->each->assertHasKeyAndNotNull('scheme_type');
        $schemes->each->assertHasKeyAndNotNull('scheme_plan');
        $schemes->each->assertHasKeyAndNotNull('rta_agent_code');
        $schemes->each->assertHasKeyAndNotNull('channel_partner_code');
        $schemes->each->assertHasKeyAndNotNull('isin');
    }
}
