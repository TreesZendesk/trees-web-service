<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class Absence extends Model
{
    protected $table = 'absence_trx';

    public $timestamps = false;

    public $fillable = ['abs_trx_id', 'date_from', 'date_to', 'project_number', 'employee_number', 'activity_status', 'created_by', 'creation_date', 'batch_id'];

    protected $primaryKey = 'abs_trx_id';

    public function __construct($attrs = []) {
        parent::__construct($attrs);

        $this->attributes['created_by'] = isset(Auth::user()->employee_name) ? Auth::user()->employee_name : "";
        $this->attributes['creation_date'] = Carbon::now();
    }

	public function setDateFromAttribute($value)
	{
		$this->attributes['date_from'] = Carbon::parse($value);
	}

	public function getDateFromAttribute($value)
	{
		return $value->format('Y-m-d');
	}

	public function setDateToAttribute($value)
	{
		$this->attributes['date_to'] = Carbon::parse($value);
	}

	public function getDateToAttribute($value)
	{
		return $value->format('Y-m-d');
	}

    public function getCreationDateAttribute($value) {
        return $value->format('Y-m-d H:i:s');
    }

	public function employee() {
		return $this->belongsTo('App\Employee', 'employee_number', 'employee_number');
	}
}
