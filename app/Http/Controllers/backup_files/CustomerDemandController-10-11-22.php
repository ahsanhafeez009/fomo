<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CustomerDemand;
use App\Models\Product;
use App\Models\Company;
use App\Imports\ImportCustomerDemand;
use Carbon\Carbon;
use File;

class CustomerDemandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function whatsapp_query()
    {
        $title = 'Whatsapp Query';
        $data = [
            'title' => $title,
        ];
        return view('admin.whatsapp-query.query', $data);
    }

    public function customer_demand_uploading()
    {
        $title = 'Uploading Customer Demands';
        $data = [
            'title' => $title,
        ];
        return view('admin.customer-demand.customer_demand_uploading', $data);
    }

    public function customer_file_uploading(Request $e)
    {
        $file = $e->file('file');
        if ($file) {
            $filename = $file->getClientOriginalName();
            $file_name = time().rand(100,999).$filename;
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $this->checkUploadedFileProperties($extension, $fileSize);
            $location = 'file_uploading/customer-demand';
            $file->move($location, $file_name);
            $filepath = public_path($location . "/" . $file_name);
            Excel::import(new ImportCustomerDemand, $filepath);
            unlink($filepath);
            $company_result = DB::table('temp_customer_demand')
                ->select('company_name')
                ->where('company_name', '!=', Null)
                ->orderBy('id', 'DESC')
                ->first();
            $product_result = DB::table('temp_customer_demand')
                ->select('product_name')
                ->where('product_name', '!=', Null)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get();
            $company_found      = $this->company_searching($company_result->company_name);
            $old_product_result = json_decode($product_result[0]->product_name);
            $products_found     = $this->products_searching($old_product_result);
            $result_to_show     = $this->make_html_for_customer_demands($company_result, $company_found, $old_product_result, $products_found);
            echo json_encode($result_to_show);
            exit();
        }
    }

    public function make_html_for_customer_demands($old_company_result, $resultant_company_found, $old_product_result, $resultant_products_found){
        foreach ($old_product_result as $key => $value) {
            @$results[$value] = @$resultant_products_found[$key];
        }
        ob_start(); ?>
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <label><?php echo $old_company_result->company_name; ?></label>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <select class="form-control" name="company_name" id="company_name">
                        <option value="<?php echo $resultant_company_found[0]->company_name; ?>"><?php echo $resultant_company_found[0]->company_name; ?></option>
                    </select>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <input type="checkbox" name="register_new_company" id="register_new_company" value="<?php echo $old_company_result->company_name; ?>">
                </div>
            </div>
        <?php echo'<br>'; ?>
        <?php foreach ($results as $key => $products){
            $newKey = str_replace(" ", "_", $key); ?>
            <div class="row">    
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                  <label><?php echo $key; ?></label>
                  <input type="hidden" name="user_products[]" value="<?php echo $key; ?>">
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <select class="form-control" name="matchable_products[]" id="matchable_products">
                        <?php if(isset($products) ? $products : '') { ?>
                            <!-- <option value="no"></option> -->
                        <?php } ?>
                        <?php if (is_array($products) || is_object($products)){ ?>
                        <?php foreach ($products as $key1 => $value) { ?>
                            <option value="<?php echo $value['product_name']; ?>"><?php echo $value['product_name']; ?></option>
                        <?php } }?>
                    </select>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <input type="checkbox" name="register_new_product" id="register_new_product[]" value="<?php echo $key; ?>">
                </div>
            </div>
        <?php echo'<br>'; ?>
        <?php } ?>
        <?php
        $res = ob_get_clean();
        echo json_encode(array('html' => $res));
        exit;
    }

    public function checkUploadedFileProperties($extension, $fileSize)
    {
        $valid_extension = array("csv", "xlsx");
        $maxFileSize = 2097152;
        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize <= $maxFileSize) {
            } else {
                throw new \Exception('No file was uploaded', Response::HTTP_REQUEST_ENTITY_TOO_LARGE); //413 error
            }
        } else {
            throw new \Exception('Invalid file extension', Response::HTTP_UNSUPPORTED_MEDIA_TYPE); //415 error
        }
    }

    public function products_searching($uploaded_products)
    {
        $if_check_extra_text = $this->check_extra_text($uploaded_products);
        $remaining_text      = $this->check_product_gender($if_check_extra_text['remaing_text']);
        $if_brand_data       = $this->find_brand($remaining_text['remaing_text_after_gender']);
        $final_array = array();
        for ($i=0; $i <count($if_brand_data['product_names']); $i++) {
            $brand_name = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$if_brand_data['final_brand_names'][$i]);
            $product_gender = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$remaining_text['bottle_gender'][$i]);
            $product_name = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$if_brand_data['product_names'][$i]);
            $bottle_size = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$if_check_extra_text['bottle_sizes'][$i]);
            $products = Product::select('*');
            if (!empty($brand_name))
                $products->where('brand_name', 'like', '%' . $brand_name . '%');
            if (!empty($product_name))
                $products->whereRaw("MATCH(product_name) AGAINST('".$product_name."')");
                // $products->where('product_nameddd', 'like', '%' . $product_name . '%');
            if (!empty($bottle_size))
                $products->where('bottle_size', 'like', '%' . $bottle_size . '%');
            if (!empty($product_gender))
                $products->where('product_gender', 'like', '%' . $product_gender . '%');
            $final_array[] = $products->get()->toArray();
        }
        $result_to_show = $this->get_most_matchable_products($final_array, $uploaded_products);
        return($result_to_show);
    }

    public function get_most_matchable_products($final_array, $product_names){
        $results = [];
        foreach ($final_array as $key => $inner_wrap) {
            foreach ($inner_wrap as $key1 => $inner_value) {
                $remove_elements = array('EDT','Edt','edt','EDP','Edp','edp','EDC','Edc','edc');
                $result= str_replace($remove_elements,"",$inner_value['product_name']);
                $results[$key][] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $result);
            }
            if (!array_key_exists($key,(array)$results))
                $results[$key][] = "";
        }

        foreach ($final_array as $key => $inner_wrap) {
            foreach ($inner_wrap as $key1 => $inner_value) {
                preg_match_all('/\d+(?:\s*x\s*\d+)*\s*ml\b/i', $inner_value['product_name'], $matches);
                $before_trim_size_array[$key][] = $matches[0];
            }
        }
        
        if (is_array($results) || is_object($results)){
            foreach ($results as $key => $inner_wrap) {
                foreach ($inner_wrap as $key1 => $inner_value) {
                    $remove_size_text[$key][] = str_replace(isset($before_trim_size_array[$key][$key1][0]) ? $before_trim_size_array[$key][$key1][0] : '',"",$inner_value);
                }
                if (!array_key_exists($key, (array)$remove_size_text)){
                    $remove_size_text[$key] = '';
                }
            }
        }

        foreach ($remove_size_text as $key => $inner_wrap) {
            foreach ($inner_wrap as $key1 => $inner_value) {
                $resultant_array[$key][] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $inner_value);
            }
            if (!array_key_exists($key, (array)$resultant_array))
                $resultant_array[$key][] = '';
        }

        $found_matched_result = [];
        foreach ($product_names as $key => $outerwrap) {
            foreach ($resultant_array[$key] as $key1 => $inner_wrap) {
                if (stripos(strtolower($outerwrap), strtolower($inner_wrap)) !== false) {
                    $found_matched_result[$key][$key1] = $inner_wrap;
                }
            }
            if (!array_key_exists($key, (array)$found_matched_result))
                $found_matched_result[$key][] = '';
        }

        $final_record_to_show = [];
        foreach ($found_matched_result as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $inner_wrap) {
                $final_record_to_show[$key][] = isset($final_array[$key][$key1]) ? $final_array[$key][$key1] : '';
            }
            if (!array_key_exists($key, (array)$final_record_to_show)){
                $final_record_to_show[$key][] = $final_array[$key];
            }
        }
        
        foreach ($product_names as $key => $product_name) {
            if (stripos(strtolower($product_name), "edp") !== false) {
                @$resultant_product_names[$key]['edp'] = $product_name;
            }
            if (stripos(strtolower($product_name), "edt") !== false) {
                @$resultant_product_names[$key]['edt'] = $product_name;
            }
            if (stripos(strtolower($product_name), "edc") !== false) {
                @$resultant_product_names[$key]['edc'] = $product_name;
            }
            if (empty(@$resultant_product_names[$key])){
                @$resultant_product_names[$key]['edp'] = $product_name;
            }
        }
        
        // if (!array_key_exists($key, @$resultant_product_names)){
        //     @$resultant_product_names[$key][] = '';
        // }

        foreach ($final_record_to_show as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $inner_wrap) {
                if (stripos(strtolower(isset($inner_wrap['product_name']) ? $inner_wrap['product_name'] : ''), strtolower('edp')) !== false) {
                    $resultant_final_record_to_show[$key]['edp'][] = $inner_wrap;
                }
                if (stripos(strtolower(isset($inner_wrap['product_name']) ? $inner_wrap['product_name'] : ''), strtolower('edt')) !== false) {
                    $resultant_final_record_to_show[$key]['edt'][] = $inner_wrap;
                }
                if (stripos(strtolower(isset($inner_wrap['product_name']) ? $inner_wrap['product_name'] : ''), strtolower('edc')) !== false) {
                    $resultant_final_record_to_show[$key]['edc'][] = $inner_wrap;
                }
            }
            if (empty(@$resultant_final_record_to_show[$key])){
                @$resultant_final_record_to_show[$key]['edp'][] = isset($outerwrap) ? $outerwrap : '';
            }
        }

        foreach ($resultant_product_names as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $innerwrap) {
                $new_result[$key] = isset($resultant_final_record_to_show[$key][$key1]) ? $resultant_final_record_to_show[$key][$key1] : '';
            }
            if (!array_key_exists($key, $new_result)){
                $new_result[$key] = isset($resultant_final_record_to_show) ? $resultant_final_record_to_show : '';
            }
        }

        echo'<pre/>';
        print_r($new_result);
        exit;
        return $new_result;
    }

    public function check_product_gender($remaining_text){
        $resultant_array = [];
        foreach ($remaining_text as $key => $value) {
            $results = explode(" ", $value);
            foreach ($results as $key1 => $inner_wrap) {
                $inner_result_data = DB::table('product_gender')
                ->select('gender')   
                ->where('name', '=', $inner_wrap)
                ->orWhere('gender', '=', $inner_wrap)
                ->get()->toArray();
                if(!empty($inner_result_data))
                    $resultant_array[$key][] = array('0' => $inner_wrap, '1' => $inner_result_data);
            }
            if (!array_key_exists($key, $resultant_array))
                $resultant_array[$key][] = '';
        }

        $removeable_text = [];
        foreach ($resultant_array as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $value) {
            $removeable_text[$key][] = isset($value[0]) ? $value[0] : '';
            $product_gender[$key] = isset($value[1][0]->gender) ? $value[1][0]->gender : '';
            }
        }

        foreach ($remaining_text as $key => $value) {
            $result = str_replace(' '.$removeable_text[$key][0]," ",$value);
            $resultant_remaining_text[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $result);
        }
        $return_result = array('bottle_gender' => $product_gender, 'remaing_text_after_gender' => $resultant_remaining_text);
        return $return_result;
    }

    public function check_extra_text($decoded_uploaded_products){
        foreach ($decoded_uploaded_products as $key => $value) {
            $result = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value);
            $old_result = str_replace("'", ' ', $result);
            $new_result[$key] = str_replace("’", ' ', $old_result);
        }
        $remove_elements = array('EDT','Edt','edt','EDP','Edp','edp','EDC','Edc','edc','FOR','for','By','by','RED','Red','red');
        $results = str_replace($remove_elements,"",$new_result);
        
        foreach ($results as $key => $value) {
            preg_match_all('/\d+(?:\s*x\s*\d+)*\s*ml\b/i', $value, $matches);
            $before_trim_size_array[$key] = $matches[0];
            if (empty($before_trim_size_array[$key])) {
                preg_match_all('/\d+(?:\s*x\s*\d+)*\s*G\b/i', $value, $matches);
                $before_trim_size_array[$key] = $matches[0];
            }
            if (empty($before_trim_size_array[$key])) {
                $before_trim_size_array[$key][] = '';
            }
        }
        $remove_size_text = [];
        $bottle_sizes = [];
        foreach ($results as $key => $value) {
            $remove_size_text[] = str_replace($before_trim_size_array[$key][0],"",$value);
            $bottle_sizes[] = preg_replace('/\D/', '', $before_trim_size_array[$key][0]);
        }
        $resultant_text_after_size_removed = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $remove_size_text);
        foreach ($resultant_text_after_size_removed as $key => $row) {
            preg_match('#\((.*?)\)#', $row, $match);
            $extra_paremeters_for_search[] = isset($match[0]) ? $match[0] : '' ;
        }

        foreach ($resultant_text_after_size_removed as $key => $outerwrap) {
            $resultant_text[$key] = str_replace($extra_paremeters_for_search[$key],"",$outerwrap);
        }
        $return_result = array('bottle_sizes' => $bottle_sizes, 'remaing_text' => $resultant_text);
        return $return_result;
    }

    public function find_brand($data){
        foreach ($data as $key => $value) {
            $results[$key] = getKeywordSuggestionsFromGoogle($value);
        }
        // echo'<pre/>';
        // print_r($results);
        // exit;
        $resultant_data = [];
        foreach ($results as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $innerwrap) {
                similar_text(strtolower($innerwrap), strtolower($data[$key]), $percent);
                if ($percent > 90) {
                    $old_innerwrap = str_replace("'", '', $innerwrap);
                    $new_innerwrap = str_replace("’", ' ', $old_innerwrap);
                    $resultant_data[$key][$percent] = $new_innerwrap;
                }
            }
            if (!array_key_exists($key,(array)$resultant_data))
                $resultant_data[$key][] = $data[$key];
        }
        
        foreach ($resultant_data as $key => $value) {
            $maxs[$key] = max(array_keys($value));
            $final_correct_spelling[$key] = $value[$maxs[$key]];
        }
        $brand_names = DB::table('brands')->select('short_key', 'brand_name')->get()->toArray();
        $resultant_brands = [];
        foreach ($final_correct_spelling as $key => $user_data) {
            foreach ($brand_names as $key1 => $brands) {
                if (stripos(strtolower($user_data), strtolower($brands->brand_name)) !== false) {
                    $resultant_brands[$key] = array('0' => $brands->brand_name, '1' => $brands->brand_name);
                }
            }
            $results = explode(" ", $user_data);
            foreach ($results as $key2 => $inner_wrap) {
                $inner_result_data = DB::table('brands')
                ->select('brand_name')   
                ->where('short_key', '=', $inner_wrap)
                ->first();
                if(!empty($inner_result_data))
                    $resultant_brands[$key] = array('0' => $inner_wrap, '1' => $inner_result_data->brand_name);
            }
            if (empty($resultant_brands[$key])) {
                $resultant_brands[$key][] = '';
            }
        }

        $product_names = [];
        $brand_names = [];
        foreach ($final_correct_spelling as $key => $value) {
            $search = '/'.preg_quote(strtolower($resultant_brands[$key][0]), '/').'/';
            $product_name    = preg_replace($search, '', strtolower($value), 1);
            $product_names[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $product_name);
            $brand_names[$key]   = isset($resultant_brands[$key][1]) ? $resultant_brands[$key][1] : '';
        }
        $final_array = array('product_names' => $product_names, 'final_brand_names' => $brand_names);
        return $final_array;
    }

    public function company_searching($uploaded_company)
    {
        $new_uploaded_company = str_replace(array('L.L.C', 'L.l.c', 'l.l.c', 'LLC', 'Llc', 'llc', 'PERFUMES', 'perfumes', 'Perfumes', 'TRADING', 'trading', 'Trading', 'GENERAL', 'general', 'General', 'WORLD', 'World', 'world', 'COMPANY', 'Company', 'company'), '', $uploaded_company);
        $sql = "SELECT * FROM `companies` WHERE MATCH(`company_name`) AGAINST('$new_uploaded_company')";
        $found_company_name = DB::select($sql);
        $found_company_list = array();
        if (count($found_company_name) > 1) {
            $found_company_list = array_slice($found_company_name, 0, 4);
        }
        if (count($found_company_name) <= 1 && count($found_company_name) > 0) {
            $found_company_list = $found_company_name;
        }
        if (count($found_company_name) < 0 || empty($found_company_name)) {
            $resultant_companies = array('company_name' => $uploaded_company);
            $no_company_found = array('company_name' => "No Company Found");
            $arr = (array)array('0' => $no_company_found, '1' => $resultant_companies);
            $obj = json_decode(json_encode($arr));
            $found_company_list = $obj;
        }
        return $found_company_list;
    }

    public function customer_demand_page()
    {
        $title = 'Customer Demand Page';
        $customer_demand = DB::table('customer_demand')->leftjoin('companies', 'customer_demand.company_name', '=', 'companies.company_name')->select('customer_demand.*', 'companies.*', 'products.*')->leftjoin('products', 'customer_demand.product_name', '=', 'products.product_name')->where('products.active', '=', 1)->where('companies.active', '=', 1)->get()->toArray();
        $country_data = Company::select('country')->groupBy('country')->where('country', '!=', Null)->get();
        $state_data = Company::select('state')->groupBy('state')->where('country', '!=', Null)->get();
        $city_data = Company::select('city')->groupBy('city')->where('country', '!=', Null)->get();
        $data = [
            'title' => $title,
            'customer_demand' => $customer_demand,
            'country_data' => $country_data,
            'state_data' => $state_data,
            'city_data' => $city_data,
        ];
        return view('admin.customer-demand.index', $data);
    }

    public function product_demand_page_ajax(Request $e)
    {
        $customer_demand = CustomerDemand::select('customer_demand.*', 'companies.*', 'products.*')
            ->leftjoin('companies', 'customer_demand.company_name', '=', 'companies.company_name')
            ->leftjoin('products', 'customer_demand.product_name', '=', 'products.product_name')
            ->where('products.active', '=', 1)->where('companies.active', '=', 1);
        if (!empty($e->input('country')))
            $customer_demand->whereIn('country', $e->input('country', []));
        if (!empty($e->input('state')))
            $customer_demand->whereIn('state', $e->input('state', []));
        if (!empty($e->input('city')))
            $customer_demand->whereIn('city', $e->input('city', []));
        $customer_demand = $customer_demand->get();
        ob_start(); ?>
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>SN</th>
                <th>Country</th>
                <th>State</th>
                <th>City</th>
                <th>Company Name</th>
                <th>Company Type</th>
                <th>Brand Type</th>
                <th>Brand Name</th>
                <th>Barcode (EAN code)</th>
                <th>Product Name</th>
                <th>Product Gender</th>
                <th>Product Type</th>
                <th>Date</th>
                <th>Qty</th>
                <th>Target Price</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            foreach ($customer_demand as $key => $value) { ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $value->country; ?></td>
                <td><?php echo $value->state; ?></td>
                <td><?php echo $value->city; ?></td>
                <td><?php echo $value->company_name; ?></td>
                <td><?php echo $value->company_type; ?></td>
                <td><?php echo $value->brand_type; ?></td>
                <td><?php echo $value->brand_name; ?></td>
                <td><?php echo $value->barcode; ?></td>
                <td><?php echo $value->product_name; ?></td>
                <td><?php echo $value->product_gender; ?></td>
                <td><?php echo $value->product_type; ?></td>
                <td><?php echo $value->date; ?></td>
                <td><?php echo $value->qty; ?></td>
                <td><?php echo $value->price; ?></td>
                <?php $i++;
                } ?>
            </tr>
            </tbody>
        </table>
        <?php
        $res = ob_get_clean();
        echo json_encode(array('html' => $res));
        exit;
    }

    public function update_customer_demand_page_data(Request $e)
    {
        $company_name         = $e->input('company_name');
        $register_new_company = $e->input('register_new_company');
        $user_products        = $e->input('user_products');
        $matchable_products   = $e->input('matchable_products');
        $register_new_product   = $e->input('register_new_product');
        if (!empty($register_new_company)) {
            $company_name = $register_new_company;
            $result = DB::table('temp_companies')->insert([
                'company_name' => $company_name,
                'active' => 0,
            ]);
        }else{
            $company_name = $company_name;
        }

        foreach ($user_products as $key => $inner_wrap) {
            if ($matchable_products[$key] == "no") {
                $user_demand_products[$key] = $inner_wrap;
                $result = DB::table('temp_products')->insert([
                    'product_name' => $inner_wrap,
                    'active' => 0,
                ]);
            }else{
                $user_demand_products[$key] = $matchable_products[$key];                
            }
            if ($register_new_product == $inner_wrap) {
                $user_demand_products[$key] = $register_new_product;
                $result = DB::table('temp_products')->insert([
                    'product_name' => $register_new_product,
                    'active' => 0,
                ]);
            }
        }

        $result = DB::table('customer_demand')->insert([
            'company_name' => $company_name,
            'product_name' => json_encode($user_demand_products)
        ]);
        $last_company_data_inserted = DB::table('temp_customer_demand')
        ->select('company_name')
        ->where('company_name', '=', $company_name)
        ->orderBy('id', 'DESC')
        ->first();
        DB::table('temp_customer_demand')->where('company_name', $last_company_data_inserted->company_name)->delete();
        echo json_encode(array('result' => $result));
        exit;
    }
}
