<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteParameter extends Model
{
    protected $table = 'site_parameters';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'value', 'label', 'category'];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('global_site_parameters');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('global_site_parameters');
        });
    }
}
