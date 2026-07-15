<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'metatitle',
        'metadescription',
        'metakeywords',
        'tags',
        'status',
        'schema_markup',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
