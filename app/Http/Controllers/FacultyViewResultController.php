<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FacultyViewResultController extends Controller
{
    //
      //
    public function __construct(){
    	$this->middleware('auth');
    }

    public function show()
    {
    	return view('/faculty/index');
    }
    
    public function viewResult()
    {
    	return view('/faculty/f_view_result');
    }
    
    public function withdata($p= null,Request $request)
    {
    	return view('/faculty/f_view_result',['p'=>$p]);	
    }

}
