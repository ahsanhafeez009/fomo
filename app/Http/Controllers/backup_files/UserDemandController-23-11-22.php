<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\UserDemand;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
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

    public function import_user_demands(Request $e)
    {
        $file = $e->file('file');
        if ($file) {
            $filename  = $file->getClientOriginalName();
            $file_name = time().rand(100,999).$filename;
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath  = $file->getRealPath();
            $fileSize  = $file->getSize();
            // $this->checkUploadedFileProperties($extension, $fileSize);
            $location  = 'file_uploading/user_demands';
            $file->move($location, $file_name);
            $filepath  = public_path($location . "/" . $file_name);
            Excel::import(new ImportUserDemand($filename), $filepath);
            unlink($filepath);
            $arr = array('message' => 'User Demands are Imported Successfully', 'title' => 'Successfully');
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
               <?php $i++;} ?>
        <?php 
        $res = ob_get_clean();
        echo json_encode(array('html' => $res));
        exit;
    }

}
