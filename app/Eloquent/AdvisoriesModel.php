<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class AdvisoriesModel extends Model
{
    //
	protected $table = 'advisories';
	protected $guarded = ['id'];

    protected $dates = [
        'flight_time',
    ];

}
