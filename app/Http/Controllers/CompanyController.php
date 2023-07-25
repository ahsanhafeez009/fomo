<?php

namespace App\Http\Controllers;
use App\Models\Company;
use App\Models\Company_Type;
use App\Models\Company_Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\DatatablesServiceProvider;
use App\Imports\ImportCompanies;
use App\Exports\CompaniesExport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index(Request $request){
       if(request()->ajax()){
        if($request->filters==true){
            $data = Company::select('*')->orderBy('id', 'ASC')->where('active', '=','1');
            if (!empty($request->input('company_type')))
                $data->whereIn('company_type', $request->input('company_type', []));
            if (!empty($request->input('status')))
                $data->whereIn('status', $request->input('status', []));
            if (!empty($request->input('fomo_incharge')))
                $data->whereIn('fomo_incharge', $request->input('fomo_incharge', []));
            if (!empty($request->input('payment_terms')))
                $data->whereIn('payment_terms', $request->input('payment_terms', []));
            if (!empty($request->input('country')))
                $data->whereIn('country', $request->input('country', []));
            if (!empty($request->input('state')))
                $data->whereIn('state', $request->input('state', []));
            if (!empty($request->input('city')))
                $data->whereIn('city', $request->input('city', []));
            $data = $data->get();
                return datatables()->of($data)->make(true);
            }else{
               $data = Company::select('*')->orderBy('id', 'ASC')->where('active', 1)->get();
               return datatables()->of($data)->make(true);
              }            
        }
        $title         = 'All Company List';
        $company_type  = Company_Type::select('name')->get();
        $status        = Company_Status::select('status')->get();
        $fomo_incharge = DB::table('company_fomo_incharge')->select('fomo_incharge')->where('fomo_incharge', '!=', Null)->get();
        $payment_terms = DB::table('company_payment_terms')->select('payment_terms')->where('payment_terms', '!=', Null)->get();
        $country       = DB::table('company_country')->select('country')->where('country', '!=', Null)->get();
        $state         = DB::table('company_state')->select('state')->where('state', '!=', Null)->get();
        $city          = DB::table('company_city')->select('city')->where('city', '!=', Null)->get();
        return view('admin.company.companies', compact('title', 'company_type', 'status', 'fomo_incharge', 'payment_terms', 'country', 'state', 'city'));
    }

    public function company_data_cleaner()
    {
        $companies   = DB::table('companies')->select('*')->where('active', '=', 0)->orderBy('id', 'ASC')->get();
        $data = [
           'title'   => 'Pending Company List',
           'companies' => $companies,
        ];
        return view('admin.company.company_data_cleaner', $data);
    }

    public function company_export_excel(){
        return Excel::download(new CompaniesExport, 'companies.xlsx');
    }

    public function approve_company($id)
    {
        $user     = Auth::user();
        $added_by = $user->id;
        $result   = DB::table('companies')->select('*')->where('id', '=', $id)->first();
        DB::table('companies')->insert([
            'company_name'          => $result->company_name,
            'company_type'          => $result->company_type,
            'industry'              => $result->industry,
            'status'                => $result->status,
            'fomo_incharge'         => $result->fomo_incharge,
            'payment_terms'         => $result->payment_terms,
            'trade_license_expiry'  => $result->trade_license_expiry,
            'trn_number'            => $result->trn_number,
            'company_email'         => $result->company_email,
            'company_number'        => $result->company_number,
            'location'              => $result->location,
            'country'               => $result->country,
            'state'                 => $result->state,
            'city'                  => $result->city,
            'address'               => $result->address,
            'remarks'               => $result->remarks,
            'added_by'              => $added_by,
            'active'                => 1,
        ]);
        DB::table('companies')->where('id', $id)->where('added_by', $added_by)->delete();
        return redirect()->back()->with('success-message','Company has been Approved');
    }

    public function company_dash()
    {
        $title   = 'Company Dashboard';
        $data = [
            'title'   => $title
        ];
        return view('admin.company.dashboard',$data);
    }

    public function download_company_sample()
    {
        $file_name = 'Companies.xls';
        $file_url = public_path().'/sample-uploading-files/'. $file_name;
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"".$file_name."\""); 
        readfile($file_url);
    }

    public function create_company()
    {
        $title   = 'Create Company';
        $company_types  = Company_Type::select('name')->get();
        $company_status = Company_Status::select('status')->get();
        $data = [
            'title'            => $title,
            'company_types'    => $company_types,
            'company_status'   => $company_status,
        ];
        return view('admin.company.create_company', $data);
    }

    public function register_company(Request $e)
    {
        $validator = Validator::make($e->all(), [
           'company_name'         => 'required',
           'company_type'         => 'required',
           'industry'             => 'required',
           'status'               => 'required',
           'fomo_incharge'        => 'required',
           'payment_terms'        => 'required',
           'trade_license_expiry' => 'required',
           'trn_number'           => 'required',
           'company_email'        => 'required|email',
           'company_number'       => 'required',
           'location'             => 'required',
           'country'              => 'required',
           'state'                => 'required',
           'city'                 => 'required',
           'address'              => 'required',
           'remarks'              => 'required',
       ]);
        if ($validator->fails()) {
            return redirect()->route('create-company')->with('error-message','Please Fill Fields');
        }else {
            $company_name = Company::where('company_name', '=', $e->company_name)->first();
        if ($company_name === null) {
            $user = Auth::user();
            $added_by = $user->id;
            $companies                       = new Company();
            $companies->company_name         = $e->company_name;
            $companies->company_type         = $e->company_type;
            $companies->industry             = $e->industry;
            $companies->status               = $e->status;
            $companies->fomo_incharge        = $e->fomo_incharge;
            $companies->payment_terms        = $e->payment_terms;
            $companies->trade_license_expiry = $e->trade_license_expiry;
            $companies->trn_number           = $e->trn_number;
            $companies->company_email        = $e->company_email;
            $companies->company_number       = $e->company_number;
            $companies->location             = $e->location;
            $companies->country              = $e->country;
            $companies->state                = $e->state;
            $companies->city                 = $e->city;
            $companies->address              = $e->address;
            $companies->remarks              = $e->remarks;
            $companies->active               = 1;
            $companies->added_by             = $added_by;
            $companies->updated_at           = date("Y-m-d h:i:s");
            $companies->created_at           = date("Y-m-d h:i:s");
            $companies->save();
            return redirect()->route('create-company')->with('success-message','Company has been Created');
        }else{
            return redirect()->route('create-company')->with('error-message','Company Already Exist Company has not Created');
        }
        }
    }

    public function upload_bulk_companies()
    {
        $title = 'All Companies File List';
        $data = [
            'title' => $title,
        ];
        return view('admin.company.index', $data);
    }

    public function import_companies(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:xls,xlsx'
        ]);
        $the_file = $request->file('file');
        try{
            $reader       = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(TRUE);
            $spreadsheet  = $reader->load($the_file->getRealPath());
            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range( 1, $row_limit );
            $column_range = range( 'Z', $column_limit );
            $startcount = 1;
            $data = array();

            foreach ( $row_range as $row ) {
                $data[] = [
                   'A' =>$sheet->getCell( 'A' . $row )->getValue(),
                   'B' =>$sheet->getCell( 'B' . $row )->getValue(),
                   'C' =>$sheet->getCell( 'C' . $row )->getValue(),
                   'D' =>$sheet->getCell( 'D' . $row )->getValue(),
                   'E' =>$sheet->getCell( 'E' . $row )->getValue(),
                   'F' =>$sheet->getCell( 'F' . $row )->getValue(),
                   'G' =>$sheet->getCell( 'G' . $row )->getValue(),
                   'H' =>$sheet->getCell( 'H' . $row )->getValue(),
                   'I' =>$sheet->getCell( 'I' . $row )->getValue(),
                   'J' =>$sheet->getCell( 'J' . $row )->getValue(),
                   'K' =>$sheet->getCell( 'K' . $row )->getValue(),
                   'L' =>$sheet->getCell( 'L' . $row )->getValue(),
                   'M' =>$sheet->getCell( 'M' . $row )->getValue(),
                   'N' =>$sheet->getCell( 'N' . $row )->getValue(),
                   'O' =>$sheet->getCell( 'O' . $row )->getValue(),
                   'P' =>$sheet->getCell( 'P' . $row )->getValue(),
                   'Q' =>$sheet->getCell( 'Q' . $row )->getValue(),
                   'R' =>$sheet->getCell( 'R' . $row )->getValue(),
                   'S' =>$sheet->getCell( 'S' . $row )->getValue(),
                   'T' =>$sheet->getCell( 'T' . $row )->getValue(),
                   'U' =>$sheet->getCell( 'U' . $row )->getValue(),
                   'V' =>$sheet->getCell( 'V' . $row )->getValue(),
                   'W' =>$sheet->getCell( 'W' . $row )->getValue(),
                   'X' =>$sheet->getCell( 'X' . $row )->getValue(),
                   'Y' =>$sheet->getCell( 'Y' . $row )->getValue(),
                   'Z' =>$sheet->getCell( 'Z' . $row )->getValue(),
               ];
               $startcount++;
            }
            $filename  = $request->file('file')->getClientOriginalName();
            $this->import_companies_to_db($data, $filename);
        } catch (Exception $e) {
            $error_code = $e->errorInfo[1];
            return back()->withErrors('There was a problem uploading the data!');
        }
    }

    function import_companies_to_db($resultant_array, $filename){
        foreach (@$resultant_array as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $innerwrap) {
                $new_innerwrap = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
                if (strtolower($new_innerwrap) == "customer name") {
                    @$counter = [$key];
                    $headings_used_by[$key][$key1] = 'customer name';
                }

                if (strtolower($new_innerwrap) == "customer type") {
                    $headings_used_by[$key][$key1] = 'customer type';
                }

                if (strtolower($new_innerwrap) == "industry") {
                    $headings_used_by[$key][$key1] = 'industry';
                }

                if (strtolower($new_innerwrap) == "status") {
                    $headings_used_by[$key][$key1] = 'status';
                }

                if (strtolower($new_innerwrap) == "fomo incharge") {
                    $headings_used_by[$key][$key1] = 'fomo incharge';
                }

                if (strtolower($new_innerwrap) == "payment terms") {
                    $headings_used_by[$key][$key1] = 'payment terms';
                }

                if (strtolower($new_innerwrap) == "trade license expiry") {
                    $headings_used_by[$key][$key1] = 'trade license expiry';
                }

                if (strtolower($new_innerwrap) == "trn number") {
                    $headings_used_by[$key][$key1] = 'trn number';
                }

                if (strtolower($new_innerwrap) == "company email") {
                    $headings_used_by[$key][$key1] = 'company email';
                }

                if (strtolower($new_innerwrap) == "company phone number") {
                    $headings_used_by[$key][$key1] = 'company phone number';
                }

                if (strtolower($new_innerwrap) == "location") {
                    $headings_used_by[$key][$key1] = 'location';
                }

                if (strtolower($new_innerwrap) == "country") {
                    $headings_used_by[$key][$key1] = 'country';
                }

                if (strtolower($new_innerwrap) == "state") {
                    $headings_used_by[$key][$key1] = 'state';
                }

                if (strtolower($new_innerwrap) == "city") {
                    $headings_used_by[$key][$key1] = 'city';
                }

                if (strtolower($new_innerwrap) == "address") {
                    $headings_used_by[$key][$key1] = 'address';
                }

                if (strtolower($new_innerwrap) == "remarks") {
                    $headings_used_by[$key][$key1] = 'remarks';
                }
            }
        }

        for ($i=0; $i <= @$counter[0] ; $i++) {
            unset($resultant_array[$i]);
            @$new_resultant_array = array_values($resultant_array);
            $row = @$headings_used_by[$i];
            if(!empty($row)){
                @$new_headings_used_by = @$row;
            }
        }

        foreach (@$new_resultant_array as $key => $value) {
            foreach (@$new_headings_used_by as $key1 => $innerwrap) {
                if ($innerwrap == "customer name") {
                    $old_company_name    = str_replace("'", ' ', $value[$key1]);
                    $temp_company_name   = str_replace("’", ' ', $old_company_name);
                    @$company_names[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $temp_company_name);
                }

                if ($innerwrap == "customer type") {
                    @$company_type[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }

                if ($innerwrap == "industry") {
                    @$industry[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }

                if ($innerwrap == "status") {
                    @$status[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }

                if ($innerwrap == "fomo incharge") {
                    @$fomo_incharge[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }

                if ($innerwrap == "payment terms") {
                    @$payment_terms[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }

                if ($innerwrap == "trade license expiry") {
                    @$trade_license_expiry[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }

                if ($innerwrap == "trn number") {
                    @$trn_number[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }

                if ($innerwrap == "company email") {
                    @$company_email[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }

                if ($innerwrap == "company phone number") {
                    @$company_number[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }

                if ($innerwrap == "location") {
                    @$location[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }

                if ($innerwrap == "country") {
                    @$country[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }

                if ($innerwrap == "state") {
                    @$state[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }
                
                if ($innerwrap == "city") {
                    @$city[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }

                if ($innerwrap == "address") {
                    @$address[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }

                if ($innerwrap == "remarks") {
                    @$remarks[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[$key1]);
                }
            }
        }
       
       for ($i=0; $i < 1; $i++) {
            $user = Auth::user();
            $added_by = $user->id;
           $result = DB::table('temp_companies')->insert([
               'filename'             => json_encode(@$filename),
               'company_name'         => json_encode(@$company_names),
               'company_type'         => json_encode(@$company_type),
               'industry'             => json_encode(@$industry),
               'status'               => json_encode(@$status),
               'fomo_incharge'        => json_encode(@$fomo_incharge),
               'payment_terms'        => json_encode(@$payment_terms),
               'trade_license_expiry' => json_encode(@$trade_license_expiry),
               'trn_number'           => json_encode(@$trn_number),
               'company_email'        => json_encode(@$company_email),
               'company_number'       => json_encode(@$company_number),
               'location'             => json_encode(@$location),
               'country'              => json_encode(@$country),
               'state'                => json_encode(@$state),
               'city'                 => json_encode(@$city),
               'address'              => json_encode(@$address),
               'remarks'              => json_encode(@$remarks),
               'active'               => 1,
               'added_by'             => !empty($added_by) ? $added_by : '',
               'created_at'           => date("Y-m-d H:i:s"),
           ]);
       }
       $arr = array('message' => 'Companies are Imported Successfully', 'title' => 'Successfully', 'status' => true);
       echo json_encode($arr);
       exit;
    }

    public function get_all_companies(Request $e)
    {
        $user = Auth::user();
        $added_by = $user->id;
        $users_demands_details = DB::table('temp_companies')->select('*')->where('added_by', $added_by)->get();
        ob_start(); ?>
                   <?php 
                   $i=1;
                   if (count($users_demands_details)>0) {
                   foreach ($users_demands_details as $key => $e) { ?>
                   <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $e->filename; ?></td>
                        <td><?php echo $e->created_at; ?></td>
                        <td class="btn-group" style="display:flex;">
                            <a href="javascript:void(0)" data-file_name_id="<?php echo $e->id; ?>" class="btn btn-info btn-xs" onclick="accept_company_file(<?php echo $e->id; ?>)">
                               <i class='fa fa-check'></i>
                            </a>
                            <a href="javascript:void(0)" data-file_name_id="<?php echo $e->id; ?>" class="btn btn-danger btn-xs" onclick="delete_company_file(<?php echo $e->id; ?>)">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                 <?php $i++;} }else{ ?>
                  <tr>
                      <td colspan="6" style="text-align: center;box-shadow: inset 0 0 0 9999px rgb(0 0 0 / 5%);">
                            No data available in table
                      </td>
                  </tr>
               <?php } ?>
        <?php 
        $res = ob_get_clean();
        echo json_encode(array('html' => $res));
        exit;
    }

    public function view_company($id)
    {
       $cdetail = Company::find($id);
       $data = [
            'title'    => $cdetail->company_name,
            'heading'  => $cdetail->company_name,
            'cdetail'  => $cdetail,
        ];
        return view('admin.company.company_detail', $data);
    }

    public function view_temp_company($id)
    {
       $cdetail   = DB::table('companies')->select('*')->where('id', '=', $id)->first();
       $data = [
            'title'    => $cdetail->company_name,
            'heading'  => $cdetail->company_name,
            'cdetail'  => $cdetail,
        ];
        return view('admin.company.company_detail', $data);
    }

    public function delete_company($id)
    {
        DB::table('companies')->where('id', $id)->delete();
        return redirect()->route('company-list')->with('warning-message','Company Deleted Successfully');
    }

    public function accept_company_file(Request $e)
    {
        $id = $e->data['id'];
        $cdetail = DB::table('temp_companies')->select('*')->where('id', '=', $id)->first();
        $company_names = json_decode(@$cdetail->company_name, true);
        $company_type = json_decode(@$cdetail->company_type, true);
        $industry      = json_decode(@$cdetail->industry, true);
        $status = json_decode(@$cdetail->status, true);
        $fomo_incharge = json_decode(@$cdetail->fomo_incharge, true);
        $payment_terms = json_decode(@$cdetail->payment_terms, true);
        $trade_license_expiry = json_decode(@$cdetail->trade_license_expiry, true);
        $trn_number = json_decode(@$cdetail->trn_number, true);
        $company_email = json_decode(@$cdetail->company_email, true);
        $company_number = json_decode(@$cdetail->company_number, true);
        $location = json_decode(@$cdetail->location, true);
        $country = json_decode(@$cdetail->country, true);
        $state = json_decode(@$cdetail->state, true);
        $city = json_decode(@$cdetail->city, true);
        $address = json_decode(@$cdetail->address, true);
        $remarks = json_decode(@$cdetail->remarks, true);
        $added_by = json_decode(@$cdetail->added_by, true);
        foreach (@$company_names as $key => $company_name) {
            if (!empty($company_name)) {
                $user = Auth::user();
                $added_by = $user->id;
                $already_company_name = Company::where('company_name', 'LIKE', '%' . @$company_name. '%')->where('city', 'LIKE', '%' . @$city[$key] . '%')->get();
                $company_name_count = $already_company_name->count();
                if($company_name_count == 0) {
                    $user = Auth::user();
                    $added_by = $user->id;
                    DB::table('companies')->insert([
                        'company_name'         => !empty(@$company_name) ? @$company_name : '',
                        'company_type'         => !empty(@$company_type[$key]) ? @$company_type[$key] : '',
                        'industry'             => !empty(@$industry[$key]) ? @$industry[$key] : '',
                        'status'               => !empty(@$status[$key]) ? @$status[$key] : '',
                        'fomo_incharge'        => !empty(@$fomo_incharge[$key]) ? @$fomo_incharge[$key] : '',
                        'payment_terms'        => !empty(@$payment_terms[$key]) ? @$payment_terms[$key] : '',
                        'trade_license_expiry' => !empty(@$trade_license_expiry[$key]) ? @$trade_license_expiry[$key] : '',
                        'trn_number'           => !empty(@$trn_number[$key]) ? @$trn_number[$key] : '',
                        'company_email'        => !empty(@$company_email[$key]) ? @$company_email[$key] : '',
                        'company_number'       => !empty(@$company_number[$key]) ? @$company_number[$key] : '',
                        'location'             => !empty(@$location[$key]) ? @$location[$key] : '',
                        'country'              => !empty(@$country[$key]) ? @$country[$key] : '',
                        'state'                => !empty(@$state[$key]) ? @$state[$key] : '',
                        'city'                 => !empty(@$city[$key]) ? @$city[$key] : '',
                        'address'              => !empty(@$address[$key]) ? @$address[$key] : '',
                        'remarks'              => !empty(@$remarks[$key]) ? @$remarks[$key] : '',
                        'active'               => 1,
                        'added_by'             => !empty($added_by) ? $added_by : '',
                        'created_at'           => date("Y-m-d H:i:s"),
                    ]);
                }else{
                    $update_product_data_array = [
                        'updated_at'     => date("Y-m-d H:i:s"),
                    ];
                    DB::table('companies')->where('company_name', @$company_name)->update($update_product_data_array);
                }
            }
        }
        DB::table('temp_companies')->where('id', $id)->delete();
        $result = array('result' => true, 'message' => "Company Approved Successfully");
        echo json_encode($result);
        exit;
    }

    public function delete_temp_company($id)
    {
        DB::table('companies')->where('id', $id)->delete();
        return redirect()->route('company-data-cleaner')->with('warning-message','Company Deleted Successfully');
    }

    public function delete_company_file(Request $e)
    {
        $id = $e->data['id'];
        DB::table('temp_companies')->where('id', $id)->delete();
        $result = array('result' => true, 'message' => "Company File Deleted Successfully");
        echo json_encode($result);
        exit;
    }

    public function edit_company($id)
     {
        $company_types  = Company_Type::select('name')->get();
        $company_status = Company_Status::select('status')->get();
        $result = Company::find($id);
        $data = [
            'title'            => $result->company_name,
            'cdetail'          => Company::find($id),
            'company_types'    => $company_types,
            'company_statuses' => $company_status,
        ];
        return view('admin.company.edit_company', $data);
    }

    public function edit_temp_company($id)
     {
        $company_types  = Company_Type::select('name')->get();
        $company_status = Company_Status::select('status')->get();
        $result = DB::table('companies')->select('*')->where('id', '=', $id)->first();
        $data = [
            'title'            => $result->company_name,
            'cdetail'          => DB::table('companies')->select('*')->where('id', '=', $id)->first(),
            'company_types'    => $company_types,
            'company_statuses' => $company_status,
        ];
        return view('admin.company.edit_temp_company', $data);
    }

    public function update_company(Request $e)
    {
        $array = [
            'company_name'          => $e->company_name,
            'company_type'          => $e->company_type,
            'industry'              => $e->industry,
            'status'                => $e->status,
            'fomo_incharge'         => $e->fomo_incharge,
            'payment_terms'         => $e->payment_terms,
            'trade_license_expiry'  => $e->trade_license_expiry,
            'trn_number'            => $e->trn_number,
            'company_email'         => $e->company_email,
            'company_number'        => $e->company_number,
            'location'              => $e->location,
            'country'               => $e->country,
            'state'                 => $e->state,
            'city'                  => $e->city,
            'address'               => $e->address,
            'remarks'               => $e->remarks,
        ];
        DB::table('companies')->where('id', $e->id)->update($array);
        return redirect()->back()->with('success-message','Company updated successfully');
    }

    public function update_temp_company(Request $e)
    {
        $array = [
            'company_name'          => $e->company_name,
            'company_type'          => $e->company_type,
            'industry'              => $e->industry,
            'status'                => $e->status,
            'fomo_incharge'         => $e->fomo_incharge,
            'payment_terms'         => $e->payment_terms,
            'trade_license_expiry'  => $e->trade_license_expiry,
            'trn_number'            => $e->trn_number,
            'company_email'         => $e->company_email,
            'company_number'        => $e->company_number,
            'location'              => $e->location,
            'country'               => $e->country,
            'state'                 => $e->state,
            'city'                  => $e->city,
            'address'               => $e->address,
            'remarks'               => $e->remarks,
        ];
        DB::table('companies')->where('id', $e->id)->update($array);
        return redirect()->back()->with('success-message','Company updated successfully');
    }
}
