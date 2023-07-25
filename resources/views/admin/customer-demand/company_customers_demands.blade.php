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
                    <p class="company_name"><?php echo @$title; ?></p>
                    <p><?php echo @$country; ?></p>
                    <p><?php echo @$city; ?></p>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <a href="{{url('/view-all-company-customers-demands/'.$customer_demand[0]->id)}}" class="btn btn-info btn-xs" style="float:right;">
                        All <?php echo @$title; ?> Record</a>
                </div>
                <br>
                <table id="customer_demands_table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Company Name</th>
                            <th>Date & Time</th>
                            <th width="280px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                            foreach ($customer_demand as $key => $data) { ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo @$data->company_name; ?></td>
                                <td><?php echo @$data->date; ?></td>
                                <td>
                                    <a href="{{url('/view-customers-demands/'.$data->id)}}" class="btn btn-primary btn-xs">
                                        <i class="fa fa-search"></i>
                                    </a>
                                </td>
                            <?php $i++; } ?>
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

