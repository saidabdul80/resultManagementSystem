<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExaminerManageLecturerController extends Controller
{
    //
     //
    public function __construct(){
    	$this->middleware('auth');
    }

    public function show()
    {
    	return view('/examiner/e_manage_lecturer');
    }
}
