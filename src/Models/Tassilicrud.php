<?php

namespace Tassili\Tassili\Models;

use Illuminate\Database\Eloquent\Model;

class TassiliCrud extends Model
{

    protected $fillable = [
        'panel',
        'model',
        'label',
        'route',
        'icon',
        'active',
    ];
    
    protected $casts = [
        'active' => 'boolean',
    ];
}