<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Models\Item;
use App\Models\Stock;
use Barryvdh\Debugbar\Facade as DebugBar;

class ItemStockImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Item::create([
            //     'name' => $row[0],
            //     'cost_price' => $row[1],
            //     'sell_price' => $row[2],
            //     'image_path' => 'default.jpg',
            //     'title' => NULL,
            // ]);
            $item = Item::create([
                'description' => $row['item_name'],
                'cost_price' => $row['cost'],
                'sell_price' => $row['selling_price'],
                'image_path' => 'default.jpg',
                'title' => NULL,
            ]);
            $stock = new Stock();
            $stock->item_id = $item->item_id;
            $stock->quantity = $row['qty']; 
            $stock->save();
        }
    }
}
