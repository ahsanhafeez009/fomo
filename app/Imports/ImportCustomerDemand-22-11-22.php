<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Company;
use Carbon\Carbon;

class ImportCustomerDemand implements ToCollection
{

    public function collection(Collection $rows)
    {
        $data = array();
        $customer_data = array();
        foreach ($rows as $row) {
            $data[] = array(
                'company_name' => isset($rows[1][0]) ? $rows[1][0] : '',
                'product_name' => isset($row[3]) ? $row[3] : ''
            );
            $customer_data[] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $row[3]);
        }
        unset($data[0]);
        unset($data[1]);
        unset($data[2]);
        unset($data[3]);
        unset($data[4]);
        unset($customer_data[0]);
        unset($customer_data[1]);
        unset($customer_data[2]);
        unset($customer_data[3]);
        unset($customer_data[4]);
        $old_customer_data = array_filter($customer_data);    
        $new_customer_data = array_values($old_customer_data);
        if(count($new_customer_data) <= 300){
            $results = array_values($data);
            $resultant_array = array();
            foreach ($results as $key => $result) {
                if (!empty($result['company_name'] && $result['product_name'])) {
                    $resultant_array[] = $result;
                }
            }
            $company_name            =  $resultant_array[0]['company_name'];
            $resultant_customer_data =  json_encode($new_customer_data);
            DB::table('temp_customer_demand')->insert([
                'company_name'  => $company_name,
                'product_name'  => $resultant_customer_data
            ]);
        }
    }
}
