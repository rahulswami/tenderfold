<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'original_url',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'full_url',
        'title',
        'clicks',
    ];
}
