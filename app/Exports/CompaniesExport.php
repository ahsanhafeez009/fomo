<?php

namespace App\Exports;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CompaniesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       return Company::select('company_name','company_type','industry','status','fomo_incharge','payment_terms','trade_license_expiry','trn_number','company_email','company_number','location','country', 'state','city','address')->get();
    }

    public function headings(): array
    {
        return ["Company Name", "Company Type", "Industry", "Status", "FOMO Incharge", "Payment Terms", "Trade License Expiry", "TRN Number", "Company Email", "Company No.", "Location", "Country", "State", "City", "Address"];
    }
}
