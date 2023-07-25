@extends('admin.template')
@section('content')
<style type="text/css">
    .div_center{
        text-align: center;
    }
    .div_center p{
        font-weight: 600 !important;
        font-size: 15px !important;
    }
    a:focus, a:hover {
        text-decoration: none !important;
        color: white !important;
    }
    td a {
        text-decoration: underline;
    }
</style>
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
            </div>
            <div class="col-lg-4">
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="table-responsive dt-responsive" id="myDiv">
                <div class="div_center">
                    <p class="company_name"><?php echo @$company_name; ?></p>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <button id="export" class="btn btn-primary wrap_right">Export Record</button>
                </div>
                <br>
                <table id="customer_demands_table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Barcode</th>
                            <th>Brand Name</th>
                            <th>Product Name</th>
                            <!-- <th>Quantity</th> -->
                            <!-- <th>price</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        if (is_array(@$result_records) || is_object(@$result_records)){ 
                        foreach ($result_records as $key => $data) { ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo @$data['barcodes']['barcode']; ?></td>
                                <td><?php echo @$data['brand_names']['brand_name']; ?></td>
                                <td>
                                    <a href="{{url('/view-product/'.$data['id'])}}">
                                        <?php echo @$data['products']; ?>
                                    </a>
                                </td>
                                <!-- <td><?php echo @$qtys[$key]; ?></td> -->
                                <!-- <td><?php echo @$prices[$key]; ?></td> -->
                                <?php $i++; } }?>
                            </tr>
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
        oTable=$('#customer_demands_table').DataTable( {
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
        exportTableToExcel('customer_demands_table');
            // table2excel.export(document.querySelectorAll('table'));
        oSettings[0]._iDisplayLength=10;
        oTable.draw();
    });

    function exportTableToExcel(tableId) {
      var tab_text = "<table border='2px'><tr>";
      var textRange;
      var j = 0;
      tab = document.getElementById(tableId);
      for (j = 0 ; j < tab.rows.length ; j++) {
          tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
      }
      tab_text = tab_text + "</table>";
      var link = document.createElement('a');
      var company_name = $(".company_name").html();
      link.download = company_name+" details.xls";
      link.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(tab_text);
      link.click();
  }
</script>
@endsection

