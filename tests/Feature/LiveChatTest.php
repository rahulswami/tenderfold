<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ChatMessage;
use App\Models\User;

class LiveChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_send_chat_message(): void
    {
        $response = $this->postJson(route('chat.send'), [
            'session_id' => 'sess_test_123',
            'message' => 'Hello Clinic Support',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        
        $this->assertDatabaseHas('chat_messages', [
            'session_id' => 'sess_test_123',
            'sender' => 'visitor',
            'message' => 'Hello Clinic Support',
        ]);
    }

    public function test_visitor_can_load_chat_history(): void
    {
        // Pre-create message
        ChatMessage::create([
            'session_id' => 'sess_test_123',
            'sender' => 'admin',
            'message' => 'Hello! How can I help you?',
            'is_read' => false,
        ]);

        $response = $this->getJson(route('chat.history', ['session_id' => 'sess_test_123']));

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertCount(1, $response->json('messages'));
        
        // Assert admin message is marked read
        $this->assertEquals(1, ChatMessage::where('session_id', 'sess_test_123')->where('sender', 'admin')->where('is_read', true)->count());
    }

    public function test_auto_message_triggers_once(): void
    {
        // First trigger
        $response = $this->postJson(route('chat.auto-trigger'), [
            'session_id' => 'sess_auto_123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message.sender', 'admin');
        
        // Second trigger should fail
        $response2 = $this->postJson(route('chat.auto-trigger'), [
            'session_id' => 'sess_auto_123',
        ]);
        $response2->assertStatus(200);
        $response2->assertJsonPath('success', false);
    }

    public function test_admin_can_manage_chats(): void
    {
        $user = User::factory()->create();

        // Visitor message
        ChatMessage::create([
            'session_id' => 'sess_admin_123',
            'sender' => 'visitor',
            'message' => 'Help me',
        ]);

        // List sessions
        $response = $this->actingAs($user)->getJson(route('admin.chats.list'));
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertCount(1, $response->json('sessions'));

        // Get session messages
        $responseMsg = $this->actingAs($user)->getJson(route('admin.chats.messages', 'sess_admin_123'));
        $responseMsg->assertStatus(200);
        $this->assertCount(1, $responseMsg->json('messages'));

        // Reply to session
        $responseReply = $this->actingAs($user)->postJson(route('admin.chats.reply', 'sess_admin_123'), [
            'message' => 'Sure, we are here.',
        ]);
        $responseReply->assertStatus(200);
        $this->assertDatabaseHas('chat_messages', [
            'session_id' => 'sess_admin_123',
            'sender' => 'admin',
            'message' => 'Sure, we are here.',
        ]);

        // Delete session
        $responseDelete = $this->actingAs($user)->deleteJson(route('admin.chats.delete', 'sess_admin_123'));
        $responseDelete->assertStatus(200);
        $this->assertEquals(0, ChatMessage::count());
    }
}
