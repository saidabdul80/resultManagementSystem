<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadController extends Controller
{

	public function __construct(){
    	$this->middleware('auth');
    }

    public function download(Request $request)
    {
    	$type = $request->input('type');

    	if ($type ==1 ) {
    		$student_registered = $request->input('student_registered');
    		return view('lecturer\outFile\result_upload_template')->with('student_registered', $student_registered);
    	}
    }
}
