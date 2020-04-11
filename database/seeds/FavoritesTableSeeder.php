<?php

use Illuminate\Database\Seeder;
use App\Models\Favorite;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 2; $i <= 50; $i++) {
            Favorite::create([
                'user_id' => 1,
                'comic_id' => $i,
            ]);
        }
    }
}
