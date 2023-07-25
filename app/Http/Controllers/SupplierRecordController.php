<?php

namespace App\Http\Controllers;

use App\Models\SupplierRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Imports\ImportSupplierRecord;
use Illuminate\Support\Facades\Auth;

class SupplierRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function supplier_record()
    {
        $title = 'Supplier Record';
        $data = [
            'title'   => $title,
        ];
        return view('admin.supplier_record.index', $data);
    }

    public function import_supplier_record(Request $request){
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
                   'A' =>$sheet->getCell( 'A' . $row )->getFormattedValue(),
                   'B' => $sheet->getCell( 'B' . $row )->getFormattedValue(),
                   'C' => $sheet->getCell( 'C' . $row )->getFormattedValue(),
                   'D' => $sheet->getCell( 'D' . $row )->getFormattedValue(),
                   'E' => $sheet->getCell( 'E' . $row )->getFormattedValue(),
                   'F' =>$sheet->getCell( 'F' . $row )->getFormattedValue(),
                   'G' =>$sheet->getCell( 'G' . $row )->getFormattedValue(),
                   'H' =>$sheet->getCell( 'H' . $row )->getFormattedValue(),
                   'I' =>$sheet->getCell( 'I' . $row )->getFormattedValue(),
                   'J' =>$sheet->getCell( 'J' . $row )->getFormattedValue(),
                   'K' =>$sheet->getCell( 'K' . $row )->getFormattedValue(),
                   'L' =>$sheet->getCell( 'L' . $row )->getFormattedValue(),
                   'M' =>$sheet->getCell( 'M' . $row )->getFormattedValue(),
                   'N' =>$sheet->getCell( 'N' . $row )->getFormattedValue(),
                   'O' =>$sheet->getCell( 'O' . $row )->getFormattedValue(),
                   'P' =>$sheet->getCell( 'P' . $row )->getFormattedValue(),
                   'Q' =>$sheet->getCell( 'Q' . $row )->getFormattedValue(),
                   'R' =>$sheet->getCell( 'R' . $row )->getFormattedValue(),
                   'S' =>$sheet->getCell( 'S' . $row )->getFormattedValue(),
                   'T' =>$sheet->getCell( 'T' . $row )->getFormattedValue(),
                   'U' =>$sheet->getCell( 'U' . $row )->getFormattedValue(),
                   'V' =>$sheet->getCell( 'V' . $row )->getFormattedValue(),
                   'W' =>$sheet->getCell( 'W' . $row )->getFormattedValue(),
                   'X' =>$sheet->getCell( 'X' . $row )->getFormattedValue(),
                   'Y' =>$sheet->getCell( 'Y' . $row )->getFormattedValue(),
                   'Z' =>$sheet->getCell( 'Z' . $row )->getFormattedValue(),
               ];
               $startcount++;
            }
        $filename  = $request->file('file')->getClientOriginalName();
        $this->import_supplier_record_to_db($data, $filename);
    }

    function import_supplier_record_to_db($resultant_array, $filename){
        $supplier_name = basename($filename, ".xlsx");
        $user = Auth::user();
        $added_by = $user->id;
        @$supplier_already_exist = DB::table('supplier_details')->select('*')->where('supplier_name', $supplier_name)->where('added_by', $added_by)->get();
        @$new_supplier_already_exist = $supplier_already_exist->count();
        if (@$new_supplier_already_exist == 0) {
            DB::table('supplier_details')->insert([
                'supplier_name' => $supplier_name,
                'added_by'      => $added_by,
                'created_at'    => date("Y-m-d H:i:s")
            ]);
            $file_name_id = DB::table('supplier_details')->select('*')->orderBy('id', 'DESC')->limit(1)->get();
            @$supplier_details_id = $file_name_id[0]->id;
        }else{
            @$supplier_details_id = $supplier_already_exist[0]->id;
        }

        foreach (@$resultant_array as $key => $outerwrap) {
            foreach ($outerwrap as $key1 => $innerwrap) {
                $new_innerwrap = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
                if (strtolower($new_innerwrap) == "barcode") {
                    @$counter = [$key];
                    $headings_used_by[$key][$key1] = 'barcode';
                }

                if (strtolower($new_innerwrap) == "price") {
                    $headings_used_by[$key][$key1] = 'price';
                }

                if (strtolower($new_innerwrap) == "qty") {
                    $headings_used_by[$key][$key1] = 'qty';
                }
            }
        }
        
        if (!empty($counter[0]) || isset($counter[0])) {
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
                    // $matched = preg_replace('/\D/', '', $value[$key1]);
                        @$barcodes[$key] = $value[$key1];
                    }

                    if ($innerwrap == "qty") {
                        @$qty[$key] = $value[$key1];
                    }

                    if ($innerwrap == "price") {
                        @$price_aed[$key] = $value[$key1];
                    }
                }
            }

            if (!empty($barcodes) && !empty($price_aed)) {
                if (is_array(@$barcodes) || is_object(@$barcodes)){
                    foreach (@$barcodes as $key => $barcode) {
                        if (!empty($barcode)) {
                            $new_barcode= str_replace("'","",$barcode);
                            @$barcode_already_exist = DB::table('supplier_record')->select('*')->where('barcode', $new_barcode)->where('added_by', $added_by)->where('supplier_details_id', $supplier_details_id)->first();
                            if (@$barcode_already_exist){
                                $array = [
                                    'price_aed'    => !empty(@$price_aed[$key]) ? @$price_aed[$key] : '',
                                    'qty'          => !empty(@$qty[$key]) ? @$qty[$key] : '',
                                    'updated_at'   => date("Y-m-d H:i:s"),
                                ];
                                DB::table('supplier_record')->where('barcode', $new_barcode)->where('added_by', $added_by)->where('supplier_details_id', $supplier_details_id)->update($array);
                            }else{
                                DB::table('supplier_record')->insert([
                                    'supplier_details_id' => $supplier_details_id,
                                    'supplier_name'       => $supplier_name,
                                    'barcode'             => !empty(@$new_barcode) ? @$new_barcode : '',
                                    'price_aed'           => !empty(@$price_aed[$key]) ? @$price_aed[$key] : '',
                                    'qty'                 => !empty(@$qty[$key]) ? @$qty[$key] : '',
                                    'added_by'            => !empty($added_by) ? $added_by : '',
                                    'created_at'          => date("Y-m-d H:i:s"),
                                ]);
                            }
                        }
                    }
                }
                $arr = array('message' => 'Supplier Record are Imported', 'title' => 'Successfully', 'status' => true);
                echo json_encode($arr);
                exit;
            }else{
                $supplier_name = basename($filename, ".xlsx");
                DB::table('supplier_details')->where('supplier_name', $supplier_name)->where('added_by', $added_by)->delete();
                $arr = array('message' => 'Please Correct File Heading', 'title' => 'Error', 'status' => false);
                echo json_encode($arr);
                exit;
            }
        }else{
            $supplier_name = basename($filename, ".xlsx");
            DB::table('supplier_details')->where('supplier_name', $supplier_name)->where('added_by', $added_by)->delete();
            $arr = array('message' => 'Please Correct File Heading', 'title' => 'Error', 'status' => false);
            echo json_encode($arr);
            exit;
        }
    }
    
    public function delete_supplier_records(Request $e)
    {
        $id = $e->data['id'];
        $result = DB::table('supplier_details')->where('id', $id)->delete();
        DB::table('supplier_record')->where('supplier_details_id', $id)->delete();
        $result = array('result' => true, 'message' => "User Demands Deleted Successfully");
        echo json_encode($result);
        exit;
    }

    public function get_all_suppliers(Request $e){
        $user = Auth::user();
        $added_by = $user->id;
        $suppliers = DB::table('supplier_details')->select('*')->where('added_by', $added_by)->get();
        ob_start(); ?>
                    <?php  
                    $i=1;
                    if (count($suppliers)>0) {
                    foreach ($suppliers as $key => $e) { ?>
                   <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $e->supplier_name; ?></td>
                        <td><?php echo $e->created_at; ?></td>
                        <td class="btn-group" style="display: flex;">
                            <a href="javascript:void(0)" data-file_name_id="<?php echo $e->id; ?>" class="btn btn-danger btn-xs" onclick="delete_this_supplier_record(<?php echo $e->id; ?>)">
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
