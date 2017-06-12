<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\ClaimHeader;
use App\ClaimDetail;
use Auth;

class ClaimController extends Controller
{
	//
	function __construct() {

	}

	function bulkCreate(Request $request, ClaimHeader $header, ClaimDetail $detail) {
		$data = $request->input('claim_headers');

		if (!$data || !is_array($data)) {
			return response()->json(['errors' => "invalid_request_body"], 400);
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

		$response = ['status' => 'success', 'claim_headers' => $result];
		return response()->json($response, 200);
	}

	function postHeader(Request $request) {
		$data = $request->input('claim_header');

		if (!$data || !is_array($data)) {
			return response()->json(['status' => 'error', 'errors' => "invalid request body"], 400);
		}

		$claim_header = Auth::user()->claimHeaders()->create($data);
		if (!$claim_header) {
			return response()->json(["errors" => "error creating claim header"], 400);
		}

		$result = $claim_header->toArray();

		if (count($data['claim_details']) > 0) {
			$details = $claim_header->details()->createMany($data['claim_details']);
			if (!$details) {
				$result['errors'] = ["error" => "error creating details"];
			}
			$result['claim_details'] = $details;
		}

		$response = ['status' => 'success', 'claims_headers' => $result];
		return response()->json($response, 200);
	}

	function postDetails(Request $request, $trx_id) {
		$data = $request->input('claim_details');

		$claim_header = ClaimHeader::where('employee_number', Auth::user()->employee_number)->where('trx_id', $trx_id)->first();
		if (!$claim_header) {
			return response()->json([ "status" => 'error', 'errors' => "unauthorize"], 401);
		}

		$details = $claim_header->details()->createMany($data);
		if (!$details) {
			return response()->json(["status" => 'error', 'errors' => 'unable_creating_details'], 400);
		}

		$response = ['status' => 'success', 'claim_details' => $details];
		return response()->json($response, 200);
	}
}
