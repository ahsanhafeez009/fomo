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
            $result_record[$count]['user_demands'] = $value;
            $result_record[$count]['supplier_record'] = SupplierRecord::select('barcode','price_aed', 'qty')->where('barcode', $value->barcode)->where('added_by', $added_by)->orderBy('id', 'ASC')->get();
            $count++;
        }
        echo'<pre/>';
        print_r($result_record);
        exit;
        $suppliers = DB::table('supplier_record')
        ->select('*')
        ->groupBy('supplier_name')
        ->where('added_by', $added_by)
        ->get();
        $data = [
            'title'               => $title,
            'alldata'             => $alldata,
            'suppliers'           => $suppliers
        ];
        return view('admin.supplier_price_analysis.index', $data);
    }
}
