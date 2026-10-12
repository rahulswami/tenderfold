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

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = !empty($value) ? trim($value, " /\t\n\r\0\x0B") : null;
    }

    public function getSlugAttribute($value)
    {
        return !empty($value) ? trim($value, " /\t\n\r\0\x0B") : $value;
    }

    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'category', 'name');
    }
}
