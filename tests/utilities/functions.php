<?php

use Illuminate\Http\Testing\File;

function create($class, $attributes = [], $times = null)
{
    return factory($class, $times)->create($attributes);
}

function make($class, $attributes = [], $times = null)
{
    return factory($class, $times)->make($attributes);
}

function stubFile($stubFilePath, $uploadedFileName)
{
    $file = tmpfile();
    copy($stubFilePath, stream_get_meta_data($file)['uri']);
    return new File($uploadedFileName, $file);
}

function stubs_path($path = '')
{
    return base_path('tests/stubs/' . $path);
}
