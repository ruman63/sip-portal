<?php
namespace App\BseStar;

class CodesLookup
{
    public static function taxStatus($code)
    {
        $lookupTable = json_decode((\Storage::get('tax_status.json')), true);
        if (!array_key_exists((int)$code, $lookupTable)) {
            return false;
        }
        return $lookupTable[(int)$code];
    }

    public static function randomTaxStatusCode()
    {
        $lookupTable = json_decode((\Storage::get('tax_status.json')), true);
        $codes = array_keys($lookupTable);
        return $codes[random_int(0, count($codes))];
    }

    public static function randomOccupationCode()
    {
        return '0' . random_int(1, 8);
    }

    public static function occupation($code)
    {
        $lookupTable = json_decode(\Storage::get('occupations.json'), true);
        if (!array_key_exists((int)$code, $lookupTable)) {
            return false;
        }
        return $lookupTable[(int)$code];
    }
}
