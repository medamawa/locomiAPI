<?php

use Illuminate\Database\Seeder;
use App\Models\Friend;

class FriendsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 2; $i <= 50; $i++) {
            Friend::create([
                'following_id' => $i,
                'followed_id' => 1,
                'request' => false,
                'approval' => true,
                'block' => false,
            ]);
        }
    }
}
