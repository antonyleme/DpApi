<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DemandsProduct extends Model
{
    protected $guarded = [];
    
    public function demand(){
        return $this->belongsTo('App\UsersDemand');
    }

    public function products(){
        return $this->hasMany('App\Product');
    }
}
