<?php

namespace App\Exports;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierRecord;
use App\Models\UserDemand;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class SupplierPriceAnalysisExport implements FromCollection, WithHeadings
{
    protected $data;
     public function __construct($data)
    {
        $this->data = $data;
    }
     public function collection()
    {
        return collect($this->data);
    }

    public function headings() :array
    {
        return [
            'Barcode',
            'Product Name',
            'Avg Price Aed'
        ];
    }
}
