<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\MonthlyReport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MonthlyReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_monthly_report()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('monthly_report.store'), [
            'month' => '2024-10',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('monthly_reports', [
            'user_id' => $user->id,
            'month' => '2024-10-01',
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_monthly_report()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $report = MonthlyReport::factory()->create(['status' => 'pending']);

        $this->actingAs($admin);
        $response = $this->post(route('monthly_report.approve', $report->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('monthly_reports', [
            'id' => $report->id,
            'status' => 'approved',
        ]);
    }

    public function test_admin_can_reject_monthly_report()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $report = MonthlyReport::factory()->create(['status' => 'pending']);

        $this->actingAs($admin);
        $response = $this->post(route('monthly_report.reject', $report->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('monthly_reports', [
            'id' => $report->id,
            'status' => 'rejected',
        ]);
    }
}
