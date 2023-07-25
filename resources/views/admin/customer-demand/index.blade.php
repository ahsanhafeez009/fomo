@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row wrap_div">    
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <label>Country</label>
                <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="country" name="country[]" multiple>
                     <?php foreach ($filtered_country_names as $key => $country_names) { ?>
                        <option value="<?php echo $country_names; ?>"><?php echo $country_names; ?></option>
                    <?php }?>
                </select>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <label>Company</label>
                <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="company_name" name="company_name[]" multiple>
                     <?php foreach ($filtered_company_names as $key => $company_names) { ?>
                        <option value="<?php echo $company_names; ?>"><?php echo $company_names; ?></option>
                    <?php }?>
                </select>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <label>Brand Type</label>
                <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="brand_type" name="brand_type[]" multiple>
                    <?php foreach ($filtered_brand_types as $key => $brand_type) { ?>
                        <option value="<?php echo $brand_type; ?>"><?php echo $brand_type; ?></option>
                    <?php }?>
                </select>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <label>Brand Name</label>
                <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="brand_name" name="brand_name[]" multiple>
                    <?php foreach ($filtered_brand_names as $key => $brand_name) { ?>
                        <option value="<?php echo $brand_name; ?>"><?php echo $brand_name; ?></option>
                    <?php }?>
                </select>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <label>Product Name</label>
                <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="product_name" name="product_name[]" multiple>
                    <?php foreach ($filtered_product_names as $key => $product_name) { ?>
                        <option value="<?php echo $product_name; ?>"><?php echo $product_name; ?></option>
                    <?php }?>
                </select>
            </div>
        </div>
    <div class="col-lg-12">
        <button id="export" class="btn btn-primary wrap_right">Export Record</button>
    </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="table-responsive dt-responsive" id="myDiv">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Country</th>
                            <th>Company name</th>
                            <th>Brand type</th>
                            <th>Brand name</th>
                            <th>Product name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 0;
                        foreach ($result as $key => $outerwrap) { ?>
                                <tr>
                                    <td><?php echo $outerwrap['country']; ?></td>
                                    <td><?php echo $outerwrap['company_name']; ?></td>
                                    <td><?php echo $outerwrap['brand_type']; ?></td>
                                    <td><?php echo $outerwrap['brand_name']; ?></td>
                                    <td><?php echo $outerwrap['product_name']; ?></td>
                                </tr>
                        <?php } ?>
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
    $(document).ready(function () {
        var table = $('#example').DataTable();
        $(".wrap_div").on('keyup change', function (e){
            var country=[]; 
            $('select[name="country[]"] option:selected').each(function() {
                country.push($(this).val());
            });

            var company_name=[]; 
            $('select[name="company_name[]"] option:selected').each(function() {
                company_name.push($(this).val());
            });

            var brand_type=[]; 
            $('select[name="brand_type[]"] option:selected').each(function() {
                brand_type.push($(this).val());
            });

            var brand_name=[]; 
            $('select[name="brand_name[]"] option:selected').each(function() {
                brand_name.push($(this).val());
            });

            var product_name=[]; 
            $('select[name="product_name[]"] option:selected').each(function() {
                product_name.push($(this).val());
            });
            
            $.ajax({
                type:"POST",
                dataType:"json",
                url: "{{url('/customer-demand-ajax')}}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "country": country,
                    "company_name": company_name,
                    "brand_type": brand_type,
                    "brand_name": brand_name,
                    "product_name": product_name,
                },
                success: function(data) {
                    $('#myDiv').html(data.html);
                    $('#myDiv table').DataTable({
                        ordering: false,
                        pageLength: 100,
                    });
                }
            });
        });
    });
    var oTable;
    var oSettings;

    $(document).ready(function(){
        oTable=$('#example').DataTable( {
            "bSort": false,
            "pagingType": "full_numbers",
            "dom": 'T<"clear">lfrtip',
            "bDestroy": true
        });
        oSettings = oTable.settings();
    });    
    document.getElementById('export').addEventListener('click', function(e) {
        e.preventDefault();
        oSettings[0]._iDisplayLength = oSettings[0].fnRecordsTotal();
        oTable.draw();
        exportTableToExcel('example');
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
      link.download = "customer-demands-details.xls";
      link.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(tab_text);
      link.click();
  }
</script>
@endsection

