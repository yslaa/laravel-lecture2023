<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item = new Item([
            'imagePath' => 'https://images.freeimages.com/images/large-previews/c0d/little-jars-with-stones-on-the-white-background-1641022.jpg',
            'title' => 'Harry Potter',
            'description' => 'Lorem ipsum dolor sit amet,',
            'cost_price' => 1,
            'sell_price' => 1
        ]);
        $item->save();

        $item = new Item([
            'imagePath' => 'https://images.freeimages.com/images/large-previews/c0d/little-jars-with-stones-on-the-white-background-1641022.jpg',
            'title' => 'Harry Potter',
            'description' => 'Lorem ipsum dolor sit amet',
            'cost_price' => 1,
            'sell_price' => 1
        ]);
        $item->save();

        $item = new Item([
            'imagePath' => 'https://images.freeimages.com/images/large-previews/c0d/little-jars-with-stones-on-the-white-background-1641022.jpg',
            'title' => 'Harry Potter',
            'description' => 'Lorem ipsum dolor sit amet',
            'cost_price' => 1,
            'sell_price' => 1
        ]);
        $item->save();
    }
}
