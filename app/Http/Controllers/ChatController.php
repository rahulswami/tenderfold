<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\SiteParameter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Public visitor endpoints
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string|max:5000',
        ]);

        $visitorMsg = ChatMessage::create([
            'session_id' => $request->session_id,
            'sender' => 'visitor',
            'message' => $request->message,
            'is_read' => false,
        ]);

        \App\Models\AdminNotification::log(
            'chat',
            'New Live Chat Message',
            'Session #' . substr($request->session_id, -6) . ': ' . \Illuminate\Support\Str::limit($request->message, 80),
            route('admin.tab', 'livechat')
        );

        return response()->json([
            'success' => true, 
            'message' => $visitorMsg
        ]);
    }

    public function getHistory(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        $messages = ChatMessage::where('session_id', $request->session_id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark admin replies as read when loaded by visitor
        ChatMessage::where('session_id', $request->session_id)
            ->where('sender', 'admin')
            ->update(['is_read' => true]);

        return response()->json(['success' => true, 'messages' => $messages]);
    }

    public function autoTrigger(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        $sessionId = $request->session_id;

        // Check if there is already any message in this session
        $exists = ChatMessage::where('session_id', $sessionId)->exists();

        if (!$exists) {
            $welcomeMsg = SiteParameter::where('id', 'chat_welcome_message')->value('value')
                ?? 'Hello! Welcome to SumitZ Clinic. How can we help you today with your joint, spine, or physiotherapy recovery?';

            $msg = ChatMessage::create([
                'session_id' => $sessionId,
                'sender' => 'admin',
                'message' => $welcomeMsg,
                'is_read' => false,
            ]);
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return response()->json(['success' => false, 'message' => 'Chat already active.']);
    }

    /**
     * Admin endpoints (must be authenticated)
     */
    public function adminListSessions()
    {
        // Select latest message ID for each session_id
        $latestIds = ChatMessage::select(DB::raw('MAX(id) as max_id'))
            ->groupBy('session_id')
            ->pluck('max_id');

        $sessions = ChatMessage::whereIn('id', $latestIds)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate unread counts
        foreach ($sessions as $session) {
            $session->unread_count = ChatMessage::where('session_id', $session->session_id)
                ->where('sender', 'visitor')
                ->where('is_read', false)
                ->count();
        }

        return response()->json(['success' => true, 'sessions' => $sessions]);
    }

    public function adminGetSessionMessages($session_id)
    {
        $messages = ChatMessage::where('session_id', $session_id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark visitor messages as read
        ChatMessage::where('session_id', $session_id)
            ->where('sender', 'visitor')
            ->update(['is_read' => true]);

        return response()->json(['success' => true, 'messages' => $messages]);
    }

    public function adminSendReply(Request $request, $session_id)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $msg = ChatMessage::create([
            'session_id' => $session_id,
            'sender' => 'admin',
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function adminDeleteChat($session_id)
    {
        ChatMessage::where('session_id', $session_id)->delete();
        return response()->json(['success' => true]);
    }
}
