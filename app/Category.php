<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];
    protected $appends = ['products'];

    public function products(){
        return $this->hasMany('App\Product');
    }

    public function getProductsAttribute(){
        return $this->products()->orderBy('qtd', 'DESC')->take(4)->get();
    }
}
