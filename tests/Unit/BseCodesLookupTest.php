<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\BseStar\CodesLookup;

class BseCodesLookupTest extends TestCase
{
    /** @test */
    public function it_looks_up_correct_tax_status()
    {
        $lookupTable = json_decode(\Storage::get('tax_status.json'), true);
        $codes = array_keys($lookupTable);
        $code = $codes[random_int(0, count($codes))];
        $status = CodesLookup::taxStatus($code);
        $this->assertEquals($lookupTable[$code], $status);
    }

    /** @test */
    public function it_gets_a_valid_random_tax_status_code()
    {
        $lookupTable = json_decode(\Storage::get('tax_status.json'), true);
        $code = CodesLookup::randomTaxStatusCode();
        $this->assertTrue(array_key_exists($code, $lookupTable));
    }

    /** @test */
    public function it_gets_a_valid_random_occupation_code()
    {
        $validCodes = ['01', '02', '03', '04', '05', '06', '07', '08'];

        $code = CodesLookup::randomOccupationCode();
        $this->assertContains($code, $validCodes);
    }

    /** @test */
    public function it_looks_up_occupation()
    {
        $validCodes = ['01', '02', '03', '04', '05', '06', '07', '08'];
        $code = $validCodes[random_int(0, 7)];
        $occupation = CodesLookup::occupation($code);
        $this->assertContains($code, $validCodes);
    }
}
