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
        $old_supplier_name  = $this->data;
        $supplier_name = basename($old_supplier_name, ".xlsx");
        $user = Auth::user();
        $added_by = $user->id;
        @$supplier_already_exist = DB::table('supplier_details')->select('*')->where('supplier_name', $supplier_name)->where('added_by', $added_by)->get();
        @$new_supplier_already_exist = $supplier_already_exist->count();
        if (@$new_supplier_already_exist == 0) {
            DB::table('supplier_details')->insert([
                'supplier_name' => $supplier_name,
                'added_by'      => $added_by,
                'created_at'    => date("Y-m-d H:i:s")
            ]);
            $file_name_id = DB::table('supplier_details')->select('*')->orderBy('id', 'DESC')->limit(1)->get();
            @$supplier_details_id = $file_name_id[0]->id;
        }else{
            @$supplier_details_id = $supplier_already_exist[0]->id;
        }

        @$resultant_array = json_decode(json_encode($rows), true);
        foreach (@$resultant_array as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $innerwrap) {
                $new_innerwrap = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
                if (strtolower($new_innerwrap) == "barcode") {
                    @$counter = [$key];
                    $headings_used_by[$key][$key1] = 'barcode';
                }

                if (strtolower($new_innerwrap) == "price") {
                    $headings_used_by[$key][$key1] = 'price';
                }

                if (strtolower($new_innerwrap) == "qty") {
                    $headings_used_by[$key][$key1] = 'qty';
                }
            }
        }

        for ($i=0; $i <= @$counter[0] ; $i++) {
            unset($resultant_array[$i]);
            @$new_resultant_array    = array_values($resultant_array);
            $row   = @$headings_used_by[$i];
            if(!empty($row)){
                @$new_headings_used_by = @$row;
            }
        }

        foreach (@$new_resultant_array as $key => $value) {
            foreach (@$new_headings_used_by as $key1 => $innerwrap) {
                if ($innerwrap == "barcode") {
                    // $matched = preg_replace('/\D/', '', $value[$key1]);
                    @$barcodes[$key] = $value[$key1];
                }

                if ($innerwrap == "qty") {
                    @$qty[$key] = $value[$key1];
                }

                if ($innerwrap == "price") {
                    @$price_aed[$key] = $value[$key1];
                }
            }
        }
        
        if (is_array(@$barcodes) || is_object(@$barcodes)){
            foreach (@$barcodes as $key => $barcode) {
                if (!empty($barcode)) {
                    $new_barcode= str_replace("'","",$barcode);
                    @$barcode_already_exist = DB::table('supplier_record')->select('*')->where('barcode', $new_barcode)->where('added_by', $added_by)->where('supplier_details_id', $supplier_details_id)->first();
                    if (@$barcode_already_exist){
                        $array = [
                            'price_aed'    => !empty(@$price_aed[$key]) ? @$price_aed[$key] : '',
                            'qty'          => !empty(@$qty[$key]) ? @$qty[$key] : '',
                            'updated_at'   => date("Y-m-d H:i:s"),
                        ];
                        DB::table('supplier_record')->where('barcode', $new_barcode)->where('added_by', $added_by)->where('supplier_details_id', $supplier_details_id)->update($array);
                    }else{
                        DB::table('supplier_record')->insert([
                            'supplier_details_id' => $supplier_details_id,
                            'supplier_name'       => $supplier_name,
                            'barcode'             => !empty(@$new_barcode) ? @$new_barcode : '',
                            'price_aed'           => !empty(@$price_aed[$key]) ? @$price_aed[$key] : '',
                            'qty'                 => !empty(@$qty[$key]) ? @$qty[$key] : '',
                            'added_by'            => !empty($added_by) ? $added_by : '',
                            'created_at'          => date("Y-m-d H:i:s"),
                        ]);
                    }
                }
            }
        }
    }
}