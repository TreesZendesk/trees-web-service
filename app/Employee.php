<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Employee extends Model implements Authenticatable, JWTSubject
{
    use Notifiable;

    protected $table = "emp_mst";

    protected $fillable = ['employee_number', 'employee_name', 'cell_no', 'creation_date'];

    public $timestamps = false;

    protected $primaryKey = 'employee_number';

    public $incrementing = false;

    public function claimHeaders() {
        return $this->hasMany('App\ClaimHeader', 'employee_number', 'employee_number');
    }

    public function absences() {
        return $this->hasMany('App\Absence', 'employee_number', 'employee_number');
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName() {
    	return 'employee_number';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
    	return $this->attributes['employee_number'];
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
    	return "nopassword";
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken() {
    	return "noremembertoken";
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value) {

    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName() {
    	return "noremembertokenname";
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
    	return $this->attributes['employee_number'];
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
    	return [
    		'user' => [
    			'employee_number' => $this->attributes['employee_number']
    		]
    	];
    }
}
