<?php

namespace App\Http\Controllers\Admin;

use App\Parsers\BSEParser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SchemeController extends Controller
{
    public function index()
    {
        return view('admin.schemes.index');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->file('schemesFile')->storeAs('', 'schemes.txt', 'local');

        (new BSEParser())->parse()->save();

        return response()->json([], 201);
    }

}
