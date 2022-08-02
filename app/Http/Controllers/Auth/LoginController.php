<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth; //class import

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

/*    use AuthenticatesUsers;
*/

    public function showLoginForm()
    {
        return view('auth/login');
    }

    public function login(Request $request)
    {
      //validate the fields....
      $this->validate(request(), [
        'email' => 'required|max:120|min:10',
        'password' => 'required|max:11',
      ]);


      $credentials = [ 'email' => $request->email , 'password' => $request->password ];
      
      if(Auth::attempt($credentials,$request->remember)){ 
       
        return redirect()->action(
            'UserController@RoleCheck', ['role_ids'=>Auth::user()->role_id,'user_id'=>Auth::user()->id]
        );
      }
      else{
      //login failed...
        return back()->withInput();

      }
      
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
