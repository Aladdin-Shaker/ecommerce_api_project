<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductMall extends Model
{
    protected $table = 'product_malls';
    protected $fillable = [
        'product_id',
        'mall_id',
    ];

    public function mall()
    {
        return $this->hasOne('App\Model\Mall');
    }
}
