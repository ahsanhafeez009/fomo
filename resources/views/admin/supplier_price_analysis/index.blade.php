@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-4">
                All Supplier Price Analysis List
            </div>
            <div class="col-lg-8">
                <button id="export" class="btn btn-primary wrap_right">Export Record</button>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="table-responsive dt-responsive" id="myDiv">
                <table id="price_analysis_table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th colspan="6" class="text-center">Fomo Requirements</th>
                            <?php foreach ($suppliers as $supplier) { ?>
                                <th colspan="2" class="text-center">{{ $supplier->supplier_name }}
                            <?php } ?>
                        </tr>
                        <tr>
                            <th>SN</th>
                            <th>Barcode</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Qty</th>
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
                            <td>
                                <?php if (!empty(@$e['user_demand_qty'])) {
                                    echo @$e['user_demand_qty'];
                                }
                                ?>
                            </td>
                            <td>
                                <?php if (!empty(@$e['lowest_price'])) {
                                    echo number_format((float)@$e['lowest_price'], 2, '.', '');
                                }
                                ?>
                            </td>
                            @foreach($suppliers as $supplier)
                                @php $found = 0; @endphp
                                @foreach ($e->get_supplier as $inSupplier)
                                    @if ($supplier->supplier_name === $inSupplier->supplier_name)
                                        <?php if (@$e['lowest_price'] == @$inSupplier->price_aed) { 
                                            if (!empty($inSupplier->price_aed)) { ?>
                                           <td style="background: #386c00;color: white;">
                                                <?php 
                                                    echo number_format((float)$inSupplier->price_aed, 2, '.', ''); ?>
                                            </td>
                                            <?php }else{ ?> 
                                                <td></td>
                                            <?php }?>
                                        <?php }else{ 
                                            if (!empty($inSupplier->price_aed)) { ?>
                                            <td>
                                            <?php 
                                            $price_to_show = number_format((float)$inSupplier->price_aed, 2, '.', ''); 
                                                if ($price_to_show !=="0.00") { 
                                                    echo $price_to_show;
                                                }
                                            ?>
                                            </td>
                                            <?php }else{ ?> 
                                                <td></td>
                                            <?php }?>
                                        <?php } ?>
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
    // var table2excel = new Table2Excel();
    document.getElementById('export').addEventListener('click', function(e) {
        e.preventDefault();
        oSettings[0]._iDisplayLength = oSettings[0].fnRecordsTotal();
        oTable.draw();
        exportTableToExcel('price_analysis_table');
        // table2excel.export(document.querySelectorAll('table'));
        oSettings[0]._iDisplayLength=10;
        oTable.draw();
    });

    function exportTableToExcel(tableId) {
          var tab_text = "<table border='2px'><tr>";
          var textRange;
          var j = 0;
          tab = document.getElementById(tableId);
          if (tab.rows.length != 3) {
              for (j = 0 ; j < tab.rows.length ; j++) {
                  tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
              }
              tab_text = tab_text + "</table>";
          // sa = window.open('data:application/vnd.ms-excel,' + escape(tab_text));
          // return (sa);
              var link = document.createElement('a');
              link.download = "price_analysis.xls";
              link.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(tab_text);
              link.click();
          }
      }
</script>
@endsection
