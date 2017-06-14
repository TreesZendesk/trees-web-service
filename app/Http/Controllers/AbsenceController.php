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
            'absence.project_number' => 'nullable|exists:project_mst,project_number'
        ];
        $message = [
            'absence.project_number.exists' => "Project number doesn't exist"
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return response()->json(["status" => "error", "status_code" => 400, "message" => $validator->errors()->first()], 400);
        }

    	$data = $request->get('absence');
    	$absence = Auth::user()->absences()->create($data);
    	if (!$absence) {
    		return response()->json(['status' => "error", "status_code" => 400, 'message' => 'Unable creating absence'], 400);
    	}

    	$response = ['status' => 'success', 'status_code' => 200, 'absence' => $absence];
    	return response()->json($response, 200);
    }

    function bulkCreate(Request $request) {
        $data = $request->input('absences');
        if (!isset($data) || !is_array($data)) {
            return response()->json(['status' => 'error', 'status_code' => 400, 'message' => "Invalid data type"], 400);
        }

        $rules = [
            '*.project_number' => 'nullable|exists:project_mst,project_number'
        ];
        $message = [
            '*.project_number.exists' => "Project number doesn't exist"
        ];
        $validator = Validator::make($data, $rules, $message);
        if ($validator->fails()) {
            return response()->json(["status" => "error", "status_code" => 400, "message" => $validator->errors()->first()], 400);
        }
        $absences = Auth::user()->absences()->createMany($data);

        if (!$absences) {
            return reponse()->json(['status' => 'error', 'status_code' => 400, 'message' => 'Unable creating absence'], 400);
        }

        $response = ['status' => 'success', 'status_code' => 200, 'absences' => $absences];
        return response()->json($response, 200);
    }
}
