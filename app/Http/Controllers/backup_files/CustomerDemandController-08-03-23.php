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
use DateTime;

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

    public function delete_this_customers_demands(Request $e)
    {
        $id = $e->data['id'];
        $result = DB::table('customer_demand')->where('id', $id)->delete();
        DB::table('customer_demand_detail')->where('customer_demand_id', $id)->delete();
        $result = array('result' => true, 'message' => "Customers Demands Deleted Successfully");
        echo json_encode($result);
        exit;
    }

    public function save_this_customers_demands(Request $e)
    {
        $id = $e->data['id'];
        $update_this_customer_demand_data = [
         'active'     => 0,
        ];
        DB::table('customer_demand')->where('id', $id)->update($update_this_customer_demand_data);
        $result = array('result' => true, 'message' => "Saved Demands Deleted Successfully");
        echo json_encode($result);
        exit;
    }

    public function get_all_customers_demands(Request $e){
        $user = Auth::user();
        $added_by = $user->id;
        $customer_demands = DB::table('customer_demand')->select('*')->where('added_by', $added_by)->groupBy('company_name')->where('active', 1)->get();
        ob_start(); ?>
                    <?php  
                    $i=1;
                    if (count($customer_demands)>0) {
                    foreach ($customer_demands as $key => $e) { ?>
                   <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $e->company_name; ?></td>
                        <td><?php echo $e->created_at; ?></td>
                        <td class="btn-group" style="display: flex;">
                            <a href="javascript:void(0)" data-file_name_id="<?php echo $e->id; ?>" class="btn btn-info btn-xs" onclick="this_save_customer_demands(<?php echo $e->id; ?>)">
                                <i class="fa fa-plus"></i>
                            </a>
                            <a href="javascript:void(0)" data-file_name_id="<?php echo $e->id; ?>" class="btn btn-primary btn-xs" onclick="this_customer_demands(<?php echo $e->id; ?>)">
                                <i class="fa fa-search"></i>
                            </a>
                            <a href="javascript:void(0)" data-file_name_id="<?php echo $e->id; ?>" class="btn btn-danger btn-xs" onclick="this_delete_customer_demands(<?php echo $e->id; ?>)">
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

    public function customer_demand_uploading()
    {
        $title = 'Uploading Customer Demands';
        $data = [
            'title' => $title,
        ];
        return view('admin.customer-demand.customer_demand_uploading', $data);
    }

    public function customer_demand_files()
    {
        $title = 'Uploading Customer Files';
        $user = Auth::user();
        $added_by = $user->id;
        $data = [
            'title' => $title,
            'customer_demands' => DB::table('customer_demand')->select('*')->where('added_by', $added_by)->where('active', 0)->get()->toArray(),
        ];
        return view('admin.customer-demand.customer_demand_files', $data);
    }

    public function customer_file_uploading(Request $request)
    {
        $the_file = $request->file('file');
        $extension = $the_file->extension();
        if('csv' == $extension){     
           $reader       = IOFactory::createReader('Csv');
           $reader->setReadDataOnly(TRUE);
           $spreadsheet  = $reader->load($the_file->getRealPath());
        }elseif('xls' == $extension) {     
            $reader       = IOFactory::createReader('Xls');
            $reader->setReadDataOnly(TRUE);
            $spreadsheet  = $reader->load($the_file->getRealPath());
        }else{    
            $reader       = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(TRUE);
            $spreadsheet  = $reader->load($the_file->getRealPath());
        }
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
            $companies_result_data = DB::table('temp_customer_demand')->select('*')->where('added_by', '=', $added_by)->orderBy('id', 'DESC')->first();
            $old_product_result = json_decode($companies_result_data->product_name);
            $final_data_found   = $this->company_and_products_searching($companies_result_data);
            $result_to_show     = $this->make_html_for_customer_demands($final_data_found);
            echo json_encode($result_to_show);
            exit();
        }else{
            $arr = array('message' => 'Please Correct File Heading', 'title' => 'Error', 'status' => false);
            echo json_encode($arr);
            exit;
        }
    }

    // function ImportCustomerDemand($resultant_array){
    //     foreach (@$resultant_array as $key => $outerwrap) {
    //         foreach ($outerwrap as $key1 => $innerwrap) {
    //             $new_innerwrap = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
    //             if (strtolower($new_innerwrap) == "barcode") {
    //                 $headings_used_by[$key][$key1] = 'barcode';
    //             }

    //             if (strtolower($new_innerwrap) == "brand") {
    //                 $headings_used_by[$key][$key1] = 'brand';
    //             }

    //             if (strtolower($new_innerwrap) == "product description") {
    //                 @$counter = [$key];
    //                 $headings_used_by[$key][$key1] = 'product description';
    //             }

    //             if (strtolower($new_innerwrap) == "qty") {
    //                 $headings_used_by[$key][$key1] = 'qty';
    //             }

    //             if (strtolower($new_innerwrap) == "price") {
    //                 $headings_used_by[$key][$key1] = 'price';
    //             }
    //         }
    //     }


    //     $date = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $resultant_array[0]['B']);
    //     $unix_date = ($date - 25569) * 86400;
    //     $date = 25569 + ($unix_date / 86400);
    //     $unix_date = ($date - 25569) * 86400;
    //     $date = gmdate("Y-m-d", $unix_date);
    //     $new_date = date('Y-m-d',strtotime($date));
    //     $company_name = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $resultant_array[1]['B']);
    //     $country = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $resultant_array[2]['B']);
    //     $city = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $resultant_array[3]['B']);

    //     for ($i=0; $i <= @$counter[0] ; $i++) {
    //         unset($resultant_array[$i]);
    //         @$new_resultant_array    = array_values($resultant_array);
    //         $row   = @$headings_used_by[$i];
    //         if(!empty($row)){
    //             @$new_headings_used_by = @$row;
    //         }
    //     }

    //     foreach (@$new_resultant_array as $key => $value) {
    //         foreach (@$new_headings_used_by as $key1 => $innerwrap) {
    //             if ($innerwrap == "barcode") {
    //                 @$barcodes[$key] = $value[$key1];
    //             }
                
    //             if ($innerwrap == "brand") {
    //                 @$brands[$key] = $value[$key1];
    //             }
                
    //             if ($innerwrap == "product description") {
    //                 if (!empty($value[$key1])) {
    //                     @$product_description[$key] = $value[$key1];
    //                 }
    //             }

    //             if ($innerwrap == "qty") {
    //                 @$qty[$key] = $value[$key1];
    //             }

    //             if ($innerwrap == "price") {
    //                 @$price[$key] = $value[$key1];
    //             }
    //         }
    //     }

    //     $barcode                 = json_encode(@$barcodes);
    //     $qty                     = json_encode(@$qty);
    //     $price                   = json_encode(@$price);
    //     $resultant_customer_data = json_encode(@$product_description);
    //     $resultant_brand_name    = json_encode(@$brands);
    //    
    //     
    //     $result = DB::table('temp_customer_demand')->insert([
    //         'company_name'  => @$company_name,
    //         'country'       => @$country,
    //         'city'          => @$city,
    //         'barcode'       => @$barcode,
    //         'brand_name'    => @$resultant_brand_name,
    //         'qty'           => @$qty,
    //         'date'          => @$new_date,
    //         'price'         => @$price,
    //         'product_name'  => @$resultant_customer_data,
    //         'added_by'      => !empty($added_by) ? $added_by : '',
    //     ]);
    //     return $result;
    // }

    function ImportCustomerDemand($resultant_array){
        foreach (@$resultant_array as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $innerwrap) {
                $new_innerwrap = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
                if (strtolower($new_innerwrap) == "date") {
                    $headings_used_by[$key][$key1] = 'date';
                }

                if (strtolower($new_innerwrap) == "product gender") {
                    $headings_used_by[$key][$key1] = 'product gender';
                }

                if (strtolower($new_innerwrap) == "product type") {
                    $headings_used_by[$key][$key1] = 'product type';
                }

                if (strtolower($new_innerwrap) == "company name") {
                    $headings_used_by[$key][$key1] = 'company name';
                }

                if (strtolower($new_innerwrap) == "country") {
                    $headings_used_by[$key][$key1] = 'country';
                }

                if (strtolower($new_innerwrap) == "city") {
                    $headings_used_by[$key][$key1] = 'city';
                }

                if (strtolower($new_innerwrap) == "location") {
                    $headings_used_by[$key][$key1] = 'location';
                }
                
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

        @$old_resultant_array = $resultant_array;
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
                if (strtolower(@$old_resultant_array[0]['A']) == "date") {
                    @$old_temp_dates1 = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $old_resultant_array[0]['B']);
                    if (!is_numeric($old_temp_dates1)) {
                        $new_old_temp_dates1 = new DateTime(@$old_temp_dates1);
                        $temp_dates1[$key] = $new_old_temp_dates1->format('Y-m-d');
                    }else{
                        $unix_date = ($old_temp_dates1 - 25569) * 86400;
                        $date = 25569 + ($unix_date / 86400);
                        $unix_date = ($date - 25569) * 86400;
                        $date = gmdate("Y-m-d", $unix_date);
                        $temp_dates1[$key] = date('Y-m-d',strtotime($date));
                    }
                }
                if ($innerwrap == "date") {
                    @$temp_dates2[$key] = $value[$key1];
                }

                if ($innerwrap == "product gender") {
                    @$product_genders[$key] = $value[$key1];
                }

                if ($innerwrap == "product type") {
                    @$product_types[$key] = $value[$key1];
                }

                if ($innerwrap == "barcode") {
                    @$barcodes[$key] = $value[$key1];
                }

                if (strtolower(@$old_resultant_array[1]['A']) == "company name") {
                    @$old_temp_companies1 = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $old_resultant_array[1]['B']);
                    @$temp_companies1[0] = $old_temp_companies1;
                }
                if ($innerwrap == "company name") {
                    @$temp_companies2[$key] = $value[$key1];
                }

                if (strtolower(@$old_resultant_array[2]['A']) == "country") {
                    @$old_temp_country1 = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $old_resultant_array[2]['B']);
                    @$temp_country1[0] = $old_temp_country1;
                }
                if ($innerwrap == "country") {
                    @$temp_country2[$key] = $value[$key1];
                }

                if (strtolower(@$old_resultant_array[3]['A']) == "city") {
                    @$old_temp_city1 = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $old_resultant_array[3]['B']);
                    @$temp_city1[0] = $old_temp_city1;
                }
                if ($innerwrap == "city") {
                    @$temp_city2[$key] = $value[$key1];
                }

                if ($innerwrap == "location") {
                    @$locations[$key] = $value[$key1];
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

        if (is_array(@$temp_dates2) || is_object(@$temp_dates2)){
            foreach (@$temp_dates2 as $key => $value) {
                if (gettype($value)=="integer") {
                    $unix_date = ($value - 25569) * 86400;
                    $value = 25569 + ($unix_date / 86400);
                    $unix_date = ($value - 25569) * 86400;
                    @$old_temp_dates2 = gmdate("Y-m-d", $unix_date);
                    $temp_dates2[$key] = date('Y-m-d',strtotime($old_temp_dates2));
                    $datediff= time() - strtotime($old_temp_dates2);
                    $temp_dates2_days[$key] = round($datediff / (60 * 60 * 24));
                }else{
                    $datediff= time() - strtotime($value);
                    $temp_dates2_days[$key] = round($datediff / (60 * 60 * 24));
                    $temp_dates2[$key] = $value;
                }
            }
        }

        $user = Auth::user();
        $added_by = $user->id;
        if (!empty($product_description) && !empty(@$temp_companies1 || @$temp_companies2 )) {
            for ($i=0; $i < 1; $i++) {
                $result = DB::table('temp_customer_demand')->insert([
                    'company_name'   => !empty(@$temp_companies1) ? json_encode(@$temp_companies1) : json_encode(@$temp_companies2),
                    'country'        => !empty(@$temp_country1) ? json_encode(@$temp_country1) : json_encode(@$temp_country2),
                    'city'           => !empty(@$temp_city1) ? json_encode(@$temp_city1) : json_encode(@$temp_city2),
                    'location'       => json_encode(@$locations),
                    'product_gender' => json_encode(@$product_genders),
                    'product_type'   => json_encode(@$product_types),
                    'barcode'        => json_encode(@$barcodes),
                    'brand_name'     => json_encode(@$brands),
                    'qty'            => json_encode(@$qty),
                    'date'           => !empty(@$temp_dates1) ? json_encode(@$temp_dates1) : json_encode(@$temp_dates2),
                    'price'          => json_encode(@$price),
                    'product_name'   => json_encode(@$product_description),
                    'added_by'       => !empty($added_by) ? $added_by : '',
                ]);
            }
            return 1;
        }else{
            DB::table('temp_customer_demand')->where('added_by', '=', $added_by)->orderBy('id', 'DESC')->delete();
            return 0;
        }
    }

    public function make_html_for_customer_demands($resultant_products_found){
        foreach ($resultant_products_found['final_result_to_show_to_modal'] as $key => $value) {
            foreach ($resultant_products_found['final_customers_company_name'][$key] as $key1 => $founded_company) {
                $final_data[$key]['companies'][] = $founded_company;
                $final_data[$key]['db_products'] = $value;
            }
            foreach ($resultant_products_found['company_based_record_products'][$key] as $key2 => $products) {
                if (!empty(@$resultant_products_found['less_than_50'][$key][$key2])) {
                    $final_data[$key]['user_products'][$key2]['less_than_50'] = $products['product_name'];
                }else{
                    $final_data[$key]['user_products'][$key2]['greater_than_50'] = $products['product_name'];
                }
            }
        }

        ob_start(); ?>
        <?php $i=0;
        foreach (@$final_data as $key => $outerwrap) { ?>
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <input type="hidden" name="old_company_name[<?php echo @$i; ?>]" value='<?php echo @$key.'||'.@$outerwrap['companies'][0]['founded_country'].'||'.@$outerwrap['companies'][0]['founded_city']; ?>'>
                    <label style="font-weight: 800;font-family: inherit;"><?php echo @$key; ?></label>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <select class="form-control" name="company_name[<?php echo @$i; ?>]" id="company_name">
                        <?php foreach (@$outerwrap['companies'] as $key1 => $companies) {  ?>
                            <option value='<?php echo @$companies['founded_company_name'].'||'.@$companies['founded_country'].'||'.@$companies['founded_city']; ?>'><?php echo @$companies['founded_company_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <input type="checkbox" name="register_new_company[<?php echo @$i; ?>]" id="register_new_company" value='<?php echo @$key.'||'.@$outerwrap['companies'][0]['founded_country'].'||'.@$outerwrap['companies'][0]['founded_city']; ?>'>
                </div>
            </div>
            <br>
            <?php $j=0; ?>
            <?php if (is_array(@$outerwrap['user_products']) || is_object(@$outerwrap['user_products'])){
                foreach (@$outerwrap['user_products'] as $key2 => $user_products) { 
                    foreach ($user_products as $key3 => $final_user_products) { ?>
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <input type="hidden" name='user_products[<?php echo @$i; ?>][<?php echo @$j; ?>]' value="<?php echo @$final_user_products; ?>">
                                <label style="font-weight: 600;"><?php echo @$final_user_products; ?></label>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <select class="form-control" name="matchable_products[<?php echo @$i; ?>][<?php echo @$j; ?>]" id="matchable_products">
                                    <?php if (is_array(@$outerwrap['db_products'][$key2]) || is_object(@$outerwrap['db_products'][$key2])){
                                        foreach (@$outerwrap['db_products'][$key2] as $key4 => $old_db_products) { 
                                            if (is_array(@$old_db_products) || is_object(@$old_db_products)){
                                                foreach ($old_db_products as $key5 => $db_products) {
                                                    if ($key3 == "greater_than_50") { ?>
                                                      <option value="<?php echo @$db_products['product_name'].'||'.@$db_products['id']; ?>"><?php echo '('.@$db_products['brand_name'].') '.@$db_products['product_name']; ?></option>
                                                    <?php }else{ ?>
                                                    <option value="<?php echo @$db_products['product_name'].'||'.@$db_products['id']; ?>"><?php echo '('.@$db_products['brand_name'].') '.@$db_products['product_name'].'*'; ?></option>
                                                    <?php }
                                                }
                                            }   
                                        }
                                    }else{ ?>
                                        <option value="no">No product Found</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <input type="checkbox" name='register_new_product[<?php echo @$i; ?>][<?php echo @$j; ?>]' id="register_new_product" value="<?php echo @$final_user_products; ?>">
                            </div>
                        </div>
                        <br>
                <?php $j++; } } } ?>
        <?php $i++; }
        $res = ob_get_clean();
        echo json_encode(array('html' => $res, 'status' => true));
        exit;
    }

    public function company_and_products_searching($result)
    {
        $customers_company_name   = json_decode($result->company_name);
        $customers_barcode        = json_decode($result->barcode);
        $customers_brand_name     = json_decode($result->brand_name);
        $customers_product_name   = json_decode($result->product_name);
        $customers_product_type   = json_decode($result->product_type);
        $customers_product_gender = json_decode($result->product_gender);
        $customers_country        = json_decode($result->country);
        $customers_city           = json_decode($result->city);
        $customers_location       = json_decode($result->location);
        foreach ($customers_product_name as $key => $value) {
            $final_data[$key]['company_name']   = !empty(@$customers_company_name[$key]) ? preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$customers_company_name[$key]) : preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$customers_company_name[0]);
            $final_data[$key]['barcode']        = @$customers_barcode[$key];
            $final_data[$key]['brand_name']     = @$customers_brand_name[$key];
            $final_data[$key]['product_name']   = @$value;
            $final_data[$key]['product_type']   = @$customers_product_type[$key];
            $final_data[$key]['product_gender'] = @$customers_product_gender[$key];
            $final_data[$key]['country']        = !empty(@$customers_country[$key]) ? @$customers_country[$key] : @$customers_country[0];
            $final_data[$key]['city']           = !empty(@$customers_city[$key]) ? @$customers_city[$key] : @$customers_city[0];
            $final_data[$key]['location']       = @$customers_location[$key];
        }
        
        foreach ($final_data as $key => $value) {
            $company_based_record_barcode[$value['company_name']][$key]['barcode'] = $value['barcode'];
            $company_based_record[$value['company_name']][$key] = $value;
            $company_based_record_products[$value['company_name']][$key]['product_name'] = $value['product_name'];
            $company_based_record_product_types[$value['company_name']][$key]['product_type'] = $value['product_type'];
            $company_based_record_product_genders[$value['company_name']][$key]['product_gender'] = $value['product_gender'];
        }

        
        $resultant_company_data   = $this->company_searching($company_based_record);
        foreach ($resultant_company_data as $key => $outerwrap) {
            $final_customers_company_name[$key] = $outerwrap['company_data'];
            $final_product_types[$key]          = $company_based_record_product_types[$key];
            $final_product_genders[$key]        = $company_based_record_product_genders[$key];
            $new_customers_brand_name[$key]     = $this->find_correct_brand($outerwrap['company_data_products']);
            $if_check_extra_text[$key]          = $this->check_extra_text($outerwrap['company_data_products']);
            $remaining_text[$key]               = $this->check_product_gender($if_check_extra_text[$key]['remaing_text']);
            $if_brand_data[$key]                = $this->find_brand($remaining_text[$key]['remaing_text_after_gender']);
            $final_product_names[$key]          = $this->cross_checked_brands($if_brand_data[$key]['product_names']);
        }

        $final_array = array();
        foreach ($final_product_names as $key => $old_products_name) {
            foreach ($old_products_name as $key1 => $products_name) {
            $brand_name            = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$if_brand_data[$key]['final_brand_names'][$key1]);
            $product_name          = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$products_name);
            $product_gender        = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$remaining_text[$key]['bottle_gender'][$key1]);
            $bottle_size           = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$if_check_extra_text[$key]['bottle_sizes'][$key1]);
            $product_type          = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$final_product_types[$key][$key1]['product_type']);
            $file_product_genders  = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$final_product_genders[$key][$key1]['product_gender']);
            $products = Product::select('*');
            if (!empty(@$company_based_record_barcode[$key]['barcode'])){
                    $products->where('barcode', '=', @$company_based_record_barcode[$key]['barcode'][$key1]);
            }else{
                if (!empty(@$new_customers_brand_name[$key][$key1])){
                    $products->where('brand_name', 'like', '%' . @$new_customers_brand_name[$key][$key1] . '%');
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
                if (!empty($product_type)){
                        $products->where('product_type', 'like', '%' . $product_type . '%');
                }
                if (!empty($file_product_genders)){
                    if (!empty($file_product_genders)){
                        if (strtolower($file_product_genders =='male')) {
                            $products->where('product_gender', '=',$file_product_genders);
                        }else{
                            $products->where('product_gender', 'like', '%' . $file_product_genders . '%');
                        }
                    }
                }else{
                    if (!empty($product_gender)){
                        if (strtolower($product_gender =='male')) {
                            $products->where('product_gender', '=',$product_gender);
                        }else{
                            $products->where('product_gender', 'like', '%' . $product_gender . '%');
                        }
                    }
                }
            }
            $final_array[$key][$key1] = $products->get()->toArray();
                if (empty($final_array[$key])) {
                    $final_array[$key] =  $this->find_brand_product_from_google(@$remaining_text[$key]['remaing_text_after_gender'][$key1], $key, @$remaining_text['bottle_gender'][$key1], @$if_check_extra_text[$key]['bottle_sizes'][$key1]);
                }
            }
        }
        $result_to_show = $this->get_most_matchable_products($final_array, $final_product_names, $if_check_extra_text, $final_customers_company_name, $company_based_record_products);
        return($result_to_show);
    }

    public function get_most_matchable_products($final_array, $product_names, $resultant_product_names_array, $final_customers_company_name, $company_based_record_products){
        $results = [];
        if (is_array(@$final_array) || is_object(@$final_array)){
            foreach (@$final_array as $key => $inner_wrap) {
                if (is_array(@$inner_wrap) || is_object(@$inner_wrap)){
                    foreach ($inner_wrap as $key1 => $inner_value) {
                        if (!empty($inner_value)) {
                        foreach ($inner_value as $key2 => $most_inner_value) {
                            if (!empty($most_inner_value['product_name'])) {
                                $remove_elements = array('edt','edp','edc');
                                $result= str_replace($remove_elements,"",strtolower(@$most_inner_value['product_name']));
                                $results[$key][$key1][$key2] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $result);
                            }else{
                                $results[$key][$key1][$key2] = "";
                            }
                        }
                        }else{
                            $results[$key][]= array();
                        }
                    }
                }
            }
        }

        if (is_array(@$final_array) || is_object(@$final_array)){
            foreach (@$final_array as $key => $inner_wrap) {
                if (is_array(@$inner_wrap) || is_object(@$inner_wrap)){
                    foreach ($inner_wrap as $key1 => $inner_value) {
                        if (!empty($inner_value)) {
                        foreach ($inner_value as $key2 => $most_inner_value) {
                            if (!empty($most_inner_value['product_name'])) {
                                preg_match_all('/\d+(?:\s*x\s*\d+)*\s*ml\b/i', $most_inner_value['product_name'], $matches);
                                $before_trim_size_array[$key][$key1][$key2] = $matches[0];
                            }else{
                                $before_trim_size_array[$key][] = "";
                            }
                        }
                        }else{
                            $before_trim_size_array[$key][]= array();
                        }
                    }
                }
            }
        }
        
        $temp_resultant_array = [];
        if (is_array($results) || is_object($results)){
            foreach ($results as $key => $inner_wrap) {
                foreach ($inner_wrap as $key1 => $inner_value) {
                    if (!empty($inner_value)) {
                        foreach ($inner_value as $key2 => $most_inner_value) {
                            $temp_remove_size_text = str_replace(isset($before_trim_size_array[$key][$key1][$key2][0]) ? $before_trim_size_array[$key][$key1][$key2][0] : '',"",$inner_value);
                            $temp_resultant_array[$key][$key1] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $temp_remove_size_text);
                        }
                    }else{
                        $temp_resultant_array[$key][] = array();
                    }
                }
                if (!array_key_exists($key, (array)$temp_resultant_array)){
                    $temp_resultant_array[$key][] = array();
                }
            }
        }

        $resultant_array = [];
        if (is_array($temp_resultant_array) || is_object($temp_resultant_array)){
            foreach ($temp_resultant_array as $key => $inner_wrap) {
                foreach ($inner_wrap as $key1 => $inner_value) {
                    if (!empty($inner_value)) {
                    foreach ($inner_value as $key2 => $most_inner_value) {
                        $remove_elements = array('men','man','female','male','women','woman');
                        $new_result= str_replace($remove_elements,"",strtolower($most_inner_value));
                        $resultant_array[$key][$key1][$key2] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $new_result);
                    }
                    }else{
                        $resultant_array[$key][] = array();
                    }
                }
                if (!array_key_exists($key1,(array)$results)){
                    $resultant_array[$key][] = array();
                }
            }
        }

        // $final_product_names = $this->find_brand($product_names);
        $found_matched_result = [];
        foreach ($product_names as $key => $outerwrap) {
            foreach ($resultant_array[$key] as $key1 => $inner_wrap) {
                if (!empty(@$outerwrap[$key1])) {
                    foreach ($inner_wrap as $key2 => $most_inner_value) {
                        if(strtolower(@$outerwrap[$key1]) == strtolower(@$most_inner_value)){
                            $found_matched_result[$key][$key1][$key2] = $most_inner_value;
                        }
                    }
                    if (empty($found_matched_result[$key][$key1])) {
                        foreach ($inner_wrap as $key2 => $most_inner_value) {
                            similar_text(strtolower(@$outerwrap[$key1]), strtolower($most_inner_value), $percent);
                            if ($percent >= 70) {
                                $found_matched_result[$key][$key1][$key2] = $most_inner_value;
                            }
                        }
                    }
                    if (empty($found_matched_result[$key][$key1])) {
                        $found_matched_result[$key][$key1] = $this->final_brand_product_from_google(@$outerwrap[$key1], $inner_wrap, $key1);
                    }
                }else{
                    foreach ($inner_wrap as $key2 => $most_inner_value) {
                        if(strtolower(@$resultant_product_names_array[$key]['remaing_text'][$key1]) == strtolower(@$most_inner_value)){
                            $found_matched_result[$key][$key1][$key2] = $most_inner_value;
                        }
                    }
                    if (empty($found_matched_result[$key][$key1])) {
                        foreach ($inner_wrap as $key2 => $most_inner_value) {
                            similar_text(strtolower(@$resultant_product_names_array[$key]['remaing_text'][$key1]), strtolower($most_inner_value), $percent);
                            if ($percent >= 70) {
                                $found_matched_result[$key][$key1][$key2] = $most_inner_value;
                            }
                        }
                    }
                    if (empty($found_matched_result[$key][$key1])) {
                        $found_matched_result[$key][$key1] = $this->final_brand_product_from_google(@$resultant_product_names_array[$key]['remaing_text'][$key1], $inner_wrap, $key1);
                    }
                }
            }
        }

        @$final_record_to_show = [];
        if (is_array(@$found_matched_result) || is_object(@$found_matched_result)){
            foreach (@$found_matched_result as $key => $outerwrap) {
                if (is_array(@$outerwrap) || is_object(@$outerwrap)){
                    foreach (@$outerwrap as $key1 => $inner_wrap) {
                        foreach ($inner_wrap as $key2 => $most_inner_value) {
                            if (!empty($most_inner_value)) {
                                if ($key2=="less_than_50") {
                                    @$less_than_50[$key][$key1] = 'less_than_50';
                                }
                                @$final_record_to_show[$key][$key1][$key2] = isset($final_array[$key][$key1][$key2]) ? $final_array[$key][$key1][$key2] : '';
                            }
                        }
                        if (empty($final_record_to_show[$key][$key1])) {
                            foreach ($inner_wrap as $key2 => $most_inner_value) {
                                if (!empty($most_inner_value)) {
                                    if ($key2=="less_than_50") {
                                        @$less_than_50[$key][$key1] = 'less_than_50';
                                    }
                                    @$final_record_to_show[$key][] = isset($final_array[$key][$key1]) ? $final_array[$key][$key1] : '';
                                    @$final_record_to_show[$key][$key1][$key2] = isset($final_array[$key][$key1][$key2]) ? $final_array[$key][$key1][$key2] : '';
                                }
                            }
                        }
                    }
                    if (empty($final_record_to_show[$key][$key1])) {
                        $final_record_to_show[$key][$key1] = array();
                    }
                }
            }
        }

        $result_key = [];
        foreach ($final_record_to_show as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $inner_wrap) {
                foreach ($inner_wrap as $key2 => $most_inner_value) {
                    if (!empty($most_inner_value)) {
                        if (stripos(strtolower(isset($most_inner_value['product_name']) ? $most_inner_value['product_name'] : ''), strtolower('edp')) !== false) {
                            $resultant_final_record_to_show[$key][$key1][$key2]['edp'][] = $most_inner_value;
                            $result_key[$key][$key1][$key2]= 'edp';
                        }
                        if (stripos(strtolower(isset($most_inner_value['product_name']) ? $most_inner_value['product_name'] : ''), strtolower('edt')) !== false) {
                            $resultant_final_record_to_show[$key][$key1][$key2]['edt'][] = $most_inner_value;
                            $result_key[$key][$key1][$key2] = 'edt';
                        }
                        if (stripos(strtolower(isset($most_inner_value['product_name']) ? $most_inner_value['product_name'] : ''), strtolower('edc')) !== false) {
                            $resultant_final_record_to_show[$key][$key1][$key2]['edc'][] = $most_inner_value;
                            $result_key[$key][$key1][$key2]= 'edc';
                        }
                        if (empty($resultant_final_record_to_show[$key][$key1])) {
                            $resultant_final_record_to_show[$key][$key1][$key2]['no_extra'][] = $most_inner_value;
                            $result_key[$key][$key1][$key2]= 'no_extra';
                        }
                    }
                    if (empty($result_key[$key][$key1][$key2])) {
                        $result_key[$key][$key1][$key2] = array();
                    }
                }
            }
        }

        $final_result_to_show_to_modal = [];
        foreach ($resultant_product_names_array as $key => $value) {
            foreach ($value['resultant_product_names'] as $key1 => $outerwrap) {
                foreach ($outerwrap as $key2 => $innerwrap) {
                    if (is_array(@$resultant_final_record_to_show[$key][$key1]) || is_object(@$resultant_final_record_to_show[$key][$key1])){
                        foreach (@$resultant_final_record_to_show[$key][$key1] as $key4 => $value) {
                            if (!empty(@$value[$key2])) {
                                $final_result_to_show_to_modal[$key][$key1][$key4] = @$value[$key2];
                            }
                            if ($key2 == "no_extra") {
                                @$key3 = @$result_key[$key][$key1][$key4];
                                if (!empty(@$value[$key3])) {
                                    $final_result_to_show_to_modal[$key][$key1][$key4] = @$value[$key3];
                                }
                            }
                        }
                    }
                }
                if(empty($final_result_to_show_to_modal[$key][$key1])) {
                    foreach ($outerwrap as $key2 => $innerwrap) {
                        if (is_array(@$resultant_final_record_to_show[$key][$key1]) || is_object(@$resultant_final_record_to_show[$key][$key1])){
                            foreach (@$resultant_final_record_to_show[$key][$key1] as $key4 => $value) {
                                @$key3 = @$result_key[$key][$key1][$key4];
                                if ($key2 == $key3) {
                                    $final_result_to_show_to_modal[$key][$key1][$key4] = @$value[$key2];
                                }else{
                                    if ($key3 == "no_extra") {
                                        $final_result_to_show_to_modal[$key][$key1][$key4] = @$value[$key3];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $result_to_return = array('final_result_to_show_to_modal' => $final_result_to_show_to_modal, 'less_than_50' => @$less_than_50, 'final_customers_company_name' => @$final_customers_company_name, 'company_based_record_products' => @$company_based_record_products);
        return $result_to_return;
    }

    public function check_product_gender($remaining_text){
        $resultant_array = [];
        foreach ($remaining_text as $key => $value) {
            $results = explode(" ", $value);
            foreach ($results as $key1 => $inner_wrap) {
                $inner_result_data = DB::table('product_gender')->select('gender')->where('name', '=', $inner_wrap)->orWhere('gender', '=', $inner_wrap)->get()->toArray();
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
                if (str_contains($value['product_name'], "'") || str_contains($value['product_name'], "’")) { 
                    $old_value = str_replace("'", ' ', $value['product_name']);
                    $temp_value = str_replace("’", ' ', $old_value);
                    $new_result[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', strtolower($temp_value));
                }else{
                    $new_result[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', strtolower($value['product_name']));
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
                preg_match_all('/\d+(?:\s*x\s*\d+)*\s*mls\b/i', $value, $matches);
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
            foreach ($decoded_uploaded_products as $key => $value) {
                if (stripos(strtolower($value['product_name']), "edp") !== false) {
                    @$resultant_product_names[$key]['edp'][] = $value['product_name'];
                }
                if (stripos(strtolower($value['product_name']), "edt") !== false) {
                    @$resultant_product_names[$key]['edt'][] = $value['product_name'];
                }
                if (stripos(strtolower($value['product_name']), "edc") !== false) {
                    @$resultant_product_names[$key]['edc'][] = $value['product_name'];
                }
                if (empty($resultant_product_names[$key])) {
                    @$resultant_product_names[$key]['no_extra'][] = $value['product_name'];
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
                    $inner_result_data = DB::table('brands')->select('brand_name')->where('short_key', '=', $inner_wrap)->first();
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
                        // $found_matched_result[$key][$key1][] = $inner_wrap;
                        similar_text(strtolower($outerwrap), strtolower($inner_wrap), $percent);
                        if ($percent==100) {
                            $higest_percentage[$key][] = $percent;
                            $found_matched_result[$key][$percent][$key1] = $inner_wrap;
                        }else{
                            $found_matched_result[$key][$key1][] = $inner_wrap;
                        }
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
                $data_to_show2 = $value[$temp_first_higest_percentage[1]];
                $data_to_show[$key] = $data_to_show1 + $data_to_show2;
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
            $temp_data_to_show = array();
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
                if (!empty($value['product_name'])) {
                    $results[$key] = getKeywordSuggestionsFromGoogle($value['product_name']);
                }
                if (empty($results[$key])) {
                    $results[$key][] = $value['product_name'];
                }
            }
        }

        $resultant_data = [];
        if (is_array($results) || is_object($results)){
            foreach ($results as $key => $outerwrap) {
                if (is_array($outerwrap) || is_object($outerwrap)){
                    foreach ($outerwrap as $key1 => $innerwrap) {
                        similar_text(strtolower($innerwrap), strtolower($data[$key]['product_name']), $percent);
                        if ($percent > 90) {
                            $old_innerwrap = str_replace("'", '', $innerwrap);
                            $new_innerwrap = str_replace("’", ' ', $old_innerwrap);
                            $resultant_data[$key][$percent] = $new_innerwrap;
                        }
                    }
                }
                if (!array_key_exists($key,(array)$resultant_data))
                    $resultant_data[$key][] = $data[$key]['product_name'];
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
            if (!empty($user_data)) {
                foreach ($brand_names as $key1 => $brands) {
                    if (stripos(strtolower($user_data), strtolower($brands->brand_name)) !== false) {
                        $resultant_brands[$key] = array('0' => $brands->brand_name, '1' => $brands->brand_name);
                    }
                }
                if (empty($resultant_brands[$key])) {
                    $results = explode(" ", $user_data);
                    foreach ($results as $key2 => $inner_wrap) {
                        $inner_result_data = DB::table('brands')
                        ->select('brand_name')
                        ->where('short_key', '=', $inner_wrap)
                        ->first();
                        if(!empty($inner_result_data))
                            $resultant_brands[$key] = array('0' => $inner_wrap, '1' => $inner_result_data->brand_name);
                    }
                }
            }

            if (empty($resultant_brands[$key])) {
                $resultant_brands[$key][] = '';
            }
        }

        $brand_names = [];
        foreach ($final_correct_spelling as $key => $value) {
            $brand_names[$key] = isset($resultant_brands[$key][1]) ? $resultant_brands[$key][1] : $resultant_brands[$key][0];
        }
        return $brand_names;
    }

    public function company_searching($uploaded_data){
        foreach ($uploaded_data as $key => $value) {
            $remove_elements = array('l.l.c','llc', 'perfumes', 'trading', 'general', 'world', 'company');
            $new_uploaded_company[$key]['company'] = str_replace($remove_elements,"", strtolower(@$key));
            foreach ($value as $key1 => $new_value) {
                $new_uploaded_company[$key]['company_country'] = @$new_value['country'];
                $new_uploaded_company[$key]['company_city']    = @$new_value['city'];
            }
        }
            
        foreach ($new_uploaded_company as $key => $value) {
            $company_name       = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value['company']);
            $company_country    = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value['company_country']);
            $company_city       = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value['company_city']);
            $found_company_name = Company::select('country', 'city', 'company_name');
            if (!empty($company_name))
                $found_company_name->where('company_name', 'like', '%' . $company_name . '%');
            if (!empty($company_country))
                if(strtolower($company_country) == "uae"){
                    $new_company_country = 'United Arab Emirates';
                    $found_company_name->where('country', 'like', '%' . $new_company_country . '%');
                }else{
                    $found_company_name->where('country', 'like', '%' . $company_country . '%');
                }
            if (!empty($company_city))
                $found_company_name->where('city', 'like', '%' . $company_city . '%');
                $final_found_company_name1 = $found_company_name->get()->toArray();

            if (empty($final_found_company_name[$key]) || count($final_found_company_name[$key]) < 0) {
                $found_company_name = Company::select('country', 'city', 'company_name');
                if (!empty($company_name))
                    $found_company_name->whereRaw("MATCH(company_name) AGAINST('".$company_name."')");
                if(strtolower($company_country) =="uae"){
                    $new_company_country = 'United Arab Emirates';
                    $found_company_name->where('country', 'like', '%' . $new_company_country . '%');
                }else{
                    $found_company_name->where('country', 'like', '%' . $company_country . '%');
                }
                if(!empty($company_city))
                    $found_company_name->where('city', 'like', '%' . $company_city . '%');
                $final_found_company_name2 = $found_company_name->get()->toArray();
            }

            if (!empty($final_found_company_name1)) {
                $final_found_company_name[$key] = $final_found_company_name1;
            }elseif ($final_found_company_name2) {
                $final_found_company_name[$key] = $final_found_company_name2;
            }else{
                $new_companies_name = explode(' ', $company_name);
                foreach ($new_companies_name as $key2 => $final_company_name) {
                    $found_company_name = Company::select('country', 'city', 'company_name');
                    if (!empty($company_name))
                        $found_company_name->where('company_name', 'like', '%' . $final_company_name . '%');
                    if(strtolower($company_country) =="uae"){
                        $new_company_country = 'United Arab Emirates';
                        $found_company_name->where('country', 'like', '%' . $new_company_country . '%');
                    }else{
                        $found_company_name->where('country', 'like', '%' . $company_country . '%');
                    }
                    if (!empty($company_city))
                        $found_company_name->where('city', 'like', '%' . $company_city . '%');
                    $end_final_found_company_name[$key][] = $found_company_name->get()->toArray();
                }
                foreach ($end_final_found_company_name as $key3 => $outerwrap) {
                    foreach ($outerwrap as $key4 => $innerwrap) {
                        foreach ($innerwrap as $key5 => $mostinnerwrap) {
                            $final_found_company_name[$key][] = $mostinnerwrap;
                        }
                    }
                }
            }
        }

        $found_matched_result = array();
        foreach ($final_found_company_name as $key => $company_name) {
            foreach ($company_name as $key1 => $innerwrap) {
                if(strtolower($key) == strtolower(@$innerwrap['company_name'])){
                    $found_matched_result[$key][$key1]['found_company'] = $key;
                    $found_matched_result[$key][$key1]['founded_company_name'] = $innerwrap['company_name'];
                    $found_matched_result[$key][$key1]['founded_country'] = $innerwrap['country'];
                    $found_matched_result[$key][$key1]['founded_city'] = $innerwrap['city'];
                }
            }
            if (empty($found_matched_result[$key])) {
                foreach ($company_name as $key1 => $innerwrap) {
                    $remove_elements = array('l.l.c','llc', 'perfumes', 'trading', 'general', 'world', 'company');
                    $new_uploaded_company = str_replace($remove_elements,"", strtolower(@$innerwrap['company_name']));
                    similar_text(strtolower($key), strtolower($new_uploaded_company), $percent);
                    if ($percent >= 80) {
                        $found_matched_result[$key][$key1]['found_company'] = $key;
                        $found_matched_result[$key][$key1]['founded_company_name'] = $innerwrap['company_name'];
                        $found_matched_result[$key][$key1]['founded_country'] = $innerwrap['country'];
                        $found_matched_result[$key][$key1]['founded_city'] = $innerwrap['city'];
                    }
                }
            }

            if (!array_key_exists($key, (array)$found_matched_result)){
                foreach ($company_name as $key1 => $innerwrap) {
                    $found_matched_result[$key][$key1]['found_company'] = $key;
                    $found_matched_result[$key][$key1]['founded_company_name'] = $innerwrap['company_name'];
                    $found_matched_result[$key][$key1]['founded_country'] = $innerwrap['country'];
                    $found_matched_result[$key][$key1]['founded_city'] = $innerwrap['city'];
                }
            }
        }
        
        foreach ($uploaded_data as $key => $value) {
            if (!empty($found_matched_result[$key])) {
                $final_data_to_return_after_company_get[$key]['company_data'] = $found_matched_result[$key];
                $final_data_to_return_after_company_get[$key]['company_data_products'] = $value;
            }else{
                foreach ($value as $key1 => $inner_value) {
                    $final_data_to_return_after_company_get[$key]['company_data'][0]['found_company'] = $key;
                    $final_data_to_return_after_company_get[$key]['company_data'][0]['founded_company_name'] = 'No Company Found';
                    $final_data_to_return_after_company_get[$key]['company_data'][0]['founded_country'] = $inner_value['country'];
                    $final_data_to_return_after_company_get[$key]['company_data'][0]['founded_city'] = $inner_value['city'];
                    $final_data_to_return_after_company_get[$key]['company_data_products'][$key1] = $inner_value;
                }
            }
        }

        return $final_data_to_return_after_company_get;
    }

    public function delete_customers_demands($id)
    {
       DB::table('customer_demand')->where('id', $id)->delete();
       return redirect()->route('customer-demand-page')->with('warning-message','Customers Demands Deleted Successfully');
    }

    public function customer_demand_page()
    {
        $user     = Auth::user();
        $added_by = $user->id;
        $title    = 'Customer Demand Page';
        $customer_demands = CustomerDemand::select('customer_demand.id', 'customer_demand.company_name', 'customer_demand.country', 'customer_demand.company_count', 'customer_demand_detail.product_id', 'customer_demand_detail.product_name','customer_demand_detail.customer_demand_id', 'products.brand_type', 'products.brand_name')
            ->leftjoin('customer_demand_detail', 'customer_demand_detail.customer_demand_id', '=', 'customer_demand.id')
            ->leftjoin('products', 'products.id', '=', 'customer_demand_detail.product_id')->where('customer_demand.added_by', '=', $added_by)->orderBy('id', 'ASC')->get()->toArray();

        $country_names=[];
        if (is_array(@$customer_demands) || is_object(@$customer_demands)){
            foreach (@$customer_demands as $key => $outerwrap) {
                if (!empty($outerwrap['country'])){
                    $country_names[$key] = $outerwrap['country'];
                }
            }
        }

        $company_names=[];
        if (is_array(@$customer_demands) || is_object(@$customer_demands)){
            foreach (@$customer_demands as $key => $outerwrap) { 
                if (!empty($outerwrap['company_name'])){
                    $company_names[$key] = $outerwrap['company_name'];
                }
            }
        }

        $filtered_brand_type_data=[];
        if (is_array(@$customer_demands) || is_object(@$customer_demands)){
            foreach (@$customer_demands as $key => $outerwrap) { 
                if (!empty($outerwrap['brand_type'])){
                    $brand_types = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $outerwrap['brand_type']);
                    $filtered_brand_type_data[$key] = $brand_types;
                }
            }
        }

        $filtered_brand_name_data=[];
        if (is_array(@$customer_demands) || is_object(@$customer_demands)){
            foreach (@$customer_demands as $key => $outerwrap) { 
                if (!empty($outerwrap['brand_name'])){
                    $filtered_brand_name_data[$key] = $outerwrap['brand_name'];
                }
            }
        }

        $filtered_product_name_data=[];
        if (is_array(@$customer_demands) || is_object(@$customer_demands)){
            foreach (@$customer_demands as $key => $outerwrap) { 
                if (!empty($outerwrap['product_name'])){
                    $filtered_product_name_data[$key] = $outerwrap['product_name'];
                }
            }
        }

        $data = [
            'title' => $title,
            'result' => $customer_demands,
            'filtered_country_names' =>array_intersect_key($country_names, array_unique(array_map('strtolower', $country_names))),
            'filtered_company_names' =>array_intersect_key($company_names, array_unique(array_map('strtolower', $company_names))),
            'filtered_product_names' =>array_intersect_key($filtered_product_name_data, array_unique(array_map('strtolower', $filtered_product_name_data))),
            'filtered_brand_types' =>array_intersect_key($filtered_brand_type_data, array_unique(array_map('strtolower', $filtered_brand_type_data))),
            'filtered_brand_names' =>array_intersect_key($filtered_brand_name_data, array_unique(array_map('strtolower', $filtered_brand_name_data))),
        ];
        return view('admin.customer-demand.index', $data);
    }

    public function view_all_company_customers_demands($id)
    {
        $user     = Auth::user();
        $added_by = $user->id;
        $old_customer_demand = DB::table('customer_demand')->where('id', '=', $id)->get()->first();
        $customer_demand = DB::table('customer_demand')->where('company_name', '=', $old_customer_demand->company_name)->where('added_by', '=', $added_by)->get()->toArray();
        foreach ($customer_demand as $key => $value) {
            $company_name[$key]   = $value->company_name;
            $qtys[$key]           = json_decode($value->qty);
            $prices[$key]         = json_decode($value->price);
            $all_data[$key]       = DB::table('customer_demand_detail')->where('customer_demand_id', '=', @$value->id)->get()->toArray();
        }

        foreach ($all_data as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $value) {
                @$product_names[] = $value->product_name;
            }
        }
        
        foreach ($qtys as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $value) {
                @$new_qtys[] = $value;
            }
        }

        foreach ($prices as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $value) {
                @$new_prices[] = $value;
            }
        }

        $count = 0;
        if (is_array(@$product_names) || is_object(@$product_names)){
            foreach (@$product_names as $key => $product_name) {
                $result_record[$count] = Product::select('id')->where('product_name', $product_name)->orderBy('id', 'ASC')->first()->toArray();
                $result_record[$count]['products'] = $product_name;
                $result_record[$count]['barcodes'] = Product::select('barcode')->where('product_name', $product_name)->orderBy('id', 'ASC')->first()->toArray();
                $result_record[$count]['brand_names'] = Product::select('brand_name')->where('product_name', $product_name)->orderBy('id', 'ASC')->first()->toArray();
                $count++;
            }
        }
        $data = [
            'title'          => @$old_customer_demand->company_name.' Demands Details',
            'company_name'   => @$old_customer_demand->company_name,
            'result_records' => @$result_record,
            'qtys'           => @$new_qtys,
            'prices'         => @$new_prices,
        ];
        return view('admin.customer-demand.all_company_customers_demands', $data);
    }

    public function view_company_customers_demands($id)
    {
        $user     = Auth::user();
        $added_by = $user->id;
        $old_customer_demand = DB::table('customer_demand')->where('id', '=', $id)->get()->first();
        $customer_demand = DB::table('customer_demand')->where('company_name', '=', $old_customer_demand->company_name)->where('added_by', '=', $added_by)->get()->toArray();
        $data = [
            'title'           => @$old_customer_demand->company_name.' Demands Details',
            'country'         => @$old_customer_demand->country,
            'city'            => @$old_customer_demand->city,
            'customer_demand' => @$customer_demand,
        ];
        return view('admin.customer-demand.company_customers_demands', $data);
    }

    public function view_customers_demands($id)
    {
        $customer_demand = DB::table('customer_demand')->where('id', '=', $id)->get()->toArray();
        foreach ($customer_demand as $key => $value) {
            $company_name   = $value->company_name;
            $city           = $value->city;
            $date           = $value->date;
            // $qtys           = json_decode($value->qty);
            // $prices         = json_decode($value->price);
        }

        $all_data = DB::table('customer_demand_detail')->where('customer_demand_id', '=', @$customer_demand[0]->id)->get()->toArray();
        foreach ($all_data as $key => $value) {
            @$product_names[] = $value->product_name;
        }

        $count = 0;
        if (is_array(@$product_names) || is_object(@$product_names)){
            foreach (@$product_names as $key => $product_name) {
                $result_record[$count] = Product::select('id')->where('product_name', $product_name)->orderBy('id', 'ASC')->first()->toArray();
                $result_record[$count]['products'] = $product_name;
                $result_record[$count]['barcodes'] = Product::select('barcode')->where('product_name', $product_name)->orderBy('id', 'ASC')->first()->toArray();
                $result_record[$count]['brand_names'] = Product::select('brand_name')->where('product_name', $product_name)->orderBy('id', 'ASC')->first()->toArray();
                $count++;
            }
        }
        $data = [
            'title'          => @$company_name.' Demands Details',
            'company_name'   => @$company_name,
            'city'           => @$city,
            'date'           => @$date,
            'result_records' => @$result_record,
            'qtys'           => @$qtys,
            'prices'         => @$prices,
        ];
        return view('admin.customer-demand.customers_demands_details', $data);
    }

    public function product_demand_page_ajax(Request $e)
    {
        $user     = Auth::user();
        $added_by = $user->id;
        $customer_demand = CustomerDemand::select('customer_demand.id AS customer_demand_id', 'customer_demand.company_name', 'customer_demand.country', 'customer_demand.company_count', 'customer_demand_detail.product_id AS customer_demand_product_id', 'customer_demand_detail.product_name','customer_demand_detail.customer_demand_id', 'products.brand_type', 'products.brand_name')
            ->leftjoin('customer_demand_detail', 'customer_demand_detail.customer_demand_id', '=', 'customer_demand.id')
            ->leftjoin('products', 'products.id', '=', 'customer_demand_detail.product_id');
        if (!empty($e->input('country')))
            $customer_demand->whereIn('customer_demand.country', $e->input('country', []));
        if (!empty($e->input('company_name')))
            $customer_demand->whereIn('customer_demand.company_name', $e->input('company_name', []));
        if (!empty($e->input('brand_type')))
            $customer_demand->whereIn('products.brand_type', $e->input('brand_type', []));
        if (!empty($e->input('brand_name')))
            $customer_demand->whereIn('products.brand_name', $e->input('brand_name', []));
        if (!empty($e->input('product_name')))
            $customer_demand->whereIn('products.product_name', $e->input('product_name', []));
        $customer_demand = $customer_demand->orderBy('customer_demand.id', 'ASC')->where('customer_demand.added_by', $added_by)->get()->toArray();
        
        foreach ($customer_demand as $key => $value) {
            $temp_customer_data[$value['country']]['country'][$key] = $value['country'];
            $temp_customer_data[$value['country']]['company_name'][$key] = $value['company_name'];
            $temp_customer_data[$value['country']]['brand_type'][$key] = $value['brand_type'];
            $temp_customer_data[$value['country']]['brand_name'][$key] = $value['brand_name'];
            $temp_customer_data[$value['country']]['product_data']['product_name'][$key] = $value['product_name'];
            $temp_customer_data[$value['country']]['product_data']['product_id'][$key] = $value['customer_demand_product_id'];
        }

        if (is_array(@$temp_customer_data) || is_object(@$temp_customer_data)){
            foreach (@$temp_customer_data as $key => $value) {
                $temp_country_data[] = $key;
                foreach ($value['company_name'] as $key1 => $value1) {
                    if (!empty($value1)) {
                        $temp_company_data[$key1] = $value1;
                    }
                }

                foreach ($value['brand_type'] as $key1 => $value1) {
                    if (!empty($value1)) {
                        $temp_brand_type_data[$key1] = $value1;
                    }
                }

                foreach ($value['brand_name'] as $key1 => $value1) {
                    if (!empty($value1)) {
                        $temp_brand_name_data[$key1] = $value1;
                    }
                }
                
                foreach ($value['product_data']['product_name'] as $key1 => $value1) {
                    if (!empty($value1)) {
                        $temp_product_name_data[$key1] = $value1;
                    }
                }

                foreach ($value['product_data']['product_id'] as $key1 => $value1) {
                    if (!empty($value1)) {
                        $temp_product_id_data[$key1] = $value1;
                    }
                }
            }
        }

        $final_country_data_count  = array_count_values($temp_country_data);
        arsort($final_country_data_count);
        $final_company_data_count  = array_count_values($temp_company_data);
        arsort($final_company_data_count);
        if (!empty($temp_brand_type_data)) {
            $final_brand_type_count = array_count_values(array_map('strtolower', $temp_brand_type_data));
        }
        if (!empty($temp_brand_type_data)) {
            arsort($final_brand_type_count);
        }

        if (!empty($temp_brand_name_data)) {
            $final_brand_name_count = array_count_values($temp_brand_name_data);
        }
        if (!empty($temp_brand_name_data)) {
            arsort($final_brand_name_count);
        }
        
        $final_product_names_count = array_count_values($temp_product_name_data);
        arsort($final_product_names_count);

        $final_product_ids_count = array_count_values($temp_product_id_data);
        arsort($final_product_ids_count);
        
        $product_id_count = 0;
        foreach ($final_product_ids_count as $key => $value) {
            $brand_names[$product_id_count] = DB::table('products')->select('brand_name')->where('id', '=', $key)->first();
            $product_id_count++;
        }

        $product_names_count = 0;
        foreach ($final_product_names_count as $key => $value) {
            $product_names[$product_names_count]['product_name'] = $key;
            $product_names[$product_names_count]['count'] = $value;
            $product_names_count++;
        }

        foreach ($product_names as $key => $value) {
            $final_product_names_array[$key]['count'] = $value['count'];
            $final_product_names_array[$key]['product_name'] = $value['product_name'];
            $final_product_names_array[$key]['brand_name'] = @$brand_names[$key]->brand_name;

        }

        foreach ($final_country_data_count as $key => $value) {
            $countries_list = DB::table('customer_demand')->select('country')->where('country', '=', $key)->where('customer_demand.added_by', $added_by)->get();
            $countries_Count = $countries_list->count();
            $resultant_country_data[] = $key.'('.$countries_Count.')';
        }
        
        foreach ($final_company_data_count as $key => $value) {
            $companies_Count = CustomerDemand::select('company_count')->where('company_name', '=', $key)->where('customer_demand.added_by', $added_by)->first();
            $final_companies_Count[$key] = $companies_Count->company_count;
            arsort($final_companies_Count);
        }

        foreach ($final_companies_Count as $key => $value) {
            $resultant_company_data[] = $key.'('.$value.')';
        }
        
        if (!empty($temp_brand_type_data)) {
            foreach ($final_brand_type_count as $key => $value) {
                $resultant_final_brand_type_data[] = ucfirst($key).'('.$value.')';
            }
        }else{
                $resultant_final_brand_type_data[] = '';
        }

        if (!empty($temp_brand_name_data)) {
            foreach ($final_brand_name_count as $key => $value) {
                $resultant_final_brand_name_data[] = $key.'('.$value.')';
            }
        }else{
                $resultant_final_brand_name_data[] = '';
        }

        foreach ($final_product_names_array as $key => $value) {
            if (!empty($value['brand_name'])) {
                $resultant_product_names_data[] = '('.$value['brand_name'].')'.$value['product_name'].'('.$value['count'].')';
            }else{
                $resultant_product_names_data[] = $value['product_name'].'('.$value['count'].')';
            }
        }

        foreach ($final_country_data_count as $key => $value) {
            $final_data_for_table['resultant_countries'] = $resultant_country_data;
            $final_data_for_table['resultant_companies'] = $resultant_company_data;
            $final_data_for_table['resultant_brand_types'] = $resultant_final_brand_type_data;
            $final_data_for_table['resultant_brand_names'] = array_slice($resultant_final_brand_name_data, 0, 20);
            $final_data_for_table['resultant_products'] = array_slice($resultant_product_names_data, 0, 100);
        }
        ob_start(); ?>
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Country</th>
                    <th>Company name</th>
                    <th>Brand type</th>
                    <th>Brand name</th>
                    <th>Product name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($final_data_for_table['resultant_products'] as $key => $product_name) { ?>
                    <tr>
                        <td><?php echo @$final_data_for_table['resultant_countries'][$key]; ?></td>
                        <td><?php echo @$final_data_for_table['resultant_companies'][$key]; ?></td>
                        <td><?php echo @$final_data_for_table['resultant_brand_types'][$key]; ?></td>
                        <td><?php echo @$final_data_for_table['resultant_brand_names'][$key]; ?></td>
                        <td><?php echo @$product_name; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php
        $res = ob_get_clean();
        echo json_encode(array('html' => $res));
        exit;
    }

    public function delete_customer_demand_page_data(Request $e){
        $user      = Auth::user();
        $added_by  = $user->id;
        DB::table('temp_customer_demand')->where('added_by', '=', $added_by)->orderBy('id', 'DESC')->delete();
        echo json_encode(array('result' => true));
        exit;
    }

    public function update_customer_demand_page_data(Request $e)
    {
        $user                    = Auth::user();
        $added_by                = $user->id;
        $old_company_name        = $e->input('old_company_name');
        $total_companies         = $e->input('company_name');
        $register_new_company    = $e->input('register_new_company');
        $user_products           = $e->input('user_products');
        $old_matchable_products  = $e->input('matchable_products');
        $register_new_product    = $e->input('register_new_product');
        $temp_old_companies = [];
        if (is_array(@$old_company_name) || is_object(@$old_company_name)){
            foreach (@$old_company_name as $key => $temp_companies) {
                $temp_old_companies[$key] = explode("||", @$temp_companies);
            }
        }

        $new_old_companies = [];
        foreach ($temp_old_companies as $key => $outerwrap) {
            $new_old_companies[$key]['user_file_company'] = $outerwrap[0];
            $new_old_companies[$key]['country'] = $outerwrap[1];
            $new_old_companies[$key]['city'] = $outerwrap[2];
        }

        $temp_total_companies = [];
        if (is_array(@$total_companies) || is_object(@$total_companies)){
            foreach (@$total_companies as $key => $temp_companies) {
                $temp_total_companies[$key] = explode("||", @$temp_companies);
            }
        }
        
        $new_total_companies = [];
        foreach ($temp_total_companies as $key => $outerwrap) {
            $new_total_companies[$key]['db_company'] = $outerwrap[0];
            $new_total_companies[$key]['country'] = $outerwrap[1];
            $new_total_companies[$key]['city'] = $outerwrap[2];
        }

        $temp_final_companies = [];
        if (is_array(@$register_new_company) || is_object(@$register_new_company)){
            foreach (@$register_new_company as $key => $temp_companies) {
                $temp_final_companies[$key] = explode("||", @$temp_companies);
            }
        }

        $new_final_companies = [];
        if (is_array(@$temp_final_companies) || is_object(@$temp_final_companies)){
            foreach (@$temp_final_companies as $key => $outerwrap) {
                $new_final_companies[$key]['user_file_company'] = $outerwrap[0];
                $new_final_companies[$key]['country'] = $outerwrap[1];
                $new_final_companies[$key]['city']    = $outerwrap[2];
            }
        }

        foreach ($temp_total_companies as $key => $value) {
            if (!empty($new_final_companies[$key]['user_file_company'])) {
                $final_companies[$key]['user_file_company'] = $new_final_companies[$key]['user_file_company'];
                $final_companies[$key]['country'] = $new_final_companies[$key]['country'];
                $final_companies[$key]['city']    = $new_final_companies[$key]['city'];
                $company_already_exist = DB::table('companies')->select('company_name')->where('company_name', '=', $new_final_companies[$key]['user_file_company'])->where('country', '=', $new_final_companies[$key]['country'])->where('city', '=', $new_final_companies[$key]['city'])->first();
                if (empty($company_already_exist)) {
                    DB::table('companies')->insert([
                        'company_name' => $new_final_companies[$key]['user_file_company'],
                        'country'      => $new_final_companies[$key]['country'],
                        'city'         => $new_final_companies[$key]['city'],
                        'active'       => 0,
                        'added_by'     => $added_by,
                    ]);
                }
            }else{
                if ($value[0] == "No Company Found") {
                    $final_companies[$key]['user_file_company'] = $new_old_companies[$key]['user_file_company'];
                    $final_companies[$key]['country'] = $new_old_companies[$key]['country'];
                    $final_companies[$key]['city']    = $new_old_companies[$key]['city'];
                    $company_already_exist = DB::table('companies')->select('company_name')->where('company_name', '=', $new_old_companies[$key]['user_file_company'])->where('country', '=', $new_old_companies[$key]['country'])->where('city', '=', $new_old_companies[$key]['city'])->first();
                    if (empty($company_already_exist)) {
                        DB::table('companies')->insert([
                            'company_name' => $new_old_companies[$key]['user_file_company'],
                            'country'      => $new_old_companies[$key]['country'],
                            'city'         => $new_old_companies[$key]['city'],
                            'active'       => 0,
                            'added_by'     => $added_by,
                        ]);
                    }
                }else{
                    $final_companies[$key]['user_file_company'] = $value[0];
                    $final_companies[$key]['country']    = $value[1];
                    $final_companies[$key]['city']       = $value[2];
                    $company_already_exist = DB::table('companies')->select('company_name')->where('company_name', '=', $value[0])->where('country', '=', $value[1])->where('city', '=', $value[2])->first();
                    if (empty($company_already_exist)) {
                        DB::table('companies')->insert([
                            'company_name' => $value[0],
                            'country'      => $value[1],
                            'city'         => $value[2],
                            'active'       => 0,
                            'added_by'     => $added_by,
                        ]);
                    }
                }
            }
        }

        if (is_array(@$old_matchable_products) || is_object(@$old_matchable_products)){
            foreach (@$old_matchable_products as $key => $matchable_product) {
                if (is_array(@$matchable_product) || is_object(@$matchable_product)){
                    foreach ($matchable_product as $key1 => $inner_matchable_product) {
                        $final_matchable_products[$key][$key1] = explode("||", @$inner_matchable_product);
                    }
                }
            }
        }

        if (is_array(@$final_matchable_products) || is_object(@$final_matchable_products)){
            foreach (@$final_matchable_products as $key => $inner_value) {
                foreach ($inner_value as $key1 => $value) {
                    if (!empty(@$register_new_product[$key][$key1])) {
                        $matchable_products[@$final_companies[$key]['user_file_company']][$key][$key1] = @$register_new_product[$key][$key1];
                        $product_already_exist = DB::table('products')->select('product_name')->where('product_name', 'like', '%' . @$register_new_product[$key][$key1] . '%')->first();
                        if (empty($product_already_exist)) {
                            $remove_extra_spaces_register_products = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', strtolower(@$register_new_product[$key][$key1]));
                            $new_remove_extra_spaces_register_products = str_replace("'", ' ', $remove_extra_spaces_register_products);
                            $final_register_products = str_replace("’", ' ', $new_remove_extra_spaces_register_products);

                            $added_products_details = DB::table('products')->insert([
                                'product_name' => @$final_register_products,
                                'active'       => 0,
                                'added_by'     => $added_by,
                            ]);
                            @$last_added_products_details = DB::table('products')->select('id')->where('product_name', 'like', '%' . @$user_products[$key][$key1] . '%')->first();
                            @$matchable_product_ids[@$final_companies[$key]['user_file_company']][$key][$key1] =  $last_added_products_details->id;
                        }
                    }else{
                        if (@$value[0] == "no") {
                            $product_already_exist = DB::table('products')->select('product_name')->where('product_name', 'like', '%' . @$user_products[$key][$key1] . '%')->first();
                            $remove_extra_spaces_user_products = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', strtolower(@$user_products[$key][$key1]));
                            $new_remove_extra_spaces_user_products = str_replace("'", ' ', $remove_extra_spaces_user_products);
                            $final_user_products = str_replace("’", ' ', $new_remove_extra_spaces_user_products);
                            if (empty($product_already_exist)) {
                                $added_products_details = DB::table('products')->insert([
                                    'product_name' => @$final_user_products,
                                    'active'       => 0,
                                    'added_by'     => $added_by,
                                ]);
                                @$last_added_products_details = DB::table('products')->select('id')->where('product_name', 'like', '%' . @$final_user_products . '%')->first();
                                @$matchable_product_ids[@$final_companies[$key]['user_file_company']][$key][$key1] =  $last_added_products_details->id;
                            }
                            @$matchable_products[@$final_companies[$key]['user_file_company']][$key][$key1] = @$final_user_products;
                        }else{
                            @$matchable_products[@$final_companies[$key]['user_file_company']][$key][$key1] = @$value[0];
                            @$matchable_product_ids[@$final_companies[$key]['user_file_company']][$key][$key1] = @$value[1];
                        }
                    }
                }
            }
        }
        
        foreach ($final_companies as $key => $temp_companies) {
            $total_companies_found_data[$temp_companies['user_file_company']]['company_details'] = @$temp_companies;
            $total_companies_found_data[$temp_companies['user_file_company']]['matchable_products'][$key] = @$matchable_products[$temp_companies['user_file_company']][$key];
            $total_companies_found_data[$temp_companies['user_file_company']]['matchable_products_id'][$key] = @$matchable_product_ids[$temp_companies['user_file_company']][$key];
        }

        $i=0;
        foreach (@$total_companies_found_data as $key => $data) {
            if(strtolower(@$data['company_details']['country']) == "uae"){
                $new_company_country = 'United Arab Emirates';
            }else{
                $new_company_country = @$data['company_details']['country'];
            }
            $last_customer_data_inserted = DB::table('customer_demand')->insert([
                'company_name' => @$key,
                'country'      => @$new_company_country,
                'city'         => @$data['company_details']['city'],
                'added_by'     => @$added_by,
            ]);
            @$last_customer_data_inserted_data = DB::table('customer_demand')->select('id')->where('added_by', '=', $added_by)->orderBy('id', 'DESC')->first();
            @$last_inserted_customer_demand_id[$i] = $last_customer_data_inserted_data->id;
            if (is_array(@$data['matchable_products']) || is_object(@$data['matchable_products'])){
                foreach (@$data['matchable_products'] as $key1 => $old_products) {
                foreach (@$old_products as $key2 => $products) {
                    @$result = DB::table('customer_demand_detail')->insert([
                        'customer_demand_id' => @$last_inserted_customer_demand_id[$i],
                        'product_id'         => $data['matchable_products_id'][$key1][$key2],
                        'product_name'       => @$products,
                    ]);
                    }
                }
            }
            $i++;
        }

        DB::table('temp_customer_demand')->where('added_by', '=', $added_by)->orderBy('id', 'DESC')->delete();
        echo json_encode(array('result' => $result));
        exit;
    }
}
