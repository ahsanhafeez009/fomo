<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SupplierPriceAnalysisExport;
use App\Models\SupplierRecord;
use App\Models\UserDemand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierPriceAnalysisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function supplier_price_analysis(){
        $title    = 'Supplier Price Analysis';
        $user     = Auth::user();
        $added_by = $user->id;
        $alldata  = UserDemand::select('barcode', 'product_name', 'avg_price_aed', 'user_demand_qty')->where('added_by', $added_by)->orderBy('id', 'ASC')->get();
        
        $count = 0;
        foreach ($alldata as $key => $value) {
            @$result = SupplierRecord::select('price_aed')->where('barcode', $value->barcode)->where('added_by', $added_by)->orderBy('id', 'ASC')->get();
            @$lowest_price[$key] =  json_decode(json_encode($result), true);
        }


        if (is_array(@$lowest_price) || is_object(@$lowest_price)){
            foreach (@$lowest_price as $key => $outerwrap) {
                foreach ($outerwrap as $key1 => $innerwrap) {
                    if(!empty($innerwrap['price_aed'])){
                        $smallest_value[$key][] = $innerwrap['price_aed'];
                    }
                }
            }
        }
        
        if (is_array(@$smallest_value) || is_object(@$smallest_value)){
            foreach (@$smallest_value as $key => $arra) {
                $temp_arra = preg_replace('/[^0-9]/', '', $arra);
                $temp_smallest_value[$key]  = min($temp_arra);
            }
        }

        $count = 0;
        $result_record = [];
        foreach ($alldata as $key => $value) {
            @$result_record[$count] = $value;
            @$result_record[$count]['lowest_price'] = @$temp_smallest_value[$key];
            $count++;
        }
        $suppliers = DB::table('supplier_record')
        ->select('*')
        ->groupBy('supplier_name')
        ->where('added_by', $added_by)
        ->get();
        $data = [
            'title'               => $title,
            'alldata'             => $result_record,
            'suppliers'           => $suppliers
        ];
        return view('admin.supplier_price_analysis.index', $data);
    }

    public function testing_supplier_price_analysis(){
        $title    = 'Supplier Price Analysis';
        $user     = Auth::user();
        $added_by = $user->id;
        $alldata  = UserDemand::select('barcode', 'product_name', 'avg_price_aed', 'user_demand_qty')->where('added_by', $added_by)->orderBy('id', 'ASC')->get();
        
        $count = 0;
        foreach ($alldata as $key => $value) {
            @$result = SupplierRecord::select('price_aed')->where('barcode', $value->barcode)->where('added_by', $added_by)->orderBy('id', 'ASC')->get();
            @$lowest_price[$key] =  json_decode(json_encode($result), true);
        }

        if (is_array(@$lowest_price) || is_object(@$lowest_price)){
            foreach (@$lowest_price as $key => $value) {
                if(!empty(@$value)){
                    $min = min($value);
                    @$smallest_value[$key] = $min;
                }
            }
        }

        $count = 0;
        $result_record = [];
        foreach ($alldata as $key => $value) {
            @$result_record[$count] = $value;
            @$result_record[$count]['lowest_price'] = @$smallest_value[$key];
            $count++;
        }
        $suppliers = DB::table('supplier_record')
        ->select('*')
        ->groupBy('supplier_name')
        ->where('added_by', $added_by)
        ->get();
        $data = [
            'title'               => $title,
            'alldata'             => $result_record,
            'suppliers'           => $suppliers
        ];
        return view('admin.supplier_price_analysis.testing_supplier_price_analysis', $data);
    }
}
