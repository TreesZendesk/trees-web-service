<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Absence;
use Auth;
use Validator;

class AbsenceController extends Controller
{
    function __construct() {

    }	

    function create(Request $request) {
        $rules = [
            'absence.project_number' => 'exists:project_mst,project_number'
        ];
        $message = [
            'absence.project_number.exists' => "project_number value doesn't exist"
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return response()->json(["status" => "error", "message" => $validator->messages()], 400);
        }

    	$data = $request->get('absence');
    	$absence = Auth::user()->absences()->create($data);
    	if (!$absence) {
    		return response()->json(['status' => "error", 'errors' => 'unable_creating_absence'], 400);
    	}
    	
    	$response = ['status' => 'success', 'absence' => $absence];
    	return response()->json($response, 200);
    }
}
