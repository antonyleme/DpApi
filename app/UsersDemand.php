<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersDemand extends Model
{
    protected $guarded = [];
    protected $appends = ['user', 'products', 'time', 'date'];
    
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function products(){
        return $this->belongsToMany('App\Product', 'demands_products', 'users_demand_id', 'id')->withPivot(['qtd', 'price']);
    }

    public function getUserAttribute(){
        return $this->user()->first();
    }

    public function getProductsAttribute(){
        return $this->products()->get();
    }

    public function getTimeAttribute(){
        return $this->created_at->format('H:i');
    }

    public function getDateAttribute(){
        return $this->created_at->format('d/m/Y');
    }
}
