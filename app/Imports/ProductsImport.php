<?php

namespace App\Imports;

use App\Models\Product; // Make sure to import your Product model
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Product([
            'title'            => $row['title'],
            'category'         => $row['category'],
            'price'            => $row['price'],
            'serial'           => $row['serial'],
            'certificate'      => $row['certificate'],
            'code_manufactur'  => $row['code_manufactur'],
        ]);
    }
}
