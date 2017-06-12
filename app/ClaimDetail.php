<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClaimDetail extends Model
{
	protected $table = 'claim_detail';

	protected $fillable = ['trx_detail_id', 'trx_header_id', 'taxi_from', 'taxi_to', 'taxi_time', 'taxi_voucher_no', 'taxi_amount', 'batch_id'];

	public $timestamps = false;

	protected $primaryKey = 'trx_detail_id';

	public function setTaxiTimeAttribute($value)
	{
		$this->attributes['taxi_time'] = Carbon::parse($value);
	}

	public function getTaxiTimeAttribute($value)
	{
		return $value->format('H:i:s');
	}

	function header() {
		return $this->belongsTo('App\ClaimHeader', 'trx_id', 'trx_header_id');
	}
}
