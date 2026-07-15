<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Lead;
use App\Models\User;

class LeadsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed parameters if needed or create user
    }

    public function test_admin_can_export_leads_to_csv(): void
    {
        // Create an admin user and authenticate
        $user = User::factory()->create();
        
        // Create dummy leads
        Lead::create([
            'name' => 'John Doe',
            'phone' => '1234567890',
            'email' => 'john@example.com',
            'message' => 'Need help with joint pain',
            'source' => 'Appointment Request Form',
            'status' => 'New',
        ]);

        $response = $this->actingAs($user)
            ->get(route('admin.leads.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('John Doe', $response->streamedContent());
    }

    public function test_admin_can_bulk_delete_leads(): void
    {
        $user = User::factory()->create();

        $lead1 = Lead::create([
            'name' => 'Lead One',
            'phone' => '1111111111',
            'email' => 'lead1@example.com',
            'message' => 'Pain in knee',
            'source' => 'Contact Us',
            'status' => 'New',
        ]);

        $lead2 = Lead::create([
            'name' => 'Lead Two',
            'phone' => '2222222222',
            'email' => 'lead2@example.com',
            'message' => 'Pain in back',
            'source' => 'Contact Us',
            'status' => 'New',
        ]);

        $this->assertEquals(2, Lead::count());

        $response = $this->actingAs($user)
            ->post(route('admin.leads.bulk-delete'), [
                'ids' => [$lead1->id, $lead2->id],
            ]);

        $response->assertStatus(302); // Redirect back
        $this->assertEquals(0, Lead::count());
    }
}
