<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = 'promo';

    protected $fillable = [
        'tld_id',
        'harga',
        'tag',

        'created_by',
        'updated_by',
        'deleted',
    ];
}
