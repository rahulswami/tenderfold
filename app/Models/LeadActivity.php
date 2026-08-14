<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'user_name',
        'action_type',
        'status_from',
        'status_to',
        'notes',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
