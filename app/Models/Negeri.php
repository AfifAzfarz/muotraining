<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Negeri extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'attribute_id',
        'nama',
        'kod',
        'status',
        'synced_at'
    ];
}
