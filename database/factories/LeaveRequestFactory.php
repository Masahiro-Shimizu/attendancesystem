<?php

namespace Database\Factories;
// database/factories/LeaveRequestFactory.php

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveRequestFactory extends Factory
{
    protected $model = LeaveRequest::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'type' => 'paid_leave',
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'reason' => $this->faker->sentence,
            'status' => 'pending',
        ];
    }
}

