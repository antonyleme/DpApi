<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bill extends Model
{
    protected $guarded = [];

    public function getDueAttribute(){
        return Carbon::parse($this->attributes['due'])->format('d/m/Y');
    }
}
