<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    public function login()
    {
	    $YearSession = \DB::table('sessions')->where('c_set','=', '1')->get();
	    return view('/login');
    	# code...
    }
}
