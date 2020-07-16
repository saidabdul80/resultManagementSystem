<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResultConfirmationController extends Controller
{
    //
    public function confirm(Request $request)
    {
    	$token_raw = $request->input('token_raw');
    	$id = \App\Result_file::where('result_token', $token_raw)->first()->id;
    	$update = \App\Result_file::find($id);
    	$update->result_confirm_by_lect = 1;
    	$update->save();
    	//return response()->json(['success'=>200]);
    	return response()->json(['success'=>200]);	
    }

      public function delete(Request $request)
    {
    	$token_raw = $request->input('token_raw');
    	$id = \App\Result_file::where('result_token', $token_raw)->delete();
    	$id = \App\Result::where('result_token', $token_raw)->delete();
    	//return response()->json(['success'=>200]);
    	return response()->json(['success'=>200]);	
    }
}
