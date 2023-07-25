<?php

namespace App\Http\Controllers;

use App\Models\SupplierRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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

    public function import_supplier_record(Request $e)
    {
        $file = $e->file('file');
        if ($file) {
            $filename  = $file->getClientOriginalName();
            $file_name = time().rand(100,999).$filename;
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath  = $file->getRealPath();
            $fileSize  = $file->getSize();
            // $this->checkUploadedFileProperties($extension, $fileSize);
            $location  = 'file_uploading/supplier_record';
            $file->move($location, $file_name);
            $filepath  = public_path($location . "/" . $file_name);
            $new_file_name = basename($filename, ".xlsx");
            $data = Excel::import(new ImportSupplierRecord($filename), $filepath);
            unlink($filepath);
            $arr = array('message' => 'Supplier Record are Imported', 'title' => 'Successfully');
            echo json_encode($arr);
            exit;
        }
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
               <?php $i++;} ?>
        <?php 
        $res = ob_get_clean();
        echo json_encode(array('html' => $res));
        exit;
    }

}
