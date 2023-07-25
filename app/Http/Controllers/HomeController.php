<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Product;
use Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $total_users     = User::count();
        $total_companies = Company::count();
        $total_products  = Product::count();
        $title           = 'Admin Dashboard';
        $data = [
            'title'            => $title,
            'total_users'      => $total_users,
            'total_companies'  => $total_companies,
            'total_products'   => $total_products,
        ];
        return view('admin.dashboard', $data);
    }

    public function staff_setting()
    {
        $data = [
            'title' => 'Admin Setting',
            'data'  => Auth::user()
        ];
        return view('admin.staff_setting', $data);
    }

    public function save_staff_setting(Request $e)
    {
        $user = Auth::user();
        $User = User::where('email', '=', $e->email)->get();
        $count = $User->count();
        if ($e->retype_password != $e->password)
            return redirect()->route('admin-setting')->with('error-message','Retype Password is wrong.');

        $user        = User::find($user->id);
        $user->email = $e->email;
        if ($e->new_password !== '')
            $user->password = password_hash($e->new_password, 1);
        $user->save();
        return redirect()->route('admin-setting')->with('success-message','Settings Changed Successfully');
    }
}
