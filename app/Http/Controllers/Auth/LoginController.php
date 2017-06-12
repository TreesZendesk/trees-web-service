<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use JWTAuth;
use App\Employee;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      //  $this->middleware('guest')->except('logout');
    }

    public function login(Request $request) {
        $cell_no = $request->input('cell_no');
        $employee = Employee::where('cell_no', $cell_no)->first();

        if (!$employee) {
            return response()->json(['message' => 'no employee found', 'status' => 404], 404);
        }

        try {
            if (!$token = JWTAuth::fromUser($employee)) {
                return response()->json(['message' => 'unable to get the token', 'status' => 400], 400);
            }
        } catch (Exception $e) {
                return response()->json(['errors' => 'error creating a token'], 500);
        }

        return response()->json(['status' => 'success', 'token_type' => 'bearer' ,'access_token' => $token, "expires_in" => config('jwt.ttl') * 60, 'user' => $employee], 200);

    }

    public function logout(Request $request) {
      
      return ['status' => "success", 'message' => "You have succesfully logout"];
    }
}

