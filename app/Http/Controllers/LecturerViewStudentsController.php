<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LecturerViewStudentsController extends Controller
{
     public function __construct(){
    	$this->middleware('auth');
    }
    
     public function show()
    {
    	return view('lecturer/l_view_student');
    	
    }
}
