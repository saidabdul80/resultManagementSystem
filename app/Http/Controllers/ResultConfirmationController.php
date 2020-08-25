<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class ResultConfirmationController extends Controller
{
    //
    public function confirm(Request $request)
    {
      $date = date('Y-m-d_h');
        //logs activity
        $token_raw = $request->input('token_raw');
       
        $injson = [
            "attributes"=>[
            $token_raw. " Uploaded result file was confirmed",
            "created_by" => Auth::user()->id,
            "created_on"=> $date
            ]
        ];
        //end logs activity
        $id = \App\Result_file::where('result_token', $token_raw)->first()->id;
        $update = \App\Result_file::find($id);
        $update->result_confirm_by_lect = 1;
        $update->save();

        \DB::table('activity_log')->insert(['id'=>null,'log_name'=>'default','description'=>'Result Confirmed','subject_id'=>Auth::user()->id, 'subject_type' => $token_raw, 'causer_id' => Auth::user()->id, 'causer_type' => 'App\Result_file','properties' => json_encode($injson), 'created_at' => $date, 'updated_at' => $date ]);

        //return response()->json(['success'=>200]);
        return response()->json(['success'=>200]);  
    }

      public function delete(Request $request)
    {
        $date = date('Y-m-d_h');
    	$token_raw = $request->input('token_raw');
    	$id = \App\Result_file::where('result_token', $token_raw)->delete();
    	$id = \App\Result::where('result_token', $token_raw)->delete();
    	//return response()->json(['success'=>200]);

          $injson = [
            "attributes"=>[
            $token_raw. " Result file was deleted",
            "created_by" => Auth::user()->id,
            "created_on"=> $date
            ]
        ];
        //end logs activity
         \DB::table('activity_log')->insert(['id'=>null,'log_name'=>'default','description'=>'Result Delelted','subject_id'=>Auth::user()->id, 'subject_type' => $token_raw, 'causer_id' => Auth::user()->id, 'causer_type' => 'App\Result_file','properties' => json_encode($injson), 'created_at' => $date, 'updated_at' => $date ]);
    	return response()->json(['success'=>200]);	
    }
}
