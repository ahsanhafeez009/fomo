<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserDemand extends Model
{
    use HasFactory;
    protected $table = 'user_demands';

    public function get_supplier()
    {
        $user = Auth::user();
        return $this->hasMany('App\Models\SupplierRecord', 'barcode','barcode')->where('added_by', $user->id);;
    }
}
