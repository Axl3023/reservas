<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_name'   => $this->faker->name,
            'customer_phone'  => $this->faker->phoneNumber,
            'customer_email'  => $this->faker->safeEmail,
            'reservation_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'reservation_time' => $this->faker->time('H:i'),
            'guest_count'     => $this->faker->numberBetween(1, 10),
            'table_id'        => Table::inRandomOrder()->first()->id ?? Table::factory(),
            'employee_id'     => Employee::inRandomOrder()->first()->id ?? Employee::factory(),
            'status'          => $this->faker->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'notes'           => $this->faker->boolean(30) ? $this->faker->sentence() : null,
        ];
    }
}
