<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClaimHeader extends Model
{
	protected $table = 'claim_header';

	protected $fillable = ['trx_id', 'claim_date', 'activity_code', 'client_code', 'employee_number', 'toll_from', 'toll_to', 'mileage', 'parking', 'meal', 'created_by', 'creation_date', 'batch_id'];

	public $timestamps = false;

	protected $primaryKey = 'trx_id';

	public function setClaimDateAttribute($value)
	{
		$this->attributes['claim_date'] = Carbon::parse($value);
	}

	public function getClaimDateAttribute($value)
	{
		return $value->format('Y-m-d');
	}

	public function setCreationDateAttribute($value)
	{
		$this->attributes['creation_date'] = Carbon::parse($value);
	}

	public function getCreationDateAttribute($value)
	{
		return $value->format('Y-m-d h:i:s');
	}

	function details() {
		return $this->hasMany('App\ClaimDetail', 'trx_header_id', 'trx_id');
	}
}