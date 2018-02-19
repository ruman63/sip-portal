<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Parser;
use App\Company;
use App\Scheme;
use App\SchemeCategory;
class NavController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        $categories = SchemeCategory::all();
        $builder = Scheme::query();
        if(request('company') != '') {
            $builder->where('company_id', request('company'));
        }
        if(request('category') != '') {
            $builder->where('scheme_category_id', request('category'));
        }
        $schemes = $builder->with(['company', 'category'])->get();

        return view('net-values', compact('schemes', 'companies', 'categories'));
    }
}
