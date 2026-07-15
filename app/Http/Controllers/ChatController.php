<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;
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

        $msg = ChatMessage::create([
            'session_id' => $request->session_id,
            'sender' => 'visitor',
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json(['success' => true, 'message' => $msg]);
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
            $msg = ChatMessage::create([
                'session_id' => $sessionId,
                'sender' => 'admin',
                'message' => 'Hello! Thanks for visiting SumitZ Clinic. How can I help you today with your joint or spine recovery?',
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
        // Select latest message for each session_id
        $subQuery = ChatMessage::select('session_id', DB::raw('MAX(id) as max_id'))
            ->groupBy('session_id');

        $sessions = ChatMessage::joinSub($subQuery, 'latest_msg', function ($join) {
                $join->on('chat_messages.id', '=', 'latest_msg.max_id');
            })
            ->orderBy('chat_messages.created_at', 'desc')
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
