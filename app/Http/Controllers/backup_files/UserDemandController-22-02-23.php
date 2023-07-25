<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\UserDemand;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Imports\ImportUserDemand;
use Illuminate\Support\Facades\Auth;

class UserDemandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function users_demands()
    {
        $title = 'User Demands';
        $data = [
            'title' => $title,
        ];
        return view('admin.user_demands.index', $data);
    }

    public function import_user_demands(Request $request){
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
            $this->import_user_demands_to_db($data, $filename);
        } catch (Exception $e) {
            $error_code = $e->errorInfo[1];
            return back()->withErrors('There was a problem uploading the data!');
        }
    }

    function import_user_demands_to_db($resultant_array, $filename){
        $user = Auth::user();
        $added_by = $user->id;
        @$user_already_exist = DB::table('users_demands_details')->select('*')->where('file_name', $filename)->where('added_by', $added_by)->get();
        $new_user_already_exist = $user_already_exist->count();
        if ($new_user_already_exist == 0) {
            DB::table('users_demands_details')->insert([
                'file_name' => $filename,
                'added_by' => $added_by
            ]);
            $temp_file_name_id = DB::table('users_demands_details')->select('*')->orderBy('id', 'DESC')->limit(1)->get();
            $file_name_id = $temp_file_name_id[0]->id;
        }else{
            $file_name_id = $user_already_exist[0]->id;
        }
        foreach ($resultant_array as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $innerwrap) {
                $new_innerwrap = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
                if (strtolower($new_innerwrap) == "barcode") {
                    $counter = [$key];
                    $headings_used_by[$key][$key1] = 'barcode';
                }

                if (strtolower($new_innerwrap) == "brand") {
                    $headings_used_by[$key][$key1] = 'brand_name';
                }

                if (strtolower($new_innerwrap) == "type") {
                    $headings_used_by[$key][$key1] = 'type';
                }

                if (strtolower($new_innerwrap) == "product name") {
                    $headings_used_by[$key][$key1] = 'product_name';
                }

                if (strtolower($new_innerwrap) == "qty") {
                    $headings_used_by[$key][$key1] = 'qty';
                }

                if (strtolower($new_innerwrap) == "price") {
                    $headings_used_by[$key][$key1] = 'avg_price_aed';
                }

                if (strtolower($new_innerwrap) == "avg pricing -usd" || strtolower($new_innerwrap) == "avg pricing-usd") {
                    $headings_used_by[$key][$key1] = 'avg_price_usd';
                }

                if (strtolower($new_innerwrap) == "avg pricing -euros" || strtolower($new_innerwrap) == "avg pricing-euros") {
                    $headings_used_by[$key][$key1] = 'avg_price_eur';
                }
            }
        }
        
        if (!empty($counter[0]) || isset($counter[0])) {
            for ($i=0; $i <= $counter[0] ; $i++) {
                unset($resultant_array[$i]);
                $new_resultant_array    = array_values($resultant_array);
                $row   = @$headings_used_by[$i];
                if(!empty($row)){
                    @$new_headings_used_by = @$row;
                }
            }


            foreach ($new_resultant_array as $key => $value) {
                foreach ($new_headings_used_by as $key1 => $innerwrap) {
                    if ($innerwrap == "barcode") {
                        @$barcodes[$key] = $value[$key1];
                    }

                    if ($innerwrap == "brand_name") {
                        @$brand_name[$key] = $value[$key1];
                    }

                    if ($innerwrap == "type") {
                        @$type[$key] = $value[$key1];
                    }

                    if ($innerwrap == "product_name") {
                        @$product_name[$key] = $value[$key1];
                    }

                    if ($innerwrap == "qty") {
                        @$qty[$key] = $value[$key1];
                    }

                    if ($innerwrap == "avg_price_aed") {
                        @$avg_price_aed[$key] = $value[$key1];
                    }

                    if ($innerwrap == "avg_price_usd") {
                        @$avg_price_usd[$key] = $value[$key1];
                    }

                    if ($innerwrap == "avg_price_eur") {
                        @$avg_price_eur[$key] = $value[$key1];
                    }
                }
            }

            if (!empty($barcodes) && !empty($product_name) && !empty($avg_price_aed)) {
                foreach ($barcodes as $key => $barcode) {
                    if (!empty($barcode)) {
                        @$barcode_already_exist = DB::table('user_demands')->select('*')->where('barcode', $barcode)->where('added_by', $added_by)->where('file_name_id', $file_name_id)->get();
                        $new_barcode_already_exist = $barcode_already_exist->count();
                        if ($new_barcode_already_exist == 0) {
                            DB::table('user_demands')->insert([
                                'file_name_id'    => $file_name_id,
                                'brand_name'      => !empty($brand_name[$key]) ? $brand_name[$key] : '',
                                'type'            => !empty($type[$key]) ? $type[$key] : '',
                                'barcode'         => !empty($barcode) ? $barcode : '',
                                'product_name'    => !empty($product_name[$key]) ? $product_name[$key] : '',
                                'user_demand_qty' => !empty($qty[$key]) ? $qty[$key] : '',
                                'avg_price_aed'   => !empty($avg_price_aed[$key]) ? $avg_price_aed[$key] : '',
                                'avg_price_usd'   => !empty($avg_price_usd[$key]) ? $avg_price_usd[$key] : '',
                                'avg_price_eur'   => !empty($avg_price_eur[$key]) ? $avg_price_eur[$key] : '',
                                'added_by'        => !empty($added_by) ? $added_by : '',
                            ]);
                        }else{
                            $array = [
                                'avg_price_aed' => !empty($avg_price_aed[$key]) ? $avg_price_aed[$key] : '',
                                'avg_price_usd' => !empty($avg_price_usd[$key]) ? $avg_price_usd[$key] : '',
                                'avg_price_eur' => !empty($avg_price_eur[$key]) ? $avg_price_eur[$key] : ''
                            ];
                            DB::table('user_demands')->where('barcode', $barcode)->where('added_by', $added_by)->where('file_name_id', $file_name_id)->update($array);
                        }
                    }
                }
                $arr = array('message' => 'User Demands are Imported Successfully', 'title' => 'Successfully', 'status' => true);
                echo json_encode($arr);
                exit;
            }else{
                DB::table('users_demands_details')->where('file_name', $filename)->where('added_by', $added_by)->delete();
                $arr = array('message' => 'Please Correct File Heading', 'title' => 'Error', 'status' => false);
                echo json_encode($arr);
                exit;
            }
        }else{
            DB::table('users_demands_details')->where('file_name', $filename)->where('added_by', $added_by)->delete();
            $arr = array('message' => 'Please Correct File Heading', 'title' => 'Error', 'status' => false);
            echo json_encode($arr);
            exit;
        }
   }

    public function delete_user_Demands(Request $e)
    {
        $id = $e->data['id'];
        DB::table('users_demands_details')->where('id', $id)->delete();
        DB::table('user_demands')->where('file_name_id', $id)->delete();
        $result = array('result' => true, 'message' => "User Demands Deleted Successfully");
        echo json_encode($result);
        exit;
    }

    public function get_all_user_demands(Request $e)
    {
        $user = Auth::user();
        $added_by = $user->id;
        $users_demands_details = DB::table('users_demands_details')->select('*')->where('added_by', $added_by)->get();
        ob_start(); ?>
                   <?php 
                   $i=1;
                   if (count($users_demands_details)>0) {
                   foreach ($users_demands_details as $key => $e) { ?>
                   <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $e->file_name; ?></td>
                        <td><?php echo $e->created_at; ?></td>
                        <td class="btn-group" style="display:flex;">
                            <a href="javascript:void(0)" data-file_name_id="<?php echo $e->id; ?>" class="btn btn-danger btn-xs" onclick="delete_user_demands(<?php echo $e->id; ?>)">
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

}
