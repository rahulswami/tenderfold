<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSlide extends Model
{
    protected $table = 'home_slides';
    protected $fillable = ['image_path', 'mobile_image_path', 'title', 'subtitle', 'button_text', 'button_link', 'sort_order'];
}
