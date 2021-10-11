<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['device','name','description','user','alert','alert_json'];

     ///Relacion uno a muchos
     public function events(){
        return $this->hasMany('App\Models\Event');
    }
}
