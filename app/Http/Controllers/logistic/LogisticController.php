<?php

namespace App\Http\Controllers\logistic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogisticController extends Controller
{
    public function index(Request $request)
    {
       return view('logistics.index');
    }
}
