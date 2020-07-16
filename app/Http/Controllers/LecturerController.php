<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LecturerController extends Controller
{
    //
    public function __construct(){
    	$this->middleware('auth');
	}
    public function index()
    {
    	
    	//.return $YearSession;
		return view('lecturer/index', compact('YearSession'));
    }
}
