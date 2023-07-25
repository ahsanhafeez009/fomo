<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name', 'company_email', 'company_type', 'company_number', 'decoded', 'active', 'status', 'sale_rept', 'sale_rept', 'country', 'state', 'city', 'area', 'designation', 'website', 'contact_person', 'contact_email', 'contact_number', 'address', 'remarks'];
}
