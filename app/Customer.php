<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer_mst';

    protected $appends = ['total_distance'];

    protected $visible = ['customer_code', 'customer_name', 'total_distance'];

    public $timestamps = false;

    protected $primaryKey = 'customer_code';

    public function getTotalDistanceAttribute($value)
    {
        return $this->attributes['standard_km_from'] + $this->attributes['standard_km_to'];
    }
}
