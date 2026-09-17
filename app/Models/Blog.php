<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $fillable = [
        'id',
        'slug',
        'title',
        'category',
        'summary',
        'content',
        'image_path',
        'published_at',
        'schema_markup',
        'metatitle',
        'metadescription',
        'metakeywords',
        'tags',
    ];
    protected $casts = [
        'published_at' => 'datetime',
    ];
}
