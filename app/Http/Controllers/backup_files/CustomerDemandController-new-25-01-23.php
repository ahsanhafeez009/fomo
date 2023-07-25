<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerDemand;
use App\Models\Product;
use App\Models\Company;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

    public function customer_file_uploading(Request $request)
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
            $result = $this->ImportCustomerDemand($data);
            if ($result == 1) {
                $user = Auth::user();
                $added_by = $user->id;
                $company_result = DB::table('temp_customer_demand')
                ->select('company_name')
                ->where('company_name', '!=', Null)
                ->where('added_by', '=', $added_by)
                ->orderBy('id', 'DESC')
                ->first();
                $record_data = DB::table('temp_customer_demand')
                ->select('*')
                ->orderBy('id', 'DESC')
                ->where('added_by', '=', $added_by)
                ->limit(1)
                ->first();
                $company_found      = $this->company_searching($company_result->company_name);
                $old_product_result = json_decode($record_data->product_name);
                $products_found     = $this->products_searching($record_data);
                $result_to_show     = $this->make_html_for_customer_demands($company_result, $company_found, $old_product_result, $products_found);
                echo json_encode($result_to_show);
                exit();
            }else{
                echo json_encode(array('status' => false));
                exit;
            }
        } catch (Exception $e) {
            $error_code = $e->errorInfo[1];
            return back()->withErrors('There was a problem uploading the data!');
        }
    }

    function ImportCustomerDemand($resultant_array){
        foreach (@$resultant_array as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $innerwrap) {
                $new_innerwrap = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
                if (strtolower($new_innerwrap) == "barcode") {
                    $headings_used_by[$key][$key1] = 'barcode';
                }

                if (strtolower($new_innerwrap) == "brand") {
                    $headings_used_by[$key][$key1] = 'brand';
                }

                if (strtolower($new_innerwrap) == "product description") {
                    @$counter = [$key];
                    $headings_used_by[$key][$key1] = 'product description';
                }

                if (strtolower($new_innerwrap) == "qty") {
                    $headings_used_by[$key][$key1] = 'qty';
                }

                if (strtolower($new_innerwrap) == "price") {
                    $headings_used_by[$key][$key1] = 'price';
                }
            }
        }
        
        $date = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $resultant_array[0]['A']);
        $company_name = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $resultant_array[1]['A']);
        $country = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $resultant_array[2]['A']);
        $city = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $resultant_array[3]['A']);

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
                    @$barcodes[$key] = $value[$key1];
                }
                
                if ($innerwrap == "brand") {
                    @$brands[$key] = $value[$key1];
                }
                
                if ($innerwrap == "product description") {
                    if (!empty($value[$key1])) {
                        @$product_description[$key] = $value[$key1];
                    }
                }

                if ($innerwrap == "qty") {
                    @$qty[$key] = $value[$key1];
                }

                if ($innerwrap == "price") {
                    @$price[$key] = $value[$key1];
                }
            }
        }

        // if(count($product_description) <= 250){
            $barcode                 = json_encode(@$barcodes);
            $qty                     = json_encode(@$qty);
            $price                   = json_encode(@$price);
            $resultant_customer_data = json_encode(@$product_description);
            $resultant_brand_name    = json_encode(@$brands);
            $user = Auth::user();
            $added_by = $user->id;
            $result = DB::table('temp_customer_demand')->insert([
                'company_name'  => @$company_name,
                'country'       => @$country,
                'city'          => @$city,
                'barcode'       => @$barcode,
                'brand_name'    => @$resultant_brand_name,
                'qty'           => @$qty,
                'date'          => @$date,
                'price'         => @$price,
                'product_name'  => @$resultant_customer_data,
                'added_by'      => !empty($added_by) ? $added_by : '',
            ]);
            return $result;
        // }else{
        //     $result = 0;
        //     return $result;
        // }
    }

    public function make_html_for_customer_demands($old_company_result, $resultant_company_found, $old_product_result, $resultant_products_found){
        if (is_array($old_product_result) || is_object($old_product_result)){
            foreach (@$old_product_result as $key => $value) {
                if (!empty($value)) {
                    if (!empty($resultant_products_found['final_result_to_show_to_modal'][$key])) {
                        if (!empty($resultant_products_found['less_than_50'][$key])) {
                            @$results[$value]['less_than_50'] = @$resultant_products_found['final_result_to_show_to_modal'][$key];
                        }else{
                            @$results[$value]['greater_than_50'] = @$resultant_products_found['final_result_to_show_to_modal'][$key];
                    }
                    }else{
                        @$results[$value]['less_than_50'] = '';
                    }
                }
            }
        }
        ob_start(); ?>
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <input type="hidden" name="old_company_name" value="<?php echo @$old_company_result->company_name;; ?>">
                    <label style="font-weight: 600;"><?php echo @$old_company_result->company_name; ?></label>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <select class="form-control" name="company_name" id="company_name">
                        <option value="<?php echo @$resultant_company_found[0]->company_name; ?>"><?php echo @$resultant_company_found[0]->company_name; ?></option>
                    </select>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <input type="checkbox" name="register_new_company" id="register_new_company" value="<?php echo @$old_company_result->company_name; ?>">
                </div>
            </div>
        <?php echo'<br>'; ?>
        <?php $i=0; ?>
        <?php foreach (@$results as $key => $products){
            if (!empty($products)) { 
                if (!empty($key)) { ?>
                    <div class="row">    
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                          <label style="font-weight: 600;"><?php echo @$key; ?></label>
                          <input type="hidden" name="user_products[<?php echo @$i; ?>]" value="<?php echo @$key; ?>">
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <select class="form-control" name="matchable_products[<?php echo @$i; ?>]" id="matchable_products">
                                <?php if (is_array($products) || is_object($products)){
                                    if (is_array(@$products['greater_than_50']) || is_object(@$products['greater_than_50'])){
                                        foreach (@$products['greater_than_50'] as $key1 => $value) { 
                                            if($value != null){ ?>
                                                <option value="<?php echo $value['product_name']; ?>"><?php echo '('.@$value['brand_name'].') '.@$value['product_name']; ?></option>
                                            <?php }else{ ?>
                                                <option value="no"></option>
                                            <?php }
                                        }
                                    }
                                    if (is_array(@$products['less_than_50']) || is_object(@$products['less_than_50'])){
                                        foreach (@$products['less_than_50'] as $key1 => $value) { 
                                            if($value != null){ ?>
                                                <option value="<?php echo $value['product_name']; ?>"><?php echo '('.@$value['brand_name'].') '.@$value['product_name'].'*'; ?></option>
                                            <?php }else{ ?>
                                                <option value="no"></option>
                                            <?php }
                                        }
                                    }
                                }?>
                            </select>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <input type="checkbox" name="register_new_product[<?php echo @$i; ?>]" id="register_new_product" value="<?php echo @$i; ?>">
                        </div>
                    </div>
                <?php $i++;} 
                echo'<br>';
            } 
        }
        $res = ob_get_clean();
        echo json_encode(array('html' => $res, 'status' => true));
        exit;
    }

    public function products_searching($uploaded_data)
    {
        $customers_barcode      = json_decode($uploaded_data->barcode);
        $customers_brand_name   = $this->find_correct_brand(json_decode($uploaded_data->brand_name));
        $customers_product_name = json_decode($uploaded_data->product_name);
        $if_check_extra_text    = $this->check_extra_text($customers_product_name);
        $remaining_text         = $this->check_product_gender($if_check_extra_text['remaing_text']);
        $if_brand_data          = $this->find_brand($remaining_text['remaing_text_after_gender']);
        $final_product_names    = $this->cross_checked_brands($if_brand_data['product_names']);
        $final_array = array();
        for ($i=0; $i<count($final_product_names); $i++) {
            $brand_name     = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$if_brand_data['final_brand_names'][$i]);
            $product_name   = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$final_product_names[$i]);
            $product_gender = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$remaining_text['bottle_gender'][$i]);
            $bottle_size    = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$if_check_extra_text['bottle_sizes'][$i]);
            $products = Product::select('*');
            if (!empty($customers_barcode[$i])){
                    $products->where('barcode', '=', $customers_barcode[$i]);
            }else{
                if (!empty($customers_brand_name[$i])){
                    $products->where('brand_name', 'like', '%' . $customers_brand_name[$i] . '%');
                }else{
                    if (!empty($brand_name)){
                        $products->where('brand_name', 'like', '%' . $brand_name . '%');
                    }
                }
                if (!empty($product_name)){
                    if (strlen($product_name) < 3) {
                        $products->where('product_name', 'like', '%' . $product_name . '%');
                    }else{
                        $products->whereRaw("MATCH(product_name) AGAINST('".$product_name."')");
                    }
                }
                if (!empty($bottle_size)){
                        $products->where('bottle_size', 'like', '%' . $bottle_size . '%');
                }
                if (!empty($product_gender)){   
                    if (strtolower($product_gender =='male')) {
                        $products->where('product_gender', '=',$product_gender);
                    }else{
                        $products->where('product_gender', 'like', '%' . $product_gender . '%');
                    }
                }
            }
            $final_array[$i] = $products->get()->toArray();
            if (empty($final_array[$i])) {
                $final_array[$i] =  $this->find_brand_product_from_google(@$remaining_text['remaing_text_after_gender'][$i], $i, @$remaining_text['bottle_gender'][$i], @$if_check_extra_text['bottle_sizes'][$i]);
            }
        }
        $result_to_show = $this->get_most_matchable_products($final_array, $final_product_names, $if_check_extra_text['resultant_product_names']);
        return($result_to_show);
    }

    public function get_most_matchable_products($final_array, $product_names, $resultant_product_names){
        $results = [];
        if (is_array(@$final_array) || is_object(@$final_array)){
            foreach (@$final_array as $key => $inner_wrap) {
                if (is_array(@$inner_wrap) || is_object(@$inner_wrap)){
                    foreach ($inner_wrap as $key1 => $inner_value) {
                        if (!empty($inner_value['product_name'])) {
                            $remove_elements = array('edt','edp','edc');
                            $result= str_replace($remove_elements,"",strtolower(@$inner_value['product_name']));
                            $results[$key][] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $result);
                        }else{
                            $results[$key][] = "";
                        }
                    }
                }
                if (!array_key_exists($key,(array)$results))
                    $results[$key][] = "";
            }
        }

        if (is_array(@$final_array) || is_object(@$final_array)){
            foreach (@$final_array as $key => $inner_wrap) {
                if (is_array(@$inner_wrap) || is_object(@$inner_wrap)){
                    foreach ($inner_wrap as $key1 => $inner_value) {
                        if (!empty($inner_value['product_name'])) {
                            preg_match_all('/\d+(?:\s*x\s*\d+)*\s*ml\b/i', $inner_value['product_name'], $matches);
                            $before_trim_size_array[$key][] = $matches[0];
                        }else{
                            $before_trim_size_array[$key][] = "";
                        }
                    }
                }
            }
        }
        
        $temp_resultant_array = [];
        if (is_array($results) || is_object($results)){
            foreach ($results as $key => $inner_wrap) {
                foreach ($inner_wrap as $key1 => $inner_value) {
                    $temp_remove_size_text = str_replace(isset($before_trim_size_array[$key][$key1][0]) ? $before_trim_size_array[$key][$key1][0] : '',"",$inner_value);
                    $temp_resultant_array[$key][] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $temp_remove_size_text);
                }
                if (!array_key_exists($key, (array)$temp_resultant_array)){
                    $temp_resultant_array[$key][] = '';
                }
            }
        }

        $resultant_array = [];
        if (is_array($temp_resultant_array) || is_object($temp_resultant_array)){
            foreach ($temp_resultant_array as $key => $inner_wrap) {
                foreach ($inner_wrap as $key1 => $inner_value) {
                    $remove_elements = array('men','man','female','male','women','woman');
                    $result= str_replace($remove_elements,"",strtolower($inner_value));
                    $resultant_array[$key][] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $result);
                }
                if (!array_key_exists($key,(array)$results))
                    $resultant_array[$key][] = "";
            }
        }

        // $final_product_names = $this->find_brand($product_names);
        $found_matched_result = [];
        foreach ($product_names as $key => $outerwrap) {
            foreach ($resultant_array[$key] as $key1 => $inner_wrap) {
                if(strtolower($outerwrap) == strtolower($inner_wrap)){
                    $found_matched_result[$key][$key1] = $inner_wrap;
                }
            }
            if (empty($found_matched_result[$key])) {
                foreach ($resultant_array[$key] as $key1 => $inner_wrap) {
                    similar_text(strtolower($outerwrap), strtolower($inner_wrap), $percent);
                    if ($percent >= 70) {
                        $found_matched_result[$key][$key1] = $inner_wrap;
                    }
                }
            }
            if (!array_key_exists($key, (array)$found_matched_result)){
                $found_matched_result[$key] = $this->final_brand_product_from_google($outerwrap, $resultant_array[$key], $key);
            }
        }

        @$final_record_to_show = [];
        if (is_array(@$found_matched_result) || is_object(@$found_matched_result)){
            foreach (@$found_matched_result as $key => $outerwrap) {
                if (is_array(@$outerwrap) || is_object(@$outerwrap)){
                    foreach (@$outerwrap as $key1 => $inner_wrap) {
                        if (!empty($inner_wrap)) {
                            if ($key1=="less_than_50") {
                                @$less_than_50[$key] = 'less_than_50';
                            }
                            @$final_record_to_show[$key][] = isset($final_array[$key][$key1]) ? $final_array[$key][$key1] : '';
                        }
                    }
                }
                if (!array_key_exists($key, (array)$final_record_to_show)){
                    @$final_record_to_show[$key][] = '';
                }
            }
        }
        
        $result_key = [];
        foreach ($final_record_to_show as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $inner_wrap) {
                if (!empty($inner_wrap)) {
                    if (stripos(strtolower(isset($inner_wrap['product_name']) ? $inner_wrap['product_name'] : ''), strtolower('edp')) !== false) {
                        $resultant_final_record_to_show[$key]['edp'][] = $inner_wrap;
                        $result_key[$key] = 'edp';
                    }
                    if (stripos(strtolower(isset($inner_wrap['product_name']) ? $inner_wrap['product_name'] : ''), strtolower('edt')) !== false) {
                        $resultant_final_record_to_show[$key]['edt'][] = $inner_wrap;
                        $result_key[$key] = 'edt';
                    }
                    if (stripos(strtolower(isset($inner_wrap['product_name']) ? $inner_wrap['product_name'] : ''), strtolower('edc')) !== false) {
                        $resultant_final_record_to_show[$key]['edc'][] = $inner_wrap;
                        $result_key[$key]= 'edc';
                    }
                    if (empty($resultant_final_record_to_show[$key])) {
                        $resultant_final_record_to_show[$key]['no_extra'][] = $inner_wrap;
                        $result_key[$key]= 'no_extra';
                    }
                }
                if (!array_key_exists($key, (array)$result_key)){
                    @$result_key[$key] = '';
                }
            }
        }

        $final_result_to_show_to_modal = [];
        foreach ($resultant_product_names as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $innerwrap) {
                $final_result_to_show_to_modal[$key] = isset($resultant_final_record_to_show[$key][$key1]) ? $resultant_final_record_to_show[$key][$key1] : '';
                if ($key1 == "no_extra") {
                    @$key2 = @$result_key[$key];
                    $final_result_to_show_to_modal[$key] = @$resultant_final_record_to_show[$key][$key2];
                }
            }
            if(empty($final_result_to_show_to_modal[$key])) {
                @$key2 = @$result_key[$key];
                if (@$final_result_to_show_to_modal[$key] == @$resultant_final_record_to_show[$key][$key2]) {
                    $final_result_to_show_to_modal[$key] = @$resultant_final_record_to_show[$key][$key2];
                }
            }
        }
        $result_to_return = array('final_result_to_show_to_modal' => $final_result_to_show_to_modal,'less_than_50' => @$less_than_50);
        return $result_to_return;
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
        $product_gender = [];
        foreach ($resultant_array as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $value) {
                $removeable_text[$key][] = isset($value[0]) ? $value[0] : '';
                $product_gender[$key] = isset($value[1][0]->gender) ? $value[1][0]->gender : '';
            }
        }

        $resultant_remaining_text = [];
        foreach ($remaining_text as $key => $value) {
            if (!empty($removeable_text[$key][0])) {
                $removeable_text_position = $this->textstrrpos($removeable_text[$key][0], $value); 
                $removeable_text_length = strlen($removeable_text[$key][0]);
                $result = substr_replace($value," ",$removeable_text_position, $removeable_text_length);
                $resultant_remaining_text[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $result);
            }else{
                $resultant_remaining_text[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value);
            }
        }

        $return_result = array('bottle_gender' => $product_gender, 'remaing_text_after_gender' => $resultant_remaining_text);
        return $return_result;
    }

    public function textstrrpos($search, $string){
        $position = strrpos($string, $search, 0); 
        if ($position == true){
            return $position;
        }
    }

    public function check_extra_text($decoded_uploaded_products){
        $new_result = [];
        if (is_array($decoded_uploaded_products) || is_object($decoded_uploaded_products)){
            foreach ($decoded_uploaded_products as $key => $value) {
                if (str_contains($value, "'") || str_contains($value, "’")) { 
                    $old_value = str_replace("'", ' ', $value);
                    $temp_value = str_replace("’", ' ', $old_value);
                    $new_result[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', strtolower($temp_value));
                }else{
                    $new_result[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', strtolower($value));
                }
            }
        }

        $remove_elements = array('edt','edp','edc');
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
            $remove_size_text[$key] = str_replace($before_trim_size_array[$key][0],"",$value);
            $bottle_sizes[$key] = preg_replace('/\D/', '', $before_trim_size_array[$key][0]);
        }

        $resultant_text_after_size_removed = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $remove_size_text);
        foreach ($resultant_text_after_size_removed as $key => $row) {
            preg_match('#\((.*?)\)#', $row, $match);
            $extra_paremeters_for_search[$key] = isset($match[0]) ? $match[0] : '' ;
        }

        $resultant_text = [];
        foreach ($resultant_text_after_size_removed as $key => $outerwrap) {
            if (strtolower($extra_paremeters_for_search[$key]) == '(w)' || strtolower($extra_paremeters_for_search[$key]) == '(m)') {
                $resultant_text[$key] = str_replace(array('(', ')'),"",$outerwrap);
            }else{
                $resultant_text[$key] = str_replace($extra_paremeters_for_search[$key],"",$outerwrap);
            }
            if (empty($resultant_text[$key])) {
                $resultant_text[$key][] = '';
            }
        }

        $resultant_product_names = [];
        if (is_array($decoded_uploaded_products) || is_object($decoded_uploaded_products)){
            foreach ($decoded_uploaded_products as $key => $product_name) {
                if (stripos(strtolower($product_name), "edp") !== false) {
                    @$resultant_product_names[$key]['edp'][] = $product_name;
                }
                if (stripos(strtolower($product_name), "edt") !== false) {
                    @$resultant_product_names[$key]['edt'][] = $product_name;
                }
                if (stripos(strtolower($product_name), "edc") !== false) {
                    @$resultant_product_names[$key]['edc'][] = $product_name;
                }
                if (empty($resultant_product_names[$key])) {
                    @$resultant_product_names[$key]['no_extra'][] = $product_name;
                }
            }
        }
        $return_result = array('bottle_sizes' => $bottle_sizes, 'remaing_text' => $resultant_text, 'resultant_product_names' => $resultant_product_names);
        return $return_result;
    }

    public function find_brand($data){
        $brand_names = DB::table('brands')->select('short_key', 'brand_name')->get()->toArray();
        $resultant_brands = [];
        
        foreach ($data as $key => $user_data) {
            if (!empty($user_data)) {
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
        }

        $product_names = [];
        $brand_names = [];
        foreach ($data as $key => $value) {
            $search = '/'.preg_quote(strtolower($resultant_brands[$key][0]), '/').'/';
            $product_name    = preg_replace($search, '', strtolower($value), 1);
            $product_names[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $product_name);
            $brand_names[$key]   = isset($resultant_brands[$key][1]) ? $resultant_brands[$key][1] : '';
            if (!empty($brand_names[$key])) {
                $brand_names[$key] = $brand_names[$key];
            }else{
                $brand_names[$key] = $this->get_brand_name_with_wrong_spellings($product_names[$key], $key);
            }
        }
        $final_array = array('product_names' => $product_names, 'final_brand_names' => $brand_names);
        return $final_array;
    }

    public function get_brand_name_with_wrong_spellings($data, $index){
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
                    $final_correct_spelling[$index] = $matches[0];
                }
            }
        }

        $brand_names = DB::table('brands')->select('short_key', 'brand_name')->get()->toArray();
        $resultant_brands = [];
        
        if(!empty(@$final_correct_spelling)){
            foreach (@$final_correct_spelling as $key => $user_data) {
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
        }

        $new_brand_names = [];
        if(!empty(@$final_correct_spelling)){
            foreach (@$final_correct_spelling as $key => $value) {
                $search = '/'.preg_quote(strtolower($resultant_brands[$key][0]), '/').'/';
                $product_name    = preg_replace($search, '', strtolower($value), 1);
                $new_brand_names   = isset($resultant_brands[$key][1]) ? $resultant_brands[$key][1] : '';
            }
        }
        if (!empty($new_brand_names)) {
            $temp_new_brand_names = $new_brand_names;
        }else{
            $temp_new_brand_names = '';
        }
        return $temp_new_brand_names;
    }

    public function cross_checked_brands($data){
        $brand_names = DB::table('brands')->select('short_key', 'brand_name')->get()->toArray();
        $resultant_brands = [];
        
        foreach ($data as $key => $user_data) {
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
        foreach ($data as $key => $value) {
            $search = '/'.preg_quote(strtolower($resultant_brands[$key][0]), '/').'/';
            $product_name    = preg_replace($search, '', strtolower($value), 1);
            $product_names[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $product_name);
        }
        return $product_names;
    }

    public function final_brand_product_from_google($data, $products_to_match, $index){
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
                    $final_correct_spelling[$index] = $matches[0];
                }
            }
        }

        $brand_names = DB::table('brands')->select('short_key', 'brand_name')->get()->toArray();
        $resultant_brands = [];
        
        if(!empty(@$final_correct_spelling)){
            foreach (@$final_correct_spelling as $key => $user_data) {
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
        }

        $product_names = [];
        if(!empty(@$final_correct_spelling)){
            foreach (@$final_correct_spelling as $key => $value) {
                $search = '/'.preg_quote(strtolower($resultant_brands[$key][0]), '/').'/';
                $product_name    = preg_replace($search, '', strtolower($value), 1);
                @$product_names[$index] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $product_name);
            }
        }else{
                @$product_names[$index] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $data);
        }

        $found_matched_result = [];
        $higest_percentage = [];
        if (!empty($product_names)) {
            foreach ($product_names as $key => $outerwrap) {
                foreach ($products_to_match as $key1 => $inner_wrap) {
                    if(strtolower($outerwrap) == strtolower($inner_wrap) || strtolower($data) == strtolower($inner_wrap)){
                        $found_matched_result[$key][$key1][] = $inner_wrap;
                    }
                }
                if (empty($found_matched_result[$key])) {
                    foreach ($products_to_match as $key1 => $inner_wrap) {
                        similar_text(strtolower($outerwrap), strtolower($inner_wrap), $percent);
                        if ($percent>30) {
                            $higest_percentage[$key][] = $percent;
                            $found_matched_result[$key][$percent][$key1] = $inner_wrap;
                        }
                    }
                }
            }
        }

        $first_higest_percentage = [];
        $second_higest_percentage = [];
        foreach ($higest_percentage as $key => $value) {
            if (count($value) > 1) {
                $max_1 = 0;
                $max_2 = 0;
                foreach ($value as $key1 => $value1) {
                    if ($value1 > $max_1) {
                        $max_2 = $max_1;
                        $max_1 = $value1;
                    }else if ($value1 > $max_2 && $value1 != $max_2) {
                        $max_2 = $value1;
                    }
                }
                $first_higest_percentage[$key] = floor($max_1).','.floor($max_2);
            }
        }

        $data_to_show = [];
        foreach ($found_matched_result as $key => $value) {
            if (count($value) > 1) {
                $temp_first_higest_percentage = explode(",", $first_higest_percentage[$key]);
                $data_to_show1 = $value[$temp_first_higest_percentage[0]];
                // $data_to_show2 = $value[$temp_first_higest_percentage[1]];
                // $data_to_show[$key] = $data_to_show1 + $data_to_show2;
                $data_to_show[$key] = $data_to_show1;
            }else{
                $maxs[$key] = max(array_keys($value));
                $data_to_show[$key] = $value[$maxs[$key]];
            }
        }

        if (!empty($data_to_show)) {
            $temp_data_to_show = $data_to_show[$index];
            $temp_data_to_show['less_than_50'] = 'less_than_50';
        }else{
            $temp_data_to_show = "";
        }
        return $temp_data_to_show;
    }

    public function find_brand_product_from_google($data, $index, $bottle_gender, $bottle_sizes){
        if (!str_contains($data, '+')){
            if (strlen($data) < 62) {
                $results = getKeywordSuggestionsFromGoogle($data);
            }
        }

        $resultant_data = [];
        if (is_array(@$results) || is_object(@$results)){
            foreach (@$results as $key => $innerwrap) {
                similar_text(strtolower($innerwrap), strtolower(@$data), $percent);
                $old_innerwrap = str_replace("'", '', $innerwrap);
                $new_innerwrap = str_replace("’", ' ', $old_innerwrap);
                $resultant_data[$percent] = $new_innerwrap;
            }
        }
        
        if(!empty($resultant_data)){
            $max_value_key = max(array_keys($resultant_data));
            $final_correct_spelling = [];
            foreach ($resultant_data as $key => $value) {
                if ($key == $max_value_key) {
                    $final_correct_spelling[$key] = $value;
                }
            }
        }

        $brand_names = DB::table('brands')->select('short_key', 'brand_name')->get()->toArray();
        $resultant_brands = [];
        
        if(!empty(@$final_correct_spelling)){
            foreach (@$final_correct_spelling as $key => $user_data) {
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
        }

        $product_names = [];
        $brand_names = [];
        if(!empty(@$final_correct_spelling)){
            foreach (@$final_correct_spelling as $key => $value) {
                $search = '/'.preg_quote(strtolower($resultant_brands[$key][0]), '/').'/';
                $product_name    = preg_replace($search, '', strtolower($value), 1);
                $product_names = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $product_name);
                $brand_names   = isset($resultant_brands[$key][1]) ? $resultant_brands[$key][1] : '';
            }
        }

        $products = Product::select('*');
        if (!empty($brand_names)){
            $products->where('brand_name', 'like', '%' . $brand_names . '%');
        }
        if (!empty($product_names)){
            if (strlen($product_names) < 3) {
                $products->where('product_name', 'like', '%' . $product_names . '%');
            }else{
                $products->whereRaw("MATCH(product_name) AGAINST('".$product_names."')");
            }
        }
        if (!empty($bottle_sizes)){
                $products->where('bottle_size', 'like', '%' . $bottle_sizes . '%');
        }
        if (!empty($bottle_gender)){   
            if (strtolower($bottle_gender =='male')) {
                $products->where('product_gender', '=',$bottle_gender);
            }else{
                $products->where('product_gender', 'like', '%' . $bottle_gender . '%');
            }
        }
        @$final_array = $products->get()->toArray();
        if (!empty($final_array)) {
            $temp_final_array[$index] = $final_array[0];
        }else{
            $temp_final_array[$index] = "";
        }
        return @$temp_final_array;
    }

    public function find_correct_brand($data){
        $results = [];
        if (is_array($data) || is_object($data)){
            foreach ($data as $key => $value) {
                if (!empty($value)) {
                    $results[$key] = getKeywordSuggestionsFromGoogle($value);
                }else{
                    $results[$key] = $value;
                }
            }
        }

        $resultant_data = [];
        if (is_array($results) || is_object($results)){
            foreach ($results as $key => $outerwrap) {
                if (is_array($outerwrap) || is_object($outerwrap)){
                    foreach ($outerwrap as $key1 => $innerwrap) {
                        similar_text(strtolower($innerwrap), strtolower($data[$key]), $percent);
                        if ($percent > 90) {
                            $old_innerwrap = str_replace("'", '', $innerwrap);
                            $new_innerwrap = str_replace("’", ' ', $old_innerwrap);
                            $resultant_data[$key][$percent] = $new_innerwrap;
                        }
                    }
                }
                if (!array_key_exists($key,(array)$resultant_data))
                    $resultant_data[$key][] = $data[$key];
            }
        }

        $final_correct_spelling = [];
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
                $resultant_brands[$key][] = $user_data;
            }
        }

        $brand_names = [];
        foreach ($final_correct_spelling as $key => $value) {
            $brand_names[$key] = isset($resultant_brands[$key][1]) ? $resultant_brands[$key][1] : $resultant_brands[$key][0];
        }
        return $brand_names;
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

    public function delete_customers_demands($id)
    {
       DB::table('customer_demand')->where('id', $id)->delete();
       return redirect()->route('customer-demand-page')->with('warning-message','Customers Demands Deleted Successfully');
    }

    public function customer_demand_page(Request $request)
    {
        if(request()->ajax()){
            if($request->filters==true){
                $user     = Auth::user();
                $added_by = $user->id;
                $data =  $customer_demand = DB::table('customer_demand')->leftjoin('companies', 'customer_demand.company_name', '=', 'companies.company_name')->select('customer_demand.id','customer_demand.company_name','customer_demand.date', 'companies.city', 'companies.state', 'companies.country', 'companies.company_type')->where('companies.active', '=', 1)->where('customer_demand.added_by', '=', $added_by);
                   if (!empty($request->input('country')))
                        $data->whereIn('companies.country', $request->input('country', []));
                   if (!empty($request->input('state')))
                       $data->whereIn('companies.state', $request->input('state', []));
                   if (!empty($request->input('city')))
                       $data->whereIn('companies.city', $request->input('city', []));
                   if (!empty($request->input('company_name')))
                       $data->whereIn('companies.company_name', $request->input('company_name', []));
                   $data = $data->get();
                return datatables()->of($data)->make(true);
            }else{
                $data = DB::table('customer_demand')->leftjoin('companies', 'customer_demand.company_name', '=', 'companies.company_name')->select('customer_demand.id','customer_demand.company_name','customer_demand.date', 'companies.city', 'companies.state', 'companies.country', 'companies.company_type')->where('companies.active', '=', 1)->where('customer_demand.added_by', '=', $added_by)->get();
                return datatables()->of($data)->make(true);
            }            
        }
            $user         = Auth::user();
            $added_by     = $user->id;
            $title        = 'Customer Demand Page';
            $country_data = Company::select('country')->groupBy('country')->where('country', '!=', Null)->get();
            $state_data   = Company::select('state')->groupBy('state')->where('state', '!=', Null)->get();
            $city_data    = Company::select('city')->groupBy('city')->where('city', '!=', Null)->get();
        return view('admin.customer-demand.index', compact('title', 'country_data', 'state_data', 'city_data'));
    }

    public function view_customers_demands($id)
    {
        $customer_demand = DB::table('customer_demand')->where('id', '=', $id)->get()->toArray();
        foreach ($customer_demand as $key => $value) {
            $company_name   = $value->company_name;
            $city           = $value->city;
            $date           = $value->date;
            $qtys           = json_decode($value->qty);
            $prices         = json_decode($value->price);
        }

        $all_data = DB::table('customer_demand_detail')->where('customer_demand_id', '=', $customer_demand[0]->id)->get()->toArray();
        foreach ($all_data as $key => $value) {
            $product_names[] = $value->product_name;
        }

        $count = 0;
        foreach ($product_names as $key => $product_name) {
            $result_record[$count] = Product::select('id')->where('product_name', $product_name)->orderBy('id', 'ASC')->first()->toArray();
            $result_record[$count]['products'] = $product_name;
            $result_record[$count]['barcodes'] = Product::select('barcode')->where('product_name', $product_name)->orderBy('id', 'ASC')->first()->toArray();
            $result_record[$count]['brand_names'] = Product::select('brand_name')->where('product_name', $product_name)->orderBy('id', 'ASC')->first()->toArray();
            $count++;
        }
        $data = [
            'title'          => $company_name.' Demands Details',
            'company_name'   => $company_name,
            'city'           => $city,
            'date'           => $date,
            'result_records' => $result_record,
            'qtys'           => $qtys,
            'prices'         => $prices,
        ];
        return view('admin.customer-demand.customers_demands_details', $data);
    }

    public function update_customer_demand_page_data(Request $e)
    {
        $user                 = Auth::user();
        $added_by             = $user->id;
        $old_company_name     = $e->input('old_company_name');
        $company_name         = $e->input('company_name');
        $register_new_company = $e->input('register_new_company');
        $user_products        = $e->input('user_products');
        $matchable_products   = $e->input('matchable_products');
        $register_new_product = $e->input('register_new_product');
        $last_temp_brand_name = DB::table('temp_customer_demand')
            ->select('*')
            ->where('brand_name', '!=', Null)
            ->where('added_by', '=', $added_by)
            ->orderBy('id', 'DESC')
            ->first();
        $customers_brand_name   = $this->find_correct_brand(json_decode($last_temp_brand_name->brand_name));
        $update_resultant_brands = [
         'brand_name'     => json_encode($customers_brand_name),
        ];
        DB::table('temp_customer_demand')->where('id', $last_temp_brand_name->id)->update($update_resultant_brands);

        if (!empty($register_new_company)) {
            $company_already_exist = DB::table('companies')
            ->select('company_name')
            ->where('company_name', '=', $register_new_company)
            ->first();
            if (empty($company_already_exist)) {
                DB::table('companies')->insert([
                    'company_name' => $register_new_company,
                    'active'       => 0,
                    'added_by'     => $added_by,
                ]);
            }
        }

        if ($company_name=="No Company Found") {
            $company_already_exist = DB::table('companies')
            ->select('company_name')
            ->where('company_name', '=', $register_new_company)
            ->first();
            if (empty($company_already_exist)) {
                DB::table('companies')->insert([
                    'company_name' => $old_company_name,
                    'active'       => 0,
                    'added_by'     => $added_by,
                ]);
            }
        }

        foreach ($user_products as $key => $temp_inner_wrap) {
            $result = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', strtolower($temp_inner_wrap));
            $old_result = str_replace("'", ' ', $result);
            $inner_wrap = str_replace("’", ' ', $old_result);
            $register_new_product_key = @$register_new_product[$key];
            if (isset($register_new_product_key)) {
                if($key == @$register_new_product_key){
                    $product_already_exist = DB::table('products')
                    ->select('product_name')
                    ->where('product_name', '=', $inner_wrap)
                    ->first();
                    $user_demand_products[$key] = $inner_wrap;
                    if (empty($product_already_exist)) {
                        DB::table('products')->insert([
                            'product_name' => $inner_wrap,
                            'active'       => 0,
                            'added_by'     => $added_by,
                        ]);
                    }
                }
            }
            if (!empty(@$matchable_products[$key])) {
                $user_demand_products[$key] = @$matchable_products[$key];
            }else{
                $product_already_exist = DB::table('products')
                ->select('product_name')
                ->where('product_name', '=', @$inner_wrap)
                ->first();
                if (empty($product_already_exist)) {
                    DB::table('products')->insert([
                        'product_name' => $inner_wrap,
                        'active'       => 0,
                        'spell_check'  => 1,
                        'added_by'     => $added_by,
                    ]);
                }
                $user_demand_products[$key] = @$inner_wrap;
            }
        }

        $last_company_data_inserted = DB::table('temp_customer_demand')
        ->select('*')
        ->where('company_name', '=', $old_company_name)
        ->where('added_by', '=', $added_by)
        ->first();
        
        if ($company_name=="No Company Found") {
            $company_name = $old_company_name;
        }else{
            $company_name = $company_name;
        }

        $already_customer_demand_company = DB::table('customer_demand')
        ->select('*')
        ->where('company_name', '=', $company_name)
        ->where('added_by', '=', $added_by)
        ->first();
        if (!empty($already_customer_demand_company)) {
            $last_customer_demand_products = DB::table('customer_demand_detail')
            ->select('product_name')
            ->where('customer_demand_id', '=', $already_customer_demand_company->id)
            ->get()->toArray();
            foreach ($last_customer_demand_products as $key => $inner_wrap) {
                $temp_old_products[] = $inner_wrap->product_name;
            }
            $result_products = array_merge($temp_old_products,$user_demand_products);
            $resultant_products = array_unique($result_products);
            DB::table('customer_demand_detail')->where('customer_demand_id', @$already_customer_demand_company->id)->delete();
            foreach ($resultant_products as $key => $product_name) {
                DB::table('customer_demand_detail')->insert([
                    'customer_demand_id' => $already_customer_demand_company->id,
                    'product_name' => $product_name,
                ]);
            }
           $result = 1;
       }else{
        $result = DB::table('customer_demand')->insert([
            'company_name' => $company_name,
            'barcode'      => $last_company_data_inserted->barcode,
            'brand_name'   => $last_company_data_inserted->brand_name,
            'product_name' => $last_company_data_inserted->product_name,
            'country'      => $last_company_data_inserted->country,
            'city'         => $last_company_data_inserted->city,
            'qty'          => $last_company_data_inserted->qty,
            'date'         => $last_company_data_inserted->date,
            'price'        => $last_company_data_inserted->price,
            'added_by'     => $added_by
        ]);

            $last_customer_demand_id = DB::table('customer_demand')
            ->select('id')
            ->where('added_by', '=', $added_by)
            ->orderBy('id', 'DESC')
            ->first();

            foreach ($user_demand_products as $key => $product_name) {
                DB::table('customer_demand_detail')->insert([
                    'customer_demand_id' => $last_customer_demand_id->id,
                    'product_name' => $product_name,
                ]);
            }
        }

        DB::table('temp_customer_demand')->where('company_name', @$last_company_data_inserted->company_name)->where('added_by', $added_by)->delete();
        echo json_encode(array('result' => $result));
        exit;
    }
}
