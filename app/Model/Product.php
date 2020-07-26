<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'title',
        'photo',
        'content',
        'weight',
        'size',
        'color',
        'price',
        'stock',
        'status',
        'reason',
        'start_at',
        'end_at',
        'start_offer_at',
        'end_offer_at',
        'price_offer',
        'other_data',
        'department_id',
        'trade_id',
        'manu_id',
        'color_id',
        'size_id',
        'weight_id',
        'currency_id',
    ];

    public function files()
    {
        return $this->hasMany('App\Model\File', 'relation_id', 'id')->where('file_type', 'product');
    }

    public function other_data()
    {
        return $this->hasMany('\App\Model\OtherData');
    }

    public function productMalls()
    {
        return $this->hasMany('App\Model\ProductMall');
    }

    public function related()
    {
        return $this->hasMany('App\Model\RelatedProduct', 'product_id', 'id');
    }
}
