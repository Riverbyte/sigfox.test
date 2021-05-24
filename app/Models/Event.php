<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name','destination','content','active','device_id'];

    ///Relacion uno a muchos inversa
    public function device(){
        return $this->belongsTo('App\Models\device');
    }
}
