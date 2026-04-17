<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObraSocial extends Model
{
    protected $table = 'obras_sociales';
    protected $fillable = ['nombre'];
    public $timestamps = false;
}
