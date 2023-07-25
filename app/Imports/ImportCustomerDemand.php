<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ImportCustomerDemand implements ToCollection
{

    public function collection(Collection $rows)
    {
        $data = array();
        $customer_data = array();
        foreach ($rows as $key => $row) {
            $company_name          = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $rows[1][0]);
            $city                  = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $rows[2][0]);
            $barcode[$key]         = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $row[1]);
            $brand_name[$key]      = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $row[2]);
            $customer_data[$key]   = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $row[3]);
            $qty[$key]             = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $row[4]);
            $price[$key]           = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $row[5]);
        }
        unset($barcode[0]);
        unset($barcode[1]);
        unset($barcode[2]);
        unset($barcode[3]);
        unset($barcode[4]);
        unset($brand_name[0]);
        unset($brand_name[1]);
        unset($brand_name[2]);
        unset($brand_name[3]);
        unset($brand_name[4]);
        unset($customer_data[0]);
        unset($customer_data[1]);
        unset($customer_data[2]);
        unset($customer_data[3]);
        unset($customer_data[4]);
        unset($qty[0]);
        unset($qty[1]);
        unset($qty[2]);
        unset($qty[3]);
        unset($qty[4]);
        unset($price[0]);
        unset($price[1]);
        unset($price[2]);
        unset($price[3]);
        unset($price[4]);
        $new_barcode       = array_values($barcode);
        $new_brand_name    = array_values($brand_name);
        $old_customer_data = array_filter($customer_data);    
        $new_customer_data = array_values($old_customer_data);
        $new_qty           = array_values($qty);
        $new_price         = array_values($price);
        
        foreach ($new_customer_data as $key => $customer_data) {
            $temp_customer_data[$key] = $customer_data;
            $temp_qty[$key] = $new_qty[$key];
            $temp_barcode[$key] = $new_barcode[$key];
            $temp_brand_name[$key] = $new_brand_name[$key];
            $temp_price[$key] = $new_price[$key];
        }

        if(count($new_customer_data) <= 300){
            $barcode                 = json_encode($temp_barcode);
            $qty                     = json_encode($temp_qty);
            $price                   = json_encode($temp_price);
            $resultant_customer_data = json_encode($temp_customer_data);
            $resultant_brand_name    = json_encode($temp_brand_name);
            $user = Auth::user();
            $added_by = $user->id;
            DB::table('temp_customer_demand')->insert([
                'company_name'  => $company_name,
                'city'          => $city,
                'barcode'       => $barcode,
                'brand_name'    => $resultant_brand_name,
                'qty'           => $qty,
                'date'          => date("Y-m-d"),
                'price'         => $price,
                'product_name'  => $resultant_customer_data,
                'added_by'      => !empty($added_by) ? $added_by : '',
            ]);
        }
    }
}
