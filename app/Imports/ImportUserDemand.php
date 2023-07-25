<?php

namespace App\Imports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\UserDemand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ImportUserDemand implements ToCollection
{
    private $data; 

    public function __construct($data)
    {
        $this->data = $data; 
    }
    public function collection(Collection $rows)
    {
        $filename  = $this->data;
        $user = Auth::user();
        $added_by = $user->id;
        @$user_already_exist = DB::table('users_demands_details')->select('*')->where('file_name', $filename)->where('added_by', $added_by)->get();
        $new_user_already_exist = $user_already_exist->count();
        if ($new_user_already_exist == 0) {
            DB::table('users_demands_details')->insert([
                'file_name' => $filename,
                'added_by' => $added_by
            ]);
            $temp_file_name_id = DB::table('users_demands_details')->select('*')->orderBy('id', 'DESC')->limit(1)->get();
            $file_name_id = $temp_file_name_id[0]->id;
        }else{
            $file_name_id = $user_already_exist[0]->id;
        }
        $resultant_array = json_decode(json_encode($rows), true);
        foreach ($resultant_array as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $innerwrap) {
                $new_innerwrap = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
                if (strtolower($new_innerwrap) == "barcode") {
                    $counter = [$key];
                    $headings_used_by[$key][$key1] = 'barcode';
                }

                if (strtolower($new_innerwrap) == "brand") {
                    $headings_used_by[$key][$key1] = 'brand_name';
                }

                if (strtolower($new_innerwrap) == "type") {
                    $headings_used_by[$key][$key1] = 'type';
                }

                if (strtolower($new_innerwrap) == "product name") {
                    $headings_used_by[$key][$key1] = 'product_name';
                }

                if (strtolower($new_innerwrap) == "price") {
                    $headings_used_by[$key][$key1] = 'avg_price_aed';
                }

                if (strtolower($new_innerwrap) == "avg pricing -usd" || strtolower($new_innerwrap) == "avg pricing-usd") {
                    $headings_used_by[$key][$key1] = 'avg_price_usd';
                }

                if (strtolower($new_innerwrap) == "avg pricing -euros" || strtolower($new_innerwrap) == "avg pricing-euros") {
                    $headings_used_by[$key][$key1] = 'avg_price_eur';
                }
            }
        }


        for ($i=0; $i <= $counter[0] ; $i++) {
            unset($resultant_array[$i]);
            $new_resultant_array    = array_values($resultant_array);
            $row   = @$headings_used_by[$i];
            if(!empty($row)){
                @$new_headings_used_by = @$row;
            }
        }

        foreach ($new_resultant_array as $key => $value) {
            foreach ($new_headings_used_by as $key1 => $innerwrap) {
                if ($innerwrap == "barcode") {
                    @$barcodes[$key] = $value[$key1];
                }

                if ($innerwrap == "brand_name") {
                    @$brand_name[$key] = $value[$key1];
                }

                if ($innerwrap == "type") {
                    @$type[$key] = $value[$key1];
                }

                if ($innerwrap == "product_name") {
                    @$product_name[$key] = $value[$key1];
                }

                if ($innerwrap == "avg_price_aed") {
                    @$avg_price_aed[$key] = $value[$key1];
                }

                if ($innerwrap == "avg_price_usd") {
                    @$avg_price_usd[$key] = $value[$key1];
                }

                if ($innerwrap == "avg_price_eur") {
                    @$avg_price_eur[$key] = $value[$key1];
                }
            }
        }

        foreach ($barcodes as $key => $barcode) {
            if (!empty($barcode)) {
                @$barcode_already_exist = DB::table('user_demands')->select('*')->where('barcode', $barcode)->where('added_by', $added_by)->where('file_name_id', $file_name_id)->get();
                $new_barcode_already_exist = $barcode_already_exist->count();
                if ($new_barcode_already_exist == 0) {
                    DB::table('user_demands')->insert([
                        'file_name_id'  => $file_name_id,
                        'brand_name'    => !empty($brand_name[$key]) ? $brand_name[$key] : '',
                        'type'          => !empty($type[$key]) ? $type[$key] : '',
                        'barcode'       => !empty($barcode) ? $barcode : '',
                        'product_name'  => !empty($product_name[$key]) ? $product_name[$key] : '',
                        'avg_price_aed' => !empty($avg_price_aed[$key]) ? $avg_price_aed[$key] : '',
                        'avg_price_usd' => !empty($avg_price_usd[$key]) ? $avg_price_usd[$key] : '',
                        'avg_price_eur' => !empty($avg_price_eur[$key]) ? $avg_price_eur[$key] : '',
                        'added_by'      => !empty($added_by) ? $added_by : '',
                    ]);
                }else{
                    $array = [
                        'avg_price_aed' => !empty($avg_price_aed[$key]) ? $avg_price_aed[$key] : '',
                        'avg_price_usd' => !empty($avg_price_usd[$key]) ? $avg_price_usd[$key] : '',
                        'avg_price_eur' => !empty($avg_price_eur[$key]) ? $avg_price_eur[$key] : ''
                    ];
                    DB::table('user_demands')->where('barcode', $barcode)->where('added_by', $added_by)->where('file_name_id', $file_name_id)->update($array);
                }
            }
        }
    }
}
