<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Manufacture extends Model
{
    protected $table = "manufactures";

    protected $fillable = [
        'name_ar',
        'name_en',
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
}
