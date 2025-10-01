<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class LandingController extends Controller
{
    public function index()
    {
        $packages = Package::orderBy('price', 'asc')->get();
        
        return view('landing', compact('packages'));
    }
}