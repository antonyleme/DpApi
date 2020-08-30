<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'category_id',
        'available',
        'imgPath'
    ];

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function demands(){
        return $this->belongsToMany('App\UsersDemand', 'demands_products', 'product_id', 'id')->withPivot(['qtd', 'price']);
    }
}