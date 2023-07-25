@extends('admin.template')
@section('content')
<style type="text/css">
    div.dataTables_wrapper div.dataTables_filter {
        display: contents !important;
    }
    div.dt-buttons {
        float: right !important;
    }
    a.dt-button:hover:not(.disabled), button.dt-button:hover:not(.disabled), div.dt-button:hover:not(.disabled) {
        background-image: none !important;
        background-color: #263544 !important;
        border-color: #f2f7fb !important;
    }
    a.dt-button{
        background-image: none !important;
        background-color: #263544 !important;
        border-color: #f2f7fb !important;
    }
    a.dt-button, a.dt-button.active:not(.disabled), a.dt-button:active:not(.disabled), a.dt-button:focus:not(.disabled), button.dt-button, button.dt-button.active:not(.disabled), button.dt-button:active:not(.disabled), button.dt-button:focus:not(.disabled), div.dt-button, div.dt-button.active:not(.disabled), div.dt-button:active:not(.disabled), div.dt-button:focus:not(.disabled) {
        background-color: #263544 !important;
        border-color: #263544 !important;
    }
</style>
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-4">
               All Supplier Record List
            </div>
            <div class="col-lg-8">
                <!-- <button id="export" class="btn btn-primary wrap_right" style="margin: 0px 0px 0px 10px; border-radius: 10px;">Export Record</button> -->
                <button class="btn btn-primary wrap_user_demand_modal wrap_right" style="margin: 0px 0px 0px 10px; border-radius: 10px;">User Demands</button>
                <button class="btn btn-primary wrap_supplier_record_modal wrap_right" style="margin: 0px 0px 0px 10px;border-radius: 10px;">Supplier Record</button>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @if(count($alldata)>0)
            <div class="table-responsive dt-responsive" id="myDiv">
                <table id="price_analysis_record" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center">Fomo Requirements</th>
                            <?php foreach ($suppliers as $supplier) { ?>
                                <th colspan="2" class="text-center">{{ $supplier->supplier_name }}
                            <?php } ?>
                        </tr>
                        <tr>
                            <th>SN</th>
                            <th>Barcode</th>
                            <th>Product Name</th>
                            <th>Avg Pricing -AED</th>
                            <?php foreach ($suppliers as $supplier) { ?>
                                <th>Price AED</th>
                                <th>Quantity</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sn = 1;
                        foreach ($alldata as $e) { ?>
                        <tr>
                            <td><?= @$sn; ?></td>
                            <td><?= @$e['barcode']  ?></td>
                            <td><?= @$e['product_name']  ?></td>
                            <td><?= @$e['avg_price_aed']  ?></td>
                            @foreach($suppliers as $supplier)
                                @php $found = 0; @endphp
                                @foreach ($e->get_supplier as $inSupplier)
                                        <td><?= $inSupplier->price_aed  ?></td>
                                        <td><?= $inSupplier->qty  ?></td>
                                        @php $found = 1; @endphp
                                @endforeach
                                @if ($found == 0)
                                    <td></td>
                                    <td></td>
                                @endif
                            @endforeach
                        </tr>
                        <?php $sn++;} ?>
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="user_demand" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="{{url('/import-user-demands')}}">
                    @csrf
                    <div class="row form-group">
                        <div class="col-sm-12">
                            <div class="custom-file mb-6 margin_bottom_">
                                <input type="file" class="custom-file-input" name="raw_file"
                                id="file-field">
                                <span class="custom-file-label" id="file-field-label" for="logo-field">Choose
                                File...</span>
                            </div>
                            <button class="btn btn-primary btn-block" type="submit">
                                Import
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="supplier_record" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="{{url('/import-supplier-record')}}">
                    @csrf
                    <div class="row form-group">
                        <div class="col-sm-12">
                            <div class="custom-file mb-6 margin_bottom_">
                                <input type="file" class="custom-file-input" name="raw_file"
                                id="file-field">
                                <span class="custom-file-label" id="file-field-label" for="logo-field">Choose
                                File...</span>
                            </div>
                            <button class="btn btn-primary btn-block" type="submit">
                                Import
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('header')
@endsection
@section('footer')
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#price_analysis_record').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                'excel',
            ],
        } );
    } );

    $(".wrap_user_demand_modal").click(function () {
        $('#user_demand').modal();
    });

    $(".wrap_supplier_record_modal").click(function () {
        $('#supplier_record').modal();
    });
    
    // var table2excel = new Table2Excel();

    // document.getElementById('export').addEventListener('click', function() {
    //     table2excel.export(document.querySelectorAll('table'));
    // });
</script>
@endsection
