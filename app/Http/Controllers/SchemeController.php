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

        if(request()->has('type')) {
            $schemes->where('scheme_type', 'LIKE', request('type'));
        }

        if(request()->has('agent')) {
            $schemes->where('rta_agent_code', 'LIKE', request('agent'));
        }
        
        return $schemes->paginate( request('perPage') ?? 50 );
    }

    public function types()
    {
        return Scheme::selectRaw('DISTINCT scheme_type')->get()->pluck('scheme_type');
    }

    public function agents()
    {
        return Scheme::selectRaw('DISTINCT rta_agent_code')->get()->pluck('rta_agent_code');
    }
}
