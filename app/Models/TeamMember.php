<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $table = 'team_members';

    protected $fillable = [
        'name',
        'designation',
        'slug',
        'image',
        'description',
        'facebook',
        'twitter',
        'phone',
        'status',
        'metatitle',
        'metadescription',
        'metakeywords',
        'tags',
        'schema_markup',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
