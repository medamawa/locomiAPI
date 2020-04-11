<?php

use Illuminate\Database\Seeder;
use App\Models\Comic;

class ComicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 50; $i++) {
            $lat = 34.719156 + ($i * 0.0001);
            $lng = 135.268422 + ($i * 0.0001);

            Comic::create([
                'user_id' => $i,
                'location' => ['latitude' => $lat, 'longitude' => $lng],
                'text' => 'これはテスト投稿' . $i,
                'image' => 'https://placehold.jp/100x50.png',
                'release' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
