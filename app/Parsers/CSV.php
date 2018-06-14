<?php

namespace App\Parsers;

class CSV
{
    private function __construct()
    {
    }

    public static function read($filePath, $seperator = ',')
    {
        return (new static)->parse(file($filePath), $seperator);
    }

    public static function write($filePath, $collection)
    {
        return !!file_put_contents($filePath, (new static)->implode($collection));
    }

    private function implode($collection)
    {
        $keys = implode(',', array_keys($collection->first()));
        return $collection->map(function ($arrayData) {
            return implode(
                ',',
                array_map(function ($col) {
                    return "'$col'";
                }, $arrayData)
            );
        })->prepend($keys)->implode("\r\n");
    }

    private function parse($lines, $seperator)
    {
        $csv = collect($lines)->map(function ($line) use ($seperator) {
            return array_map('trim', str_getcsv($line, $seperator, "'"));
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
