<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = "states";
    protected $fillable = [
        'state_name_ar',
        'state_name_en',
        'city_id',
        'country_id'
    ];

    public function country()
    {
        return $this->belongsTo('App\Model\Country');
    }
    public function city()
    {
        return $this->belongsTo('App\Model\City');
    }
}
