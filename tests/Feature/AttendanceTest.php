<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_punch_in()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $punchInTime = now()->toDateTimeString();  // 事前に時間を設定

        $response = $this->post(route('punch-in'));
    
        $response->assertStatus(200);
        $this->assertDatabaseHas('times', [
            'user_id' => $user->id,
            'punch_in' => $punchInTime,
        ]);
    }



    public function test_user_can_punch_out()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('punch-in'));
        $response = $this->post(route('punch-out'));

        $response->assertStatus(200);
        $this->assertDatabaseHas('times', [
            'user_id' => $user->id,
            'punch_out' => now()->toDateTimeString(),
        ]);
    }

    public function test_user_can_start_break()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('break-start'));

        $response->assertStatus(200);
        $this->assertDatabaseHas('breaks', [
            'user_id' => $user->id,
            'start_time' => now()->toDateTimeString(),
        ]);
    }

    public function test_user_can_end_break()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('break-start'));
        $response = $this->post(route('break-end'));

        $response->assertStatus(200);
        $this->assertDatabaseHas('breaks', [
            'user_id' => $user->id,
            'end_time' => now()->toDateTimeString(),
        ]);
    }
}
