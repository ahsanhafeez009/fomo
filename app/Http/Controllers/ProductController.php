<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Yajra\Datatables\DatatablesServiceProvider;
use App\Models\Product;
use App\Models\Brands;
use App\Models\ProductGender;
use App\Imports\ImportProduct;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function products(Request $request)
    {
       if(request()->ajax()){
        if($request->filters==true){
                $data = Product::select('*')->where('active', '=', '1');
                if (!empty($request->input('brand_types')))
                    $data->whereIn('brand_type', $request->input('brand_types', []));
                if (!empty($request->input('brand_names')))
                    $data->whereIn('brand_name', $request->input('brand_names', []));
                if (!empty($request->input('bottle_sizes')))
                    $data->whereIn('bottle_size', $request->input('bottle_sizes', []));
                if (!empty($request->input('product_genders')))
                    $data->whereIn('product_gender', $request->input('product_genders', []));
                if (!empty($request->input('product_types')))
                    $data->whereIn('product_type', $request->input('product_types', []));
                $data = $data->get();
                return datatables()->of($data)->make(true);
            }else{
               $data = DB::table('products')->select('*')->orderBy('bottle_size', 'ASC')->where('active', '=', '1')->get();
               return datatables()->of($data)->make(true);
              }            
        }
        $title          =  'All Product List';
        $brand_type     = Product::select('brand_type')->groupBy('brand_type')->where('brand_type', '!=', Null)->get();
        $brand_name     = Brands::all();
        $bottle_size    = Product::select('bottle_size')->groupBy('bottle_size')->where('bottle_size', '!=', Null)->get();
        $product_gender = ProductGender::select('gender')->groupBy('gender')->where('gender', '!=', Null)->get();
        $product_type   = Product::select('product_type')->groupBy('product_type')->where('product_type', '!=', Null)->get();
        return view('admin.product.products', compact('title', 'brand_type', 'brand_name', 'bottle_size', 'product_gender', 'product_type'));
    }

    public function brands_list()
    {
        $data = [
            'title'   => 'All Brands List',
            'brands'  => Brands::all(),
        ];
        return view('admin.brands_list.index', $data);
    }

    public function product_export_excel(){
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function upload_bulk_products()
    {
        $title = 'All Products File List';
        $data = [
            'title' => $title,
        ];
        return view('admin.product.index', $data);
    }

    public function view_product($id)
    {
       $cdetail =  Product::find($id);
       $data = [
            'title'    => $cdetail->product_name,
            'heading'  => $cdetail->product_name,
            'cdetail'  => $cdetail,
        ];
        return view('admin.product.product_detail', $data);
    }

    public function delete_product_file(Request $e)
    {
        $id = $e->data['id'];
        DB::table('temp_products')->where('id', $id)->delete();
        $result = array('result' => true, 'message' => "Company File Deleted Successfully");
        echo json_encode($result);
        exit;
    }

    public function accept_product_file(Request $e)
    {
        $id = $e->data['id'];
        $cdetail = DB::table('temp_products')->select('*')->where('id', '=', $id)->first();
        $brand_type     = json_decode($cdetail->brand_type, true);
        $brand_name     = json_decode($cdetail->brand_name, true);
        $product_names  = json_decode($cdetail->product_name, true);
        $barcode        = json_decode($cdetail->barcode, true);
        $bottle_size    = json_decode($cdetail->bottle_size, true);
        $product_gender = json_decode($cdetail->product_gender, true);
        $product_type   = json_decode($cdetail->product_type, true);
        foreach (@$product_names as $key => $product_name) {
            if (!empty($product_name)) {
                $user = Auth::user();
                $added_by = $user->id;
                $already_product_name = Product::where('product_name', '=', $product_name)->where('brand_name', '=', @$brand_names[$key])->get();
                $product_name_count = $already_product_name->count();
                if($product_name_count == 0) {
                    $user = Auth::user();
                    $added_by = $user->id;
                    DB::table('products')->insert([
                        'barcode'        => !empty(@$barcode[$key]) ? @$barcode[$key] : '',
                        'brand_type'     => !empty(@$brand_type[$key]) ? @$brand_type[$key] : '',
                        'brand_name'     => !empty(@$brand_name[$key]) ? @$brand_name[$key] : '',
                        'product_name'   => !empty(@$product_name) ? @$product_name : '',
                        'bottle_size'    => !empty(@$bottle_size[$key]) ? @$bottle_size[$key] : '',
                        'product_gender' => !empty(@$product_gender[$key]) ? @$product_gender[$key] : '',
                        'product_type'   => !empty(@$product_type[$key]) ? @$product_type[$key] : '',
                        'active'         => 1,
                        'added_by'       => !empty($added_by) ? $added_by : '',
                        'created_at'     => date("Y-m-d H:i:s"),
                    ]);
                }else{
                    $update_product_data_array = [
                        'brand_type'     => !empty(@$brand_type[$key]) ? @$brand_type[$key] : '',
                        'bottle_size'    => !empty(@$bottle_size[$key]) ? @$bottle_size[$key] : '',
                        'product_gender' => !empty(@$product_gender[$key]) ? @$product_gender[$key] : '',
                        'product_type'   => !empty(@$product_type[$key]) ? @$product_type[$key] : '',
                        'updated_at'     => date("Y-m-d H:i:s"),
                    ];
                    DB::table('products')->where('product_name', @$product_name)->where('brand_name', @$brand_names[$key])->update($update_product_data_array);
                }
            }
        }
        DB::table('temp_products')->where('id', $id)->delete();
        $result = array('result' => true, 'message' => "Product Approved Successfully");
        echo json_encode($result);
        exit;
    }

    public function get_all_products()
    {
       $user = Auth::user();
       $added_by = $user->id;
       $users_demands_details = DB::table('temp_products')->select('*')->where('added_by', $added_by)->get();
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
                           <a href="javascript:void(0)" data-file_name_id="<?php echo $e->id; ?>" class="btn btn-info btn-xs" onclick="accept_product_file(<?php echo $e->id; ?>)">
                              <i class='fa fa-check'></i>
                           </a>
                           <a href="javascript:void(0)" data-file_name_id="<?php echo $e->id; ?>" class="btn btn-danger btn-xs" onclick="delete_product_file(<?php echo $e->id; ?>)">
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

    public function view_temp_product($id)
    {
        $user     = Auth::user();
        $added_by = $user->id;
        $cdetail   = DB::table('products')->select('*')->where('id', '=', $id)->first();
        $data = [
            'title'    => $cdetail->product_name,
            'heading'  => $cdetail->product_name,
            'cdetail'  => $cdetail,
        ];
        return view('admin.product.product_detail', $data);
    }

    public function product_data_cleaner()
    {
        $user                 = Auth::user();
        $added_by             = $user->id;
        $data = [
            'title'    => 'Pending Products List',
            'products' => DB::table('products')->select('*')->where('active', '=', 0)->where('added_by', '=', $added_by)->get(),
        ];
        return view('admin.product.product_data_cleaner', $data);
    }

    public function download_product_sample()
     {
        $file_name = 'products.xls';
        $file_url = public_path().'/sample-uploading-files/'. $file_name;
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"".$file_name."\""); 
        readfile($file_url);
     }

    public function product_dash()
    {
        $data = [
            'title'    => 'Product Dashboard',
        ];
        return view('admin.product.dashboard', $data);
    }

    public function create_product()
    {
        $brand_type = DB::table('products')
                         ->select('brand_type')
                         ->groupBy('brand_type')
                         ->where('brand_type', '!=', Null)
                         ->get();
        $brand_name     = Brands::all();
        $product_gender = ProductGender::select('gender')->groupBy('gender')->where('gender', '!=', Null)->get();
        $product_type   = DB::table('products')
                         ->select('product_type')
                         ->groupBy('product_type')
                         ->where('product_type', '!=', Null)
                         ->get();
        $data = [
            'title'          => 'Create a Product',
            'brand_type'     => $brand_type,
            'brand_name'     => $brand_name,
            'product_gender' => $product_gender,
            'product_type'   => $product_type,
        ];
        return view('admin.product.create_product', $data);
    }

    public function edit_product($id)
    {
        $brand_type = DB::table('products')
                         ->select('brand_type')
                         ->groupBy('brand_type')
                         ->where('brand_type', '!=', Null)
                         ->get();
        $brand_name     = Brands::all();
        $product_gender = ProductGender::select('gender')->groupBy('gender')->where('gender', '!=', Null)->get();
        $product_type = DB::table('products')
                         ->select('product_type')
                         ->groupBy('product_type')
                         ->where('product_type', '!=', Null)
                         ->get();
        $data = [
            'id'         => $id,
            'title'      => 'Edit Product',
            'product'    => Product::find($id),
            'brand_type'     => $brand_type,
            'brand_name'     => $brand_name,
            'product_gender' => $product_gender,
            'product_type'   => $product_type,
        ];
        return view('admin.product.edit_product', $data);
    }

    public function edit_temp_product($id)
    {
        $brand_type     = DB::table('products')->select('brand_type')->groupBy('brand_type')->where('brand_type', '!=', Null)->get();
        $brand_name     = Brands::all();
        $product_gender = ProductGender::select('gender')->groupBy('gender')->where('gender', '!=', Null)->get();
        $product_type   = DB::table('products')->select('product_type')->groupBy('product_type')->where('product_type', '!=', Null)->get();
        $data = [
            'id'             => $id,
            'title'          => 'Edit Product',
            'product'        => DB::table('products')->select('*')->where('id', '=', $id)->first(),
            'brand_type'     => $brand_type,
            'brand_name'     => $brand_name,
            'product_gender' => $product_gender,
            'product_type'   => $product_type,
        ];
        return view('admin.product.edit_temp_product', $data);
    }

    public function product_detail($id)
    {
        $product = Product::find($id);
        $data = [
            'title'   => 'Product',
            'product' => $product,
        ];
        return view('admin.product.product_detail', $data);
    }

    public function save_product(Request $e)
    {
        $user = Auth::user();
        $added_by = $user->id;
        $e->validate([
           'brand_type'     => 'required',
           'brand_name'     => 'required',
           'product_name'   => 'required',
           'product_type'   => 'required',
        ]);
        $product_name = Product::where('product_name', '=', $e->product_name)->where('added_by', $added_by)->first();
        if ($product_name === null) {
        $product                 = new Product();
        $product->brand_type     = $e->brand_type;
        $product->brand_name     = $e->brand_name;
        $product->product_name   = $e->product_name;
        $product->barcode        = $e->barcode;
        $product->bottle_size    = $e->bottle_size;
        $product->product_gender = $e->product_gender;
        $product->product_type   = $e->product_type;
        $product->active         = 1;
        $product->added_by       = $added_by;
        $product->save();
            return redirect()->route('create-product')->with('success-message','Product has been created');
        }else{
            return redirect()->route('create-product')->with('error-message','Product Already Exist Product has not Created');
        }

    }

    public function import_products(Request $request)
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
                   'B' => $sheet->getCell( 'B' . $row )->getValue(),
                   'C' => $sheet->getCell( 'C' . $row )->getValue(),
                   'D' => $sheet->getCell( 'D' . $row )->getValue(),
                   'E' => $sheet->getCell( 'E' . $row )->getValue(),
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
            $this->import_products_to_db($data, $filename);
            return redirect()->route('create-product')->with('success-message','Products are Imported Successfully');
        } catch (Exception $e) {
            $error_code = $e->errorInfo[1];
            return back()->withErrors('There was a problem uploading the data!');
        }
    }

    function import_products_to_db($resultant_array, $filename){
        foreach (@$resultant_array as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $innerwrap) {
                $new_innerwrap = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
                if (strtolower($new_innerwrap) == "brand type") {
                    $headings_used_by[$key][$key1] = 'brand type';
                }

                if (strtolower($new_innerwrap) == "brand name") {
                    $headings_used_by[$key][$key1] = 'brand name';
                }

                if (strtolower($new_innerwrap) == "barcode" || strtolower($new_innerwrap) == "barcode (ean code)") {
                    $headings_used_by[$key][$key1] = 'barcode';
                }

                if (strtolower($new_innerwrap) == "product name") {
                    @$counter = [$key];
                    $headings_used_by[$key][$key1] = 'product name';
                }

                if (strtolower($new_innerwrap) == "bottle size") {
                    $headings_used_by[$key][$key1] = 'bottle size';
                }

                if (strtolower($new_innerwrap) == "product gender") {
                    $headings_used_by[$key][$key1] = 'product gender';
                }

                if (strtolower($new_innerwrap) == "product type") {
                    $headings_used_by[$key][$key1] = 'product type';
                }
            }
        }

        for ($i=0; $i <= @$counter[0] ; $i++) {
            unset($resultant_array[$i]);
            @$new_resultant_array    = array_values($resultant_array);
            $row = @$headings_used_by[$i];
            if(!empty($row)){
                @$new_headings_used_by = @$row;
            }
        }

        foreach (@$new_resultant_array as $key => $value) {
            foreach (@$new_headings_used_by as $key1 => $innerwrap) {
                if ($innerwrap == "barcode") {
                    $old_barcodes= str_replace("'","",$value[$key1]);
                    @$barcodes[$key] = $old_barcodes;
                }

                if ($innerwrap == "brand type") {
                    @$brand_types[$key] = $value[$key1];
                }

                if ($innerwrap == "brand name") {
                    @$brand_names[$key] = $value[$key1];
                }

                if ($innerwrap == "product name") {
                    @$old_product_names   = str_replace("'", ' ', $value[$key1]);
                    @$product_names[$key] = str_replace("’", ' ', $old_product_names);
                }

                if ($innerwrap == "bottle size") {
                    @$bottle_sizes[$key] = $value[$key1];
                }

                if ($innerwrap == "product gender") {
                    @$product_genders[$key] = $value[$key1];
                }

                if ($innerwrap == "product type") {
                    @$product_types[$key] = $value[$key1];
                }
            }
        }

        for ($i=0; $i < 1; $i++) {
             $user = Auth::user();
             $added_by = $user->id;
            $result = DB::table('temp_products')->insert([
                'filename'        => json_encode(@$filename),
                'brand_type'      => json_encode(@$brand_types),
                'brand_name'      => json_encode(@$brand_names),
                'product_name'    => json_encode(@$product_names),
                'barcode'         => json_encode(@$barcodes),
                'bottle_size'     => json_encode(@$bottle_sizes),
                'product_gender'  => json_encode(@$product_genders),
                'product_type'    => json_encode(@$product_types),
                'added_by'        => !empty($added_by) ? $added_by : '',
                'created_at'      => date("Y-m-d H:i:s"),
            ]);
        }
        $arr = array('message' => 'Products are Imported Successfully', 'title' => 'Successfully', 'status' => true);
        echo json_encode($arr);
        exit;
    }

    public function approve_product($id)
    {
        $user     = Auth::user();
        $added_by = $user->id;
        $result   = DB::table('products')->select('*')->where('id', '=', $id)->first();
        $if_check_extra_text = $this->check_extra_text($result->product_name);
        if (!empty($result->brand_name)) {
           $brand_name_to_update = $result->brand_name;
        }else{
           $brand_name_to_update = $if_check_extra_text['final_brand_names'];            
        }

        if (!empty($result->bottle_size)) {
           $product_bottle_size_to_update = $result->bottle_size;
        }else{
           $product_bottle_size_to_update = $if_check_extra_text['final_bottle_size'].'ML';            
        }

        if (!empty($result->product_gender)) {
           $product_gender_to_update = $result->product_gender;
        }else{
           $product_gender_to_update = $if_check_extra_text['final_product_gender'];            
        }

        $data_to_update = array('product_name' => $if_check_extra_text['final_resultant_product_after_approved'],'brand_name' => $brand_name_to_update,'brand_type' => $result->brand_type, 'barcode' => $result->barcode, 'bottle_size' => $product_bottle_size_to_update,'product_gender' => $product_gender_to_update, 'product_type' => $result->product_type, 'spell_check' => 1,'active' => 1);

        DB::table('customer_demand_detail')->where('product_name', $result->product_name)->update(['product_name' => $if_check_extra_text['final_resultant_product_after_approved']]);

        DB::table('products')->where('id', $id)->update($data_to_update);
        return redirect()->back()->with('success-message','Product has been Approved');
    }

    public function check_extra_text($product_name){
        if (str_contains($product_name, "'") || str_contains($product_name, "’")) { 
            $old_product_name = str_replace("'", ' ', $product_name);
            $temp_product_name = str_replace("’", ' ', $old_product_name);
            $new_result = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', strtolower($temp_product_name));
        }else{
            $new_result = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', strtolower($product_name));
        }

        $remove_elements = array('edt','edp','edc');
        $results = str_replace($remove_elements,"",strtolower($new_result));
        preg_match_all('/\d+(?:\s*x\s*\d+)*\s*ml\b/i', $results, $matches);
        $before_trim_size_array = $matches[0];
        if (empty($before_trim_size_array)) {
            preg_match_all('/\d+(?:\s*x\s*\d+)*\s*G\b/i', $results, $matches);
            $before_trim_size_array = $matches[0];
        }
        if (empty($before_trim_size_array)) {
            $before_trim_size_array = '';
        }
        
        $remove_size_text = str_replace($before_trim_size_array[0],"",$results);
        $final_bottle_size = preg_replace('/\D/', '', $before_trim_size_array[0]);

        $resultant_text_after_size_removed = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $remove_size_text);
        preg_match('#\((.*?)\)#', $resultant_text_after_size_removed, $match);
        $extra_paremeters_for_search = isset($match[0]) ? $match[0] : '' ;
        if (!empty($extra_paremeters_for_search)) {
            $resultant_text = str_replace(array('(', ')'),"",$remove_size_text);
        }else{
            $resultant_text = $remove_size_text;
        }

        if (stripos(strtolower($product_name), "edp") !== false) {
            @$final_product_extra_values = 'edp';
        }
        if (stripos(strtolower($product_name), "edt") !== false) {
            @$final_product_extra_values = 'edt';
        }
        if (stripos(strtolower($product_name), "edc") !== false) {
            @$final_product_extra_values = 'edc';
        }
        if (empty($final_product_extra_values)) {
            @$final_product_extra_values = 'no_extra';
        }

        $before_gender_results = explode(" ", $resultant_text);
        foreach ($before_gender_results as $key => $inner_wrap) {
            $inner_result_data = DB::table('product_gender')
            ->select('gender')   
            ->where('name', '=', $inner_wrap)
            ->orWhere('gender', '=', $inner_wrap)
            ->get()->toArray();
            if(!empty($inner_result_data)){
                $resultant_array = array('0' => $inner_wrap, '1' => $inner_result_data);
            }
        }

        $removeable_gender_text = isset($resultant_array[0]) ? $resultant_array[0] : '';
        $final_product_gender  = isset($resultant_array[1][0]->gender) ? $resultant_array[1][0]->gender : '';
        $remove_gender_text = str_replace($removeable_gender_text,"",$resultant_text);
        $resultant_remaining_text_after_gender = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $remove_gender_text);

        $brand_names = DB::table('brands')->select('short_key', 'brand_name')->get()->toArray();
        foreach ($brand_names as $key => $brands) {
            if (stripos(strtolower($resultant_remaining_text_after_gender), strtolower($brands->brand_name)) !== false) {
                $resultant_brands = array('0' => $brands->brand_name, '1' => $brands->brand_name);
            }
            if (empty($resultant_brands)) {
            $results = explode(" ", $resultant_remaining_text_after_gender);
                foreach ($results as $key2 => $inner_wrap) {
                    $inner_result_data = DB::table('brands')
                    ->select('brand_name')
                    ->where('short_key', '=', $inner_wrap)
                    ->first();
                    if(!empty($inner_result_data))
                        $resultant_brands = array('0' => $inner_wrap, '1' => $inner_result_data->brand_name);
                }
            }
        }

        @$search = '/'.preg_quote(strtolower(@$resultant_brands[0]), '/').'/';
        @$product_name    = preg_replace($search, '', strtolower($resultant_remaining_text_after_gender), 1);
        @$final_product_name = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$product_name);
        @$brand_names   = isset($resultant_brands[1]) ? $resultant_brands[1] : '';
        if (!empty($brand_names)) {
            $final_brand_names = $brand_names;
        }else{
            $final_brand_names = $this->get_brand_name_with_wrong_spellings($final_product_name);
        }

        $resultant_product_after_approved = $final_product_name.' '.$final_product_extra_values.' '.$final_bottle_size.'ml';
        $final_resultant_product_after_approved = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $resultant_product_after_approved);

        $result_to_return = array('final_brand_names' => $final_brand_names,'final_resultant_product_after_approved' => $final_resultant_product_after_approved,'final_product_gender' => $final_product_gender,'final_bottle_size' => $final_bottle_size,'final_product_extra_values' => $final_product_extra_values, );
        return $result_to_return;
    }

    public function get_brand_name_with_wrong_spellings($data){
        if (!str_contains($data, '+')){
            if (strlen($data) < 62) {
               $results = getKeywordSuggestionsFromGoogle($data);
            }
        }

        $resultant_data = [];
        if (is_array(@$results) || is_object(@$results)){
            foreach (@$results as $key => $innerwrap) {
                similar_text(strtolower($innerwrap), strtolower(@$data), $percent);
                if ($percent > 70) {
                    $old_innerwrap = str_replace("'", '', $innerwrap);
                    $new_innerwrap = str_replace("’", ' ', $old_innerwrap);
                    $resultant_data[$percent] = $new_innerwrap;
                }
            }
        }

        $final_correct_spelling = [];
        if(!empty(@$resultant_data)){
            $max_value_key = max(array_keys(@$resultant_data));
            foreach (@$resultant_data as $key => $value) {
                if ($key == $max_value_key) {
                    $count = str_word_count($data);
                    preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $value, $matches);
                    $final_correct_spelling = $matches[0];
                }
            }
        }

        $brand_names = DB::table('brands')->select('short_key', 'brand_name')->get()->toArray();
        foreach ($brand_names as $key => $brands) {
            if (stripos(strtolower($final_correct_spelling), strtolower($brands->brand_name)) !== false) {
                $resultant_brands = array('0' => $brands->brand_name, '1' => $brands->brand_name);
            }
            if (empty($resultant_brands)) {
                $results = explode(" ", $final_correct_spelling);
                foreach ($results as $key2 => $inner_wrap) {
                    $inner_result_data = DB::table('brands')
                    ->select('brand_name')
                    ->where('short_key', '=', $inner_wrap)
                    ->first();
                    if(!empty($inner_result_data))
                        $resultant_brands = array('0' => $inner_wrap, '1' => $inner_result_data->brand_name);
                }
            }
        }

        @$search = '/'.preg_quote(strtolower(@$resultant_brands[0]), '/').'/';
        @$product_name    = preg_replace($search, '', strtolower($resultant_remaining_text_after_gender), 1);
        @$final_product_name = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$product_name);
        @$brand_names   = isset($resultant_brands[1]) ? $resultant_brands[1] : '';
        if (!empty($brand_names)) {
            $final_brand_names = $brand_names;
        }else{
            $final_brand_names = '';
        }
        return $final_brand_names;
    }

    public function update_product(Request $e)
    {
        $result = [
         'brand_type'     => $e->brand_type,
         'brand_name'     => $e->brand_name,
         'product_name'   => $e->product_name,
         'barcode'        => $e->barcode,
         'bottle_size'    => $e->bottle_size,
         'product_gender' => $e->product_gender,
         'product_type'   => $e->product_type,
        ];
        DB::table('products')->where('id', $e->id)->update($result);
        return redirect()->back()->with('success-message','Product has been updated');
    }

    public function update_temp_product(Request $e)
    {
        $result_record = $this->get_result($e->product_name);
        if (!empty($e->brand_name)) {
           $brand_name_to_update = $e->brand_name;
        }else{
           $brand_name_to_update = $result_record['final_brand_names'];            
        }

        if (!empty($e->bottle_size)) {
           $product_bottle_size_to_update = $e->bottle_size;
        }else{
           $product_bottle_size_to_update = $result_record['final_bottle_size'].'ML';            
        }

        if (!empty($e->product_gender)) {
           $product_gender_to_update = $e->product_gender;
        }else{
           $product_gender_to_update = $result_record['final_product_gender'];            
        }

        $result = [
            'brand_type'     => $e->brand_type,
            'brand_name'     => $brand_name_to_update,
            'product_name'   => $result_record['final_resultant_product_after_approved'],
            'barcode'        => $e->barcode,
            'bottle_size'    => $product_bottle_size_to_update,
            'product_gender' => $product_gender_to_update,
            'product_type'   => $e->product_type,
            'spell_check'    => 1,
            'active'         => 1
        ];

        $pDetails = DB::table('products')->select('product_name')->where('id', '=', $e->id)->first();
        DB::table('products')->where('id', $e->id)->update($result);
        DB::table('customer_demand_detail')->where('product_name', $pDetails->product_name)->update(['product_name' => $result_record['final_resultant_product_after_approved']]);
        return redirect()->back()->with('success-message','Product has been updated');
    }

    public function get_result($product_name)
    {
        if (str_contains($product_name, "'") || str_contains($product_name, "’")) { 
            $old_product_name = str_replace("'", ' ', $product_name);
            $temp_product_name = str_replace("’", ' ', $old_product_name);
            $new_result = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', strtolower($temp_product_name));
        }else{
            $new_result = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', strtolower($product_name));
        }

        $remove_elements = array('edt','edp','edc');
        $results = str_replace($remove_elements,"",strtolower($new_result));
        preg_match_all('/\d+(?:\s*x\s*\d+)*\s*ml\b/i', $results, $matches);
        $before_trim_size_array = $matches[0];
        if (empty($before_trim_size_array)) {
            preg_match_all('/\d+(?:\s*x\s*\d+)*\s*G\b/i', $results, $matches);
            $before_trim_size_array = $matches[0];
        }
        if (empty($before_trim_size_array)) {
            $before_trim_size_array = '';
        }
        
        $remove_size_text = str_replace($before_trim_size_array[0],"",$results);
        $final_bottle_size = preg_replace('/\D/', '', $before_trim_size_array[0]);

        $resultant_text_after_size_removed = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $remove_size_text);
        preg_match('#\((.*?)\)#', $resultant_text_after_size_removed, $match);
        $extra_paremeters_for_search = isset($match[0]) ? $match[0] : '' ;
        if (!empty($extra_paremeters_for_search)) {
            $resultant_text = str_replace(array('(', ')'),"",$remove_size_text);
        }else{
            $resultant_text = $remove_size_text;
        }

        if (stripos(strtolower($product_name), "edp") !== false) {
            @$final_product_extra_values = 'edp';
        }
        if (stripos(strtolower($product_name), "edt") !== false) {
            @$final_product_extra_values = 'edt';
        }
        if (stripos(strtolower($product_name), "edc") !== false) {
            @$final_product_extra_values = 'edc';
        }
        if (empty($final_product_extra_values)) {
            @$final_product_extra_values = 'no_extra';
        }

        $before_gender_results = explode(" ", $resultant_text);
        foreach ($before_gender_results as $key => $inner_wrap) {
            $inner_result_data = DB::table('product_gender')
            ->select('gender')   
            ->where('name', '=', $inner_wrap)
            ->orWhere('gender', '=', $inner_wrap)
            ->get()->toArray();
            if(!empty($inner_result_data)){
                $resultant_array = array('0' => $inner_wrap, '1' => $inner_result_data);
            }
        }

        $removeable_gender_text = isset($resultant_array[0]) ? $resultant_array[0] : '';
        $final_product_gender  = isset($resultant_array[1][0]->gender) ? $resultant_array[1][0]->gender : '';
        $remove_gender_text = str_replace($removeable_gender_text,"",$resultant_text);
        $resultant_remaining_text_after_gender = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $remove_gender_text);

        $brand_names = DB::table('brands')->select('short_key', 'brand_name')->get()->toArray();
        foreach ($brand_names as $key => $brands) {
            if (stripos(strtolower($resultant_remaining_text_after_gender), strtolower($brands->brand_name)) !== false) {
                $resultant_brands = array('0' => $brands->brand_name, '1' => $brands->brand_name);
            }
            if (empty($resultant_brands)) {
            $results = explode(" ", $resultant_remaining_text_after_gender);
                foreach ($results as $key2 => $inner_wrap) {
                    $inner_result_data = DB::table('brands')
                    ->select('brand_name')
                    ->where('short_key', '=', $inner_wrap)
                    ->first();
                    if(!empty($inner_result_data))
                        $resultant_brands = array('0' => $inner_wrap, '1' => $inner_result_data->brand_name);
                }
            }
        }

        @$search = '/'.preg_quote(strtolower(@$resultant_brands[0]), '/').'/';
        @$product_name    = preg_replace($search, '', strtolower($resultant_remaining_text_after_gender), 1);
        @$final_product_name = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$product_name);
        @$brand_names   = isset($resultant_brands[1]) ? $resultant_brands[1] : '';
        if (!empty($brand_names)) {
            $final_brand_names = $brand_names;
        }else{
            $final_brand_names = '';
        }

        $resultant_product_after_approved = $final_brand_names.' '.$final_product_name.' '.$final_product_extra_values.' '.$final_bottle_size.'ml';
        $final_resultant_product_after_approved = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $resultant_product_after_approved);
        $result_to_return = array('final_brand_names' => $final_brand_names,'final_resultant_product_after_approved' => $final_resultant_product_after_approved,'final_product_gender' => $final_product_gender,'final_bottle_size' => $final_bottle_size,'final_product_extra_values' => $final_product_extra_values, );
        return $result_to_return;
    }

    public function delete_product($id)
    {
        $result = Product::find($id)->delete();
        cache()->flush();
        return redirect()->route('product-list')->with('warning-message','Product has been Deleted');
    }

    public function delete_brand($id)
    {
        $result = Brands::find($id)->delete();
        cache()->flush();
        return redirect()->route('brands-list')->with('warning-message','Brand has been Deleted');
    }

    public function delete_temp_product($id)
    {
        $user     = Auth::user();
        $added_by = $user->id;
        DB::table('products')->where('id', $id)->where('added_by', $added_by)->delete();
        cache()->flush();
        return redirect()->route('product-data-cleaner')->with('warning-message','Product has been Deleted');
    }

}
