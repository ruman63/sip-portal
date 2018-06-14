<?php

namespace App\Parsers;

class CSV
{
    private function __construct()
    {
    }

    public static function read($filePath)
    {
        return (new static)->parse(file($filePath));
    }

    private function parse($lines)
    {
        $csv = collect($lines)->map(function ($line) {
            return array_map('trim', str_getcsv($line, ',', "'"));
        });

        $keys = array_map('strtolower', $csv->shift());

        $this->rows = $csv->map(function ($record) use ($keys) {
            return array_combine($keys, $record);
        });

        return $this;
    }

    public function get()
    {
        return $this->rows;
    }

    public function columns($columns)
    {
        return $this->rows->map(function ($row) use ($columns) {
            return array_only($row, $columns);
        });
    }
}
