<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AutoController extends Controller
{
    public function login()
    {
    $YearSession = \DB::table('sessions')->where('c_set','=', '1')->get();
    //return $YearSession;
    	return view('auth/login', compact('YearSession'));
    }
    
    public function handleLogin(Request $request)
    {
    	$data = $request->only('email','password');
    	if(\Auth::attempt($data)){
    		//return 'is logged in';
    		
    		return redirect()->intended('/');
    	}else{
    		//return Redirect::to('/login')->withInput(Input::except('password'));
    		return redirect()->intended('/login?failed=1');

    	}

    }
}
