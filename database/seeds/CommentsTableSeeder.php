<?php

use Illuminate\Database\Seeder;
use App\Models\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 50; $i++) {
            Comment::create([
                'user_id' => 1,
                'comic_id' => $i,
                'text' => 'これはテストコメント' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
