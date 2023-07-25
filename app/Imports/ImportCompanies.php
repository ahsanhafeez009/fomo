<?php
namespace App\Imports;
use App\Models\Company;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ImportCompanies implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $data = [];
        foreach ($rows as $key => $row) {
            $data[] = array(
                    'company_name'   => isset($row[0]) ? $row[0] : '',
                    'company_type'   => isset($row[1]) ? $row[1] : '',
                    'status'         => isset($row[2]) ? $row[2] : '',
                    'decoded'        => isset($row[3]) ? $row[3] : '',
                    'active'         => 1,
                    'sale_rept'      => isset($row[4]) ? $row[4] : '',
                    'sale_terms'     => isset($row[5]) ? $row[5] : '',
                    'country'        => isset($row[6]) ? $row[6] : '',
                    'state'          => isset($row[7]) ? $row[7] : '',
                    'city'           => isset($row[8]) ? $row[8] : '',
                    'area'           => isset($row[9]) ? $row[9] : '',
                    'address'        => isset($row[10]) ? $row[10] : '',
                    'website'        => isset($row[11]) ? $row[11] : '',
                    'company_email'  => isset($row[12]) ? $row[12] : '',
                    'company_number' => isset($row[13]) ? $row[13] : '',
                    'contact_person' => isset($row[14]) ? $row[14] : '',
                    'designation'    => isset($row[15]) ? $row[15] : '',
                    'contact_email'  => isset($row[16]) ? $row[16] : '',
                    'contact_number' => isset($row[17]) ? $row[17] : '',
                    'remarks'        => isset($row[18]) ? $row[18] : '',
            );
        }
        unset($data[0]);
        unset($data[1]);
        unset($data[2]);
        $results = array_values($data);
        $resultant_array = array();
        foreach ($results as $key => $result) {
            if (!empty($result['company_name']) && is_string($result['company_name'])) {
                $resultant_array[] = $result;
            }
        }
        foreach ($resultant_array as $key => $value) {
            $user = Auth::user();
            $added_by = $user->id;
            $company_name = Company::where('company_name', 'LIKE', '%' . $value['company_name']. '%')->where('city', 'LIKE', '%' . $value['city'] . '%')->where('added_by', $added_by)->first();
            if ( !empty($company_name)) {
                DB::table('companies')->where('company_name', $value['company_name'])->update($value);
            }else{
                $user = Auth::user();
                $value['added_by'] = $user->id;
                DB::table('companies')->insert($value);
            }
        }
    }
}
