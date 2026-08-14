<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'user_name',
        'action_url',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Log an admin notification helper
     */
    public static function log(string $type, string $title, ?string $message = null, ?string $url = null)
    {
        try {
            if (!Schema::hasTable('admin_notifications')) {
                return null;
            }

            $userName = Auth::check() ? Auth::user()->name : 'System';

            return self::create([
                'type' => strtolower($type),
                'title' => $title,
                'message' => $message,
                'user_name' => $userName,
                'action_url' => $url,
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed logging admin notification: ' . $e->getMessage());
            return null;
        }
    }
}
