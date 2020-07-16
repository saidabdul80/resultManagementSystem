<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExaminerViewResultController extends Controller
{
    //
     //
    public function __construct(){
    	$this->middleware('auth');
    }

    public function show()
    {
    	return view('/examiner/e_view_result');
    }

    public function withdata($p= null,Request $request)
    {
    	return view('/examiner/e_view_result',['p'=>$p]);	
    }
}
