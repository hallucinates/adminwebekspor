<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'name',

        'created_by',
        'updated_by',
        'deleted',
    ];
}
