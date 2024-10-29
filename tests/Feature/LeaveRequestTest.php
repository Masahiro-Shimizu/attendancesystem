<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\LeaveRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaveRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_leave_request()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('leave_requests.store'), [
            'type' => 'paid_leave',
            'start_date' => '2024-10-01',
            'end_date' => '2024-10-03',
            'reason' => 'Personal matters',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leave_requests', [
            'user_id' => $user->id,
            'type' => 'paid_leave',
            'start_date' => '2024-10-01',
            'end_date' => '2024-10-03',
        ]);
    }

    public function test_admin_can_approve_leave_request()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $leaveRequest = LeaveRequest::factory()->create(['status' => 'pending']);

        $this->actingAs($admin);
        $response = $this->post(route('admin.leave_requests.approve', $leaveRequest->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('leave_requests', [
            'id' => $leaveRequest->id,
            'status' => 'approved',
        ]);
    }

    public function test_admin_can_reject_leave_request()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $leaveRequest = LeaveRequest::factory()->create(['status' => 'pending']);

        $this->actingAs($admin);
        $response = $this->post(route('admin.leave_requests.reject', $leaveRequest->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('leave_requests', [
            'id' => $leaveRequest->id,
            'status' => 'rejected',
        ]);
    }
}
