<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'project_mst';

    protected $appends = ['total_distance'];

    protected $visible = ['project_number', 'project_name', 'total_distance'];

    public $timestamps = false;

    protected $primaryKey = 'project_number';

    public $incrementing = false;

    public function getTotalDistanceAttribute($value)
    {
        return $this->attributes['standard_km_from'] + $this->attributes['standard_km_to'];
    }
}
