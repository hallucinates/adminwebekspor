<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = 'template';

    protected $fillable = [
        'kategori_id',
        'name',
        'gambar',
        'beli',
        'beli_palsu',

        'created_by',
        'updated_by',
        'deleted',
    ];
}
