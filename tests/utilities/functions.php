<?php

use Illuminate\Http\UploadedFile;

function create($class, $attributes = [], $times = null)
{
    return factory($class, $times)->create($attributes);
}

function make($class, $attributes = [], $times = null)
{
    return factory($class, $times)->make($attributes);
}

function stubFile($stubFilePath, $uploadedFileName, $mime = null)
{
    $pathToFile = sys_get_temp_dir() . '/' . $uploadedFileName;
    copy($stubFilePath, $pathToFile);
    return new UploadedFile($pathToFile, $uploadedFileName, $mime);
}

function stubs_path($path = '')
{
    return base_path('tests/stubs/' . $path);
}
