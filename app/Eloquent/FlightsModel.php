<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class FlightsModel extends Model
{
    //
	protected $table = 'flights';
	protected $guarded = ['id'];

    protected $dates = [
        'flight_time',
    ];

    public function advisories()
    {
        return $this->hasMany('App\Eloquent\AdvisoriesModel', 'flight_id')->orderBy('distance', 'asc');
    }
}
