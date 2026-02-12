<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use \App\Traits\LogsActivity;
    protected $fillable = ['key', 'value', 'group'];

    protected $casts = [
        'value' => 'encrypted',
    ];
}
