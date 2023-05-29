<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Item;
use App\Models\Stock;

class ItemSheetImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
         
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
