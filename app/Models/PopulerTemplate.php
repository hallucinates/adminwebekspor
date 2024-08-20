<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopulerTemplate extends Model
{
    protected $table = 'populer_template';

    protected $fillable = [
        'urutan',
        'template_id',

        'created_by',
        'updated_by',
        'deleted',
    ];
}
