<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class elogsController extends Controller
{
    //
     public function __construct(){
    	$this->middleware('auth');
    }

    public function show()
    {
    	return view('/examiner/e_logs');
    }
}
