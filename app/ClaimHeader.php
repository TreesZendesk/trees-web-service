<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class ClaimHeader extends Model
{
	protected $table = 'claim_header';

	protected $fillable = ['trx_id', 'claim_date', 'activity_code', 'client_code', 'employee_number', 'toll_from', 'toll_to', 'mileage', 'parking', 'meal', 'created_by', 'creation_date', 'batch_id'];

	public $timestamps = false;

	protected $primaryKey = 'trx_id';

	public function __construct($attrs = []) {
		parent::__construct($attrs);

		$this->attributes['created_by'] = isset(Auth::user()->employee_name) ? Auth::user()->employee_name : "";
		$this->attributes['creation_date'] = Carbon::now();
	}

	public function setClaimDateAttribute($value)
	{
		$this->attributes['claim_date'] = Carbon::parse($value);
	}

	public function getClaimDateAttribute($value)
	{
		return $value->format('Y-m-d');
	}

	public function getCreationDateAttribute($value)
	{
		return $value->format('Y-m-d H:i:s');
	}

	function details() {
		return $this->hasMany('App\ClaimDetail', 'trx_header_id', 'trx_id');
	}
}
