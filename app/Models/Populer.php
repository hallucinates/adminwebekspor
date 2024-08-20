<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Populer extends Model
{
    protected $table = 'populer';

    protected $fillable = [
        'urutan',
        'tld_id',

        'created_by',
        'updated_by',
        'deleted',
    ];
}
