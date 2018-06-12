<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Scheme;
use App\Parsers\BSEParser;

class SchemeController extends Controller
{
    public function index()
    {
        $schemes = Scheme::query();
        
        if(request()->has('s')) {
            $schemes->where('scheme_name', 'LIKE', '%'.request('s').'%');
        }
        
        return $schemes->paginate(request('perPage') ?? 50);
    }
}
