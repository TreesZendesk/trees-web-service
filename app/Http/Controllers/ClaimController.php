<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\ClaimHeader;
use App\ClaimDetail;
use Auth;
use Validator;

class ClaimController extends Controller
{
	//
	function __construct() {

	}

	function bulkCreate(Request $request, ClaimHeader $header, ClaimDetail $detail) {
		$data = $request->input('claim_headers');

		if (!$data || !is_array($data)) {
			return response()->json(['status' => 'error', 'status_code' => 400, 'message' => "Invalid request body"], 400);
		}

		$rules = [
            '*.claim_date' => 'required|date',
            '*.activity_code' => 'required',
			'*.toll_from' => 'nullable|integer',
			'*.toll_to' => 'nullable|integer',
			'*.milleage' => 'nullable|integer',
			'*.parking' => 'nullable|integer',
			'*.meal' => 'nullable|integer',
			'*.claim_details.*.taxi_from' => 'sometimes|required',
			'*.claim_details.*.taxi_to' => 'sometimes|required',
			'*.claim_details.*.taxi_time' => 'sometimes|required|time',
			'*.claim_details.*.taxi_voucher_no' => 'sometimes|required',
			'*.claim_details.*.taxi_amount' => 'sometimes|required|integer'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(["status" => "error", "status_code" => 400, "message" => $validator->errors()->first()], 400);
        }

		$result = [];
		foreach ($data as $key => $header) {
			$claim_header = Auth::user()->claimHeaders()->create($header);
			if (!$claim_header) {
				$result[$key] = ["errors" => "error_creating_claim_header"];
				continue;
			}

			$result[$key] = $claim_header->toArray();
			$result[$key]['claim_details'] = [];

			if (isset($header['claim_details']) && count($header['claim_details']) > 0) {
				$details = $claim_header->details()->createMany($header['claim_details']);

				if (!$details) {
					$result[$key]['errors'] = ["errors" => "error_creating_details"];
				}
				$result[$key]['claim_details'] = $details;
			}

		}

		$response = ['status' => 'success', 'status_code' => 200, 'claim_headers' => $result];
		return response()->json($response, 200);
	}

	function postHeader(Request $request) {
		$data = $request->input('claim_header');

		if (!$data || !is_array($data)) {
			return response()->json(['status' => 'error', 'status_code' => 400, 'message' => "Invalid request body"], 400);
		}

		$rules = [
			'claim_date' => 'required|date',
            'activity_code' => 'required',
			'toll_from' => 'nullable|integer',
			'toll_to' => 'nullable|integer',
			'milleage' => 'nullable|integer',
			'parking' => 'nullable|integer',
			'meal' => 'nullable|integer',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(["status" => "error", "status_code" => 400, "message" => $validator->errors()->first()], 400);
        }

		$claim_header = Auth::user()->claimHeaders()->create($data);
		if (!$claim_header) {
			return response()->json(["errors" => "error creating claim header"], 400);
		}

		$result = $claim_header->toArray();

		if (isset($data['claim_details']) && count($data['claim_details']) > 0) {
			$details = $claim_header->details()->createMany($data['claim_details']);
			if (!$details) {
				$result['errors'] = ["error" => "error creating details"];
			}
			$result['claim_details'] = $details;
		}

		$response = ['status' => 'success', 'status_code' => 200, 'claims_headers' => $result];
		return response()->json($response, 200);
	}

	function postDetails(Request $request, $trx_id) {
		$data = $request->input('claim_details');

		$rules = [
            '*.taxi_from' => 'required',
            '*.taxi_to' => 'required',
			'*.taxi_time' => 'required|time',
			'*.taxi_voucher_no' => 'required',
			'*.taxi_amount' => 'required|integer'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(["status" => "error", "status_code" => 400, "message" => $validator->errors()->first()], 400);
        }

		try {
			$claim_header = ClaimHeader::where('employee_number', Auth::user()->employee_number)->where('trx_id', $trx_id)->first();

			if ($claim_header == false) {
				return response()->json([ "status" => 'error', 'status_code' => 400, 'message' => "Claim header with that trx_id not found or you don't have the privilege to add details on that header"], 401);
			}
		} catch (Exception $e) {
			return repsonse()->json(['status' => 'error', 'status_code' => 500, "message" => "Internal error"]);
		}

		$details = $claim_header->details()->createMany($data);
		if (!$details) {
			return response()->json(["status" => 'error','status_code' => 400, 'message' => 'Unable creating details'], 400);
		}

		$response = ['status' => 'success', 'status_code' => 200, 'claim_details' => $details];
		return response()->json($response, 200);
	}
}



//status
////status_code
//message
