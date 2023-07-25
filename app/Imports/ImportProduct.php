<?php

namespace App\Imports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ImportProduct implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $data = [];
        foreach ($rows as $row) {
            $data[] = array(
                'brand_type'      => isset($row[1]) ? $row[1] : '',
                'brand_name'      => isset($row[2]) ? $row[2] : '',
                'barcode'         => isset($row[3]) ? $row[3] : '',
                'product_name'    => isset($row[4]) ? $row[4] : '',
                'bottle_size'     => isset($row[5]) ? $row[5] : '',
                'product_gender'  => isset($row[6]) ? $row[6] : '',
                'product_type'    => isset($row[7]) ? $row[7] : '',
                'active'          => 1,
            );
        }
        unset($data[0]);
        $results = array_values($data);
        foreach ($results as $key => $result) {
            if (!empty($result['product_name'])) {
                $resultant_array[$key] = $result;
            }
        }
        
        foreach ($resultant_array as $key => $value) {
            $product_names[$key]   = $value['product_name'];
            $products_brands[$key] = $value['brand_name'];
        }

        foreach ($product_names as $key => $product_name) {
            $old_product_names   = str_replace("'", ' ', $product_name);
            $new_product_names[$key] = str_replace("’", ' ', $old_product_names);
        }

        foreach ($resultant_array as $key => $value) {
            $user = Auth::user();
            $added_by = $user->id;
            $value['product_name'] = $new_product_names[$key];
            $product_name = Product::where('product_name', '=', $value['product_name'])->where('brand_name', '=', $products_brands[$key])->where('added_by', $added_by)->get();
            $product_name_count = $product_name->count();
            if($product_name_count == 0) {
                $user = Auth::user();
                $value['added_by'] = $user->id;
                DB::table('products')->insert($value);
            }else{
                DB::table('products')->where('product_name', $value['product_name'])->update($value);
            }
        }
    }
}
