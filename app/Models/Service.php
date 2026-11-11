<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'slug',
        'image_path',
        'icon_path',
        'service_name',
        'hero_category',
        'hero_title',
        'hero_subtitle',
        'rate_value',
        'max_loan',
        'tenure',
        'intro_title',
        'intro_content',
        'eligibility_criteria',
        'documents_required',
        'tips_title',
        'tips_content',
        'faqs',
        'badge',
        'summary',
        'is_active',
        'sort_order',
        'schema_markup',
        'metatitle',
        'metadescription',
        'metakeywords',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'faqs' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getMetaTitleAttribute()
    {
        return $this->attributes['metatitle'] ?? $this->attributes['meta_title'] ?? null;
    }

    public function setMetaTitleAttribute($value)
    {
        $this->attributes['metatitle'] = $value;
    }

    public function getMetaDescriptionAttribute()
    {
        return $this->attributes['metadescription'] ?? $this->attributes['meta_description'] ?? null;
    }

    public function setMetaDescriptionAttribute($value)
    {
        $this->attributes['metadescription'] = $value;
    }

    public function getMetaKeywordsAttribute()
    {
        return $this->attributes['metakeywords'] ?? $this->attributes['meta_keywords'] ?? null;
    }

    public function setMetaKeywordsAttribute($value)
    {
        $this->attributes['metakeywords'] = $value;
    }
}
