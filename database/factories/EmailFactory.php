<?php

namespace Database\Factories;

use RifRocket\FilamentMailbox\Models\Email;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailFactory extends Factory
{
    protected $model = Email::class;

    public function definition()
    {
        return [
            'folder_id'  => rand(1, 4),
            'uid'        => $this->faker->unique()->numerify('UID####'),
            'user_id'    => 1,
            'message_id' => $this->faker->unique()->uuid,
            'subject'    => $this->faker->sentence,
            'from'       => $this->faker->email,
            'to'         => json_encode([$this->faker->email, $this->faker->email]),
            'cc'         => json_encode([]),
            'bcc'        => json_encode([]),
            'body'       => $this->faker->paragraph,
            'seen'       => $this->faker->boolean,
            'email_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'flags'      => json_encode(['Seen' => $this->faker->boolean]),
        ];
    }
}

