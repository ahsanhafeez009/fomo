<?php

namespace App\Exports;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       return Product::select('brand_type','brand_name','product_name','barcode','bottle_size','product_gender','product_type')->get();
    }

    public function headings(): array
    {
        return ["Brand Type", "Brand Name", "Product Name", "Barcode", "Bottle Size", "Product Gender", "Product Type"];
    }
}
