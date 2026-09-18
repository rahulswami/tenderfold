<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSlide extends Model
{
    protected $table = 'home_slides';
    protected $fillable = [
        'image_path',
        'mobile_image_path',
        'eyebrow',
        'title',
        'subtitle',
        'button_text',
        'button_link',
        'button_2_text',
        'button_2_link',
        'trust_1_num',
        'trust_1_label',
        'trust_2_num',
        'trust_2_label',
        'trust_3_num',
        'trust_3_label',
        'sort_order'
    ];
}
