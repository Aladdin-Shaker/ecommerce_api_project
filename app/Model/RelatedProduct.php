<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RelatedProduct extends Model
{
    protected $table = 'related_products';
    protected $fillable = [
        'product_id',
        'related_product',
    ];

    public function product()
    {
        return $this->hasOne('App\Model\Product', 'id', 'related_product');
        // hasOne('model', 'foreign_key', 'local_key');
    }
}
