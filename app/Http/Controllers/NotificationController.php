<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Public API: subscribe session_id
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        $exists = DB::table('push_subscriptions')
            ->where('session_id', $request->session_id)
            ->exists();

        if (!$exists) {
            DB::table('push_subscriptions')->insert([
                'session_id' => $request->session_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Public API: poll for targeted and broadcast notifications
     */
    public function poll(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'last_id' => 'required|integer',
        ]);

        $sessionId = $request->session_id;
        $lastId = (int) $request->last_id;

        // Query targeted unread notifications for this user (chat replies)
        $targeted = DB::table('web_notifications')
            ->where('session_id', $sessionId)
            ->where('is_read', false)
            ->get();

        // Mark targeted notifications as read so they are only delivered once
        if ($targeted->isNotEmpty()) {
            DB::table('web_notifications')
                ->where('session_id', $sessionId)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        // Query general broadcast notifications (session_id is null) higher than last_id
        $maxId = DB::table('web_notifications')->max('id') ?? 0;
        if ($lastId === 0) {
            // Initialize lastId to current max notification ID to avoid history flooding on new session
            $lastId = $maxId;
            $broadcasts = collect();
        } else {
            $broadcasts = DB::table('web_notifications')
                ->whereNull('session_id')
                ->where('id', '>', $lastId)
                ->get();
        }

        $notifications = $targeted->merge($broadcasts)->sortBy('id')->values();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'last_id' => $lastId
        ]);
    }

    /**
     * Admin Panel Action: broadcast a new message to all subscribed users
     */
    public function broadcast(Request $request)
    {
        // Require seo/notifications permissions
        $user = auth()->user();
        if ($user->role !== 'superadmin') {
            $permissions = is_array($user->permissions) ? $user->permissions : (json_decode($user->permissions ?? '[]', true) ?: []);
            if (!in_array('all', $permissions) && !in_array('seo', $permissions)) {
                abort(403, 'Unauthorized action.');
            }
        }

        $request->validate([
            'type' => 'required|string|in:custom,blog,cms_page,service,specialist,landing',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'url' => 'nullable|string|max:2048',
        ]);

        DB::table('web_notifications')->insert([
            'session_id' => null, // null means broadcast to all
            'title' => $request->title,
            'message' => $request->message,
            'url' => $request->url,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Notification broadcasted to all subscribed users successfully!');
    }
}
