<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    use HasFactory;

    protected $table = 'cms_pages';

    protected $fillable = [
        'title',
        'slug',
        'metatitle',
        'metadescription',
        'metakeywords',
        'image',
        'description',
        'status',
        'schema_markup',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
