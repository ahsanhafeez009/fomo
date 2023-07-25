@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-4">
                All Supplier Record List
            </div>
            <div class="col-lg-8">
                <button id="export" class="btn btn-primary wrap_right">Export Record</button>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @if(count($alldata)>0)
            <div class="table-responsive dt-responsive" id="myDiv">
                <table id="price_analysis_table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th colspan="5" class="text-center">Fomo Requirements</th>
                            <?php foreach ($suppliers as $supplier) { ?>
                                <th colspan="2" class="text-center">{{ $supplier->supplier_name }}
                            <?php } ?>
                        </tr>
                        <tr>
                            <th>SN</th>
                            <th>Barcode</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Lowest Price</th>
                            <?php foreach ($suppliers as $supplier) { ?>
                                <th>Price</th>
                                <th>Quantity</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sn = 1;
                        foreach ($alldata as $e) { ?>
                        <?php if (!empty(@$e['product_name'])) { ?>
                        <tr>
                            <td><?= @$sn; ?></td>
                            <td><?= @$e['barcode'] ?></td>
                            <td><?= @$e['product_name'] ?></td>
                            <td><?= @$e['avg_price_aed'] ?></td>
                            <td><?= @$e['avg_price_aed'] ?></td>
                            @foreach($suppliers as $supplier)
                                @php $found = 0; @endphp
                                @foreach ($e->get_supplier as $inSupplier)
                                    @if ($supplier->supplier_name === $inSupplier->supplier_name)
                                        <td><?= number_format((float)$inSupplier->price_aed, 2, '.', ''); ?></td>
                                        <td><?= $inSupplier->qty  ?></td>
                                        @php $found = 1; @endphp
                                    @endif
                                @endforeach
                                @if ($found == 0)
                                    <td></td>
                                    <td></td>
                                @endif
                            @endforeach
                        </tr>
                        <?php } ?>
                        <?php $sn++;} ?>
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('header')
@endsection
@section('footer')
<script type="text/javascript">
    var oTable;
    var oSettings;

    $(document).ready(function(){
        oTable=$('#price_analysis_table').DataTable( {
            "bSort": false,
            "pagingType": "full_numbers",
            "dom": 'T<"clear">lfrtip',
        }); //store reference of your table in oTable
        oSettings = oTable.settings(); //store its settings in oSettings
    });    
    var table2excel = new Table2Excel();
    document.getElementById('export').addEventListener('click', function() {
        oSettings[0]._iDisplayLength = oSettings[0].fnRecordsTotal();
        oTable.draw();
        table2excel.export(document.querySelectorAll('table'));
        oSettings[0]._iDisplayLength=10;
        oTable.draw();
    });
</script>
@endsection
