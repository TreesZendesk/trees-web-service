<?php

use Illuminate\Http\Request;
use \Auth as Auth;
use \JWTAuth as JWTAuth;
use App\Customer;
use App\Project;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function() {
	Route::get('/lists', function() {
		$projects = Project::all();
		$customers = Customer::all();

		$lists = ['status' => 'success','projects' => $projects, 'customers' => $customers];
		return response()->json($lists, 200);
	});
	Route::post('/login', 'Auth\LoginController@login')->middleware('guest');
	Route::post('/logout', 'Auth\LoginController@logout')->middleware('auth:api');
	Route::group(['middleware' => 'auth:api'], function () {

		Route::post('claims/create_many', 'ClaimController@bulkCreate');
		Route::post('claims/header', 'ClaimController@postHeader');
		Route::post('claims/header/{trx_id}/details', 'ClaimController@postDetails');
		Route::post('absence', 'AbsenceController@create');

		Route::get('user', function (Request $request) {
			return Auth::user();
			//return $request->user();
		});
		Route::get('test', function () {
			return 'authenticated';
		});
	});

});
