<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Car extends Model
{
    protected $table = 'cars';

    //Relacion
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
