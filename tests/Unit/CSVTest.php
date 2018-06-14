<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Parsers\CSV;
use Illuminate\Support\Collection;

class CSVTest extends TestCase
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

    /** @test */
    public function it_writes_a_given_collection_to_a_csv_file()
    {
        $collection = collect([
            ['id' => '1', 'name' => 'Ruman Saleem', 'class' => 'PG', 'age' => 22],
            ['id' => '2', 'name' => 'Tarun Kanwal', 'class' => 'UG', 'age' => 21],
            ['id' => '3', 'name' => 'Arun Sharma', 'class' => 'PG', 'age' => 23],
            ['id' => '4', 'name' => 'Siddharth Bajpayee', 'class' => 'UG', 'age' => 20],
            ['id' => '5', 'name' => 'Vivek Oberoi', 'class' => 'PG', 'age' => 22],
            ['id' => '6', 'name' => 'Taimoor Khan', 'class' => 'UG', 'age' => 21],
        ]);
        if (!is_dir(storage_path('app/test'))) {
            mkdir(storage_path('app/test'));
        }
        $filepath = storage_path('app/test/file.csv');
        $this->assertTrue(CSV::write($filepath, $collection));
        $this->assertFileExists($filepath);
        $collection->assertEquals(CSV::read($filepath)->get());

        exec('rm -rf ' . storage_path('app/test'));
    }
}
