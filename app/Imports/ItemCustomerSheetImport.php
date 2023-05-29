<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class ItemCustomerSheetImport implements WithMultipleSheets
{
    /**
    * @param Collection $collection
    */
    public function sheets(): array
    {
        return [
            'item' => new ItemSheetImport(),
            'customer' => new CustomerSheetImport(),
        ];
    }
}
