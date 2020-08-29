<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersDemand extends Model
{
    protected $guarded = [];
    protected $appends = ['time', 'date'];
    
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function getTimeAttribute(){
        return $this->created_at->format('H:i');
    }

    public function getDateAttribute(){
        return $this->created_at->format('d/m/Y');
    }
}
