<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TLD extends Model
{
    protected $table = 'tld';

    protected $fillable = [
        'name',
        'harga',

        'created_by',
        'updated_by',
        'deleted',
    ];
}
