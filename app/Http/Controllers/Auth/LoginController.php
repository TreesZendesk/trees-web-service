<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use JWTAuth;
use Validator;
use Carbon\Carbon;
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
        $validator = Validator::make($request->all(), [
            'cell_no' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'status_code' => 400, 'message' => $validator->errors()->first()], 400);
        }
        $cell_no = $request->input('cell_no');
        $employee = Employee::where('cell_no', $cell_no)->first();

        if (!$employee) {
            return response()->json(['message' => 'no employee found', 'status' => "error", 'status_code' => 404], 404);
        }

        try {
            if (!$token = JWTAuth::fromUser($employee)) {
                return response()->json(['status' => "error", 'status_code' => 400, 'message' => 'unable to get the token'], 400);
            }
        } catch (Exception $e) {
                return response()->json(['status' => 'error', 'status_code' => 500, 'message' => 'error creating a token'], 500);
        }

        return response()->json(['status' => 'success', "status_code" => 200, 'token_type' => 'bearer' ,'access_token' => $token, "expires_in" =>  Carbon::now()->addSeconds(config('jwt.ttl')*60)->diffForHumans(), 'user' => $employee], 200);

    }

    public function logout(Request $request) {
      $user = JWTAuth::invalidate(JWTAuth::getToken());
      return response()->json(['status' => "success", 'status_code' => 200,  'message' => "You have succesfully logout", 'user' => $user->user()], 200);
    }
}
