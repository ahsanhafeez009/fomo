<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\SupplierRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ImportSupplierRecord implements ToCollection
{
    private $data; 

    public function __construct($data)
    {
        $this->data = $data; 
    }
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $value) {
            @$first_heading[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', !empty($value[0]) ? $value[0] : '');
            @$second_heading[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', !empty($value[1]) ? $value[1] : '');
            @$third_heading[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', !empty($value[2]) ? $value[2] : '');
            @$forth_heading[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', !empty($value[3]) ? $value[3] : '');
            @$fifth_heading[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', !empty($value[4]) ? $value[4] : '');
            @$six_heading[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', !empty($value[5]) ? $value[5] : '');
            @$seventh_heading[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', !empty($value[6]) ? $value[6] : '');
            @$eighth_heading[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', !empty($value[7]) ? $value[7] : '');
            @$ninth_heading[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', !empty($value[8]) ? $value[8] : '');
            @$tenth_heading[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', !empty($value[9]) ? $value[9] : '');
            @$eleven_heading[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', !empty($value[10]) ? $value[10] : '');
        }

        if (!empty(@$first_heading[0])) {
            if (strtolower(@$first_heading[0]) == "item id") {
                @$item = @$first_heading;
            }
            if (strtolower(@$second_heading[0]) == "item id") {
                @$item = @$second_heading;
            }
            if (strtolower(@$third_heading[0]) == "item id") {
                @$item = @$third_heading;
            }
            if (strtolower(@$forth_heading[0]) == "item id") {
                @$item = @$forth_heading;
            }
            if (strtolower(@$fifth_heading[0]) == "item id") {
                @$item = @$fifth_heading;
            }
            if (strtolower(@$six_heading[0]) == "item id") {
                @$item = @$six_heading;
            }
            if (strtolower(@$seventh_heading[0]) == "item id") {
                @$item = @$seventh_heading;
            }
            if (strtolower(@$eighth_heading[0]) == "item id") {
                @$item = @$eighth_heading;
            }
            if (strtolower(@$ninth_heading[0]) == "item id") {
                @$item = @$ninth_heading;
            }
            if (strtolower(@$tenth_heading[0]) == "item id") {
                @$item = @$tenth_heading;
            }
            if (strtolower(@$eleven_heading[0]) == "item id") {
                @$item = @$eleven_heading;
            }
        }
        
        if (!empty(@$first_heading[0])) {
            if (strtolower(@$first_heading[0]) == "barcode") {
                @$barcode = @$first_heading;
            }
            if (strtolower(@$second_heading[0]) == "barcode") {
                @$barcode = @$second_heading;
            }
            if (strtolower(@$third_heading[0]) == "barcode") {
                @$barcode = @$third_heading;
            }
            if (strtolower(@$forth_heading[0]) == "barcode") {
                @$barcode = @$forth_heading;
            }
            if (strtolower(@$fifth_heading[0]) == "barcode") {
                @$barcode = @$fifth_heading;
            }
            if (strtolower(@$six_heading[0]) == "barcode") {
                @$barcode = @$six_heading;
            }
            if (strtolower(@$seventh_heading[0]) == "barcode") {
                @$barcode = @$seventh_heading;
            }
            if (strtolower(@$eighth_heading[0]) == "barcode") {
                @$barcode = @$eighth_heading;
            }
            if (strtolower(@$ninth_heading[0]) == "barcode") {
                @$barcode = @$ninth_heading;
            }
            if (strtolower(@$tenth_heading[0]) == "barcode") {
                @$barcode = @$tenth_heading;
            }
            if (strtolower(@$eleven_heading[0]) == "barcode") {
                @$barcode = @$eleven_heading;
            }
        }

        if (!empty(@$first_heading[0])) {
            if (strtolower(@$first_heading[0]) == "brand") {
                @$brand_name = @$first_heading;
            }
            if (strtolower(@$second_heading[0]) == "brand") {
                @$brand_name = @$second_heading;
            }
            if (strtolower(@$third_heading[0]) == "brand") {
                @$brand_name = @$third_heading;
            }
            if (strtolower(@$forth_heading[0]) == "brand") {
                @$brand_name = @$forth_heading;
            }
            if (strtolower(@$fifth_heading[0]) == "brand") {
                @$brand_name = @$fifth_heading;
            }
            if (strtolower(@$six_heading[0]) == "brand") {
                @$brand_name = @$six_heading;
            }
            if (strtolower(@$seventh_heading[0]) == "brand") {
                @$brand_name = @$seventh_heading;
            }
            if (strtolower(@$eighth_heading[0]) == "brand") {
                @$brand_name = @$eighth_heading;
            }
            if (strtolower(@$ninth_heading[0]) == "brand") {
                @$brand_name = @$ninth_heading;
            }
            if (strtolower(@$tenth_heading[0]) == "brand") {
                @$brand_name = @$tenth_heading;
            }
            if (strtolower(@$eleven_heading[0]) == "brand") {
                @$brand_name = @$eleven_heading;
            }
        }

        if (!empty($first_heading[0])) {
            if (strtolower(@$first_heading[0]) == "sub brand") {
                @$sub_brand = @$first_heading;
            }
            if (strtolower(@$second_heading[0]) == "sub brand") {
                @$sub_brand = @$second_heading;
            }
            if (strtolower(@$third_heading[0]) == "sub brand") {
                @$sub_brand = @$third_heading;
            }
            if (strtolower(@$forth_heading[0]) == "sub brand") {
                @$sub_brand = @$forth_heading;
            }
            if (strtolower(@$fifth_heading[0]) == "sub brand") {
                @$sub_brand = @$fifth_heading;
            }
            if (strtolower(@$six_heading[0]) == "sub brand") {
                @$sub_brand = @$six_heading;
            }
            if (strtolower(@$seventh_heading[0]) == "sub brand") {
                @$sub_brand = @$seventh_heading;
            }
            if (strtolower(@$eighth_heading[0]) == "sub brand") {
                @$sub_brand = @$eighth_heading;
            }
            if (strtolower(@$ninth_heading[0]) == "sub brand") {
                @$sub_brand = @$ninth_heading;
            }
            if (strtolower(@$tenth_heading[0]) == "sub brand") {
                @$sub_brand = @$tenth_heading;
            }
            if (strtolower(@$eleven_heading[0]) == "sub brand") {
                @$sub_brand = @$eleven_heading;
            }
        }

        if (!empty($first_heading[0])) {
            if (strtolower(@$first_heading[0]) == "description") {
                @$description = @$first_heading;
            }
            if (strtolower(@$second_heading[0]) == "description") {
                @$description = @$second_heading;
            }
            if (strtolower(@$third_heading[0]) == "description") {
                @$description = @$third_heading;
            }
            if (strtolower(@$forth_heading[0]) == "description") {
                @$description = @$forth_heading;
            }
            if (strtolower(@$fifth_heading[0]) == "description") {
                @$description = @$fifth_heading;
            }
            if (strtolower(@$six_heading[0]) == "description") {
                @$description = @$six_heading;
            }
            if (strtolower(@$seventh_heading[0]) == "description") {
                @$description = @$seventh_heading;
            }
            if (strtolower(@$eighth_heading[0]) == "description") {
                @$description = @$eighth_heading;
            }
            if (strtolower(@$ninth_heading[0]) == "description") {
                @$description = @$ninth_heading;
            }
            if (strtolower(@$tenth_heading[0]) == "description") {
                @$description = @$tenth_heading;
            }
            if (strtolower(@$eleven_heading[0]) == "description") {
                @$description = @$eleven_heading;
            }
        }

        if (!empty($first_heading[0])) {
            if (strtolower(@$first_heading[0]) == "price aed") {
                @$price_aed = @$first_heading;
            }
            if (strtolower(@$second_heading[0]) == "price aed") {
                @$price_aed = @$second_heading;
            }
            if (strtolower(@$third_heading[0]) == "price aed") {
                @$price_aed = @$third_heading;
            }
            if (strtolower(@$forth_heading[0]) == "price aed") {
                @$price_aed = @$forth_heading;
            }
            if (strtolower(@$fifth_heading[0]) == "price aed") {
                @$price_aed = @$fifth_heading;
            }
            if (strtolower(@$six_heading[0]) == "price aed") {
                @$price_aed = @$six_heading;
            }
            if (strtolower(@$seventh_heading[0]) == "price aed") {
                @$price_aed = @$seventh_heading;
            }
            if (strtolower(@$eighth_heading[0]) == "price aed") {
                @$price_aed = @$eighth_heading;
            }
            if (strtolower(@$ninth_heading[0]) == "price aed") {
                @$price_aed = @$ninth_heading;
            }
            if (strtolower(@$tenth_heading[0]) == "price aed") {
                @$price_aed = @$tenth_heading;
            }
            if (strtolower(@$eleven_heading[0]) == "price aed") {
                @$price_aed = @$eleven_heading;
            }
        }

        if (!empty($first_heading[0])) {
            if (strtolower(@$first_heading[0]) == "quantity") {
                @$qty = @$first_heading;
            }
            if (strtolower(@$second_heading[0]) == "quantity") {
                @$qty = @$second_heading;
            }
            if (strtolower(@$third_heading[0]) == "quantity") {
                @$qty = @$third_heading;
            }
            if (strtolower(@$forth_heading[0]) == "quantity") {
                @$qty = @$forth_heading;
            }
            if (strtolower(@$fifth_heading[0]) == "quantity") {
                @$qty = @$fifth_heading;
            }
            if (strtolower(@$six_heading[0]) == "quantity") {
                @$qty = @$six_heading;
            }
            if (strtolower(@$seventh_heading[0]) == "quantity") {
                @$qty = @$seventh_heading;
            }
            if (strtolower(@$eighth_heading[0]) == "quantity") {
                @$qty = @$eighth_heading;
            }
            if (strtolower(@$ninth_heading[0]) == "quantity") {
                @$qty = @$ninth_heading;
            }
            if (strtolower(@$tenth_heading[0]) == "quantity") {
                @$qty = @$tenth_heading;
            }
            if (strtolower(@$eleven_heading[0]) == "quantity") {
                @$qty = @$eleven_heading;
            }
        }
        
        if (!empty($item)) {
            unset($item[0]);
            $new_item = array_values($item);
        }else{
            $new_item = '';
        }

        if (!empty($barcode)) {
            unset($barcode[0]);
            $new_barcode = array_values($barcode);
        }else{
            $new_barcode = '';
        }

        if (!empty($brand_name)) {
            unset($brand_name[0]);
            $new_brand_name = array_values($brand_name);
        }else{
            $new_brand_name = '';
        }

        if (!empty($sub_brand)) {
            unset($sub_brand[0]);
            $new_sub_brand = array_values($sub_brand);
        }else{
            $new_sub_brand = '';
        }

        if (!empty($description)) {
            unset($description[0]);
            $new_description = array_values($description);
        }else{
            $new_description = '';
        }

        if (!empty($price_aed)) {
            unset($price_aed[0]);
            $new_price_aed = array_values($price_aed);
        }else{
            $new_price_aed = '';
        }

        if (!empty($qty)) {
            unset($qty[0]);
            $new_qty = array_values($qty);
        }else{
            $new_qty = '';
        }
        $old_supplier_name  = $this->data;
        $supplier_name = basename($old_supplier_name, ".xlsx");
        foreach ($new_barcode as $key => $barcodes) {
            $file_name_id = DB::table('supplier_details')
            ->select('*')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get();
            $user = Auth::user();
            $added_by = $user->id;
            $supplier_details_id = $file_name_id[0]->id;
            $supplier_name = $supplier_name;
            $result = DB::table('supplier_record')->insert([
                'supplier_details_id' => $supplier_details_id,
                'supplier_name' => $supplier_name,
                'item' => !empty($new_item[$key]) ? $new_item[$key] : '',
                'barcode' => !empty($barcodes) ? $barcodes : '',
                'brand_name' => !empty($new_brand_name[$key]) ? $new_brand_name[$key] : '',
                'sub_brand' => !empty($new_sub_brand[$key]) ? $new_sub_brand[$key] : '',
                'description' => !empty($new_description[$key]) ? $new_description[$key] : '',
                'price_aed' => !empty($new_price_aed[$key]) ? $new_price_aed[$key] : '',
                'qty' => !empty($new_qty[$key]) ? $new_qty[$key] : '',
                'added_by'      => !empty($added_by) ? $added_by : '',
            ]);
        }

    }
}