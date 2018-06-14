<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Parsers\CSV;
use Illuminate\Support\Collection;

class CSVParserTest extends TestCase
{
    /** @test */
    public function it_reads_the_given_comma_seperated_file_to_a_collection()
    {
        $data = CSV::read(stubs_path('test.csv'))->get();
        $this->assertInstanceOf(Collection::class, $data);
        $this->assertCount(5, $data);
        $data->take(5)->each(function ($dataItem) {
            $this->assertCount(6, $dataItem);
        });
        $this->assertArrayHasKey('name', $data->last());
        $this->assertEquals('Dsouza', $data->last()['name']);
    }

    /** @test */
    public function it_reads_the_given_pipe_seperated_file_to_a_collection()
    {
        $data = CSV::read(stubs_path('piped.csv'), '|')->get();
        $this->assertInstanceOf(Collection::class, $data);
        $this->assertCount(5, $data);
        $data->take(5)->each(function ($dataItem) {
            $this->assertCount(6, $dataItem);
        });
        $this->assertArrayHasKey('name', $data->last());
        $this->assertEquals('Dsouza', $data->last()['name']);
    }

    /** @test */
    public function it_parses_down_the_given_file_path_to_an_array_with_provided_columns_only()
    {
        $select = ['roll_no', 'name', 'fees'];

        $data = CSV::read(stubs_path('test.csv'))->columns($select);
        $this->assertCount(count($select), $data->first());
        $this->assertArrayHasSameElements($select, array_keys($data->first()));
    }
}
