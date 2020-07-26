<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Mall extends Model
{
    protected $table = "malls";

    protected $fillable = [
        'name_ar',
        'name_en',
        'country_id',
        'email',
        'mobile',
        'address',
        'facebook',
        'twitter',
        'website',
        'contact_name',
        'lat',
        'lng',
        'icon'
    ];

    public function country()
    {
        return $this->hasOne('App\Model\Country', 'id', 'country_id');
    }
}
