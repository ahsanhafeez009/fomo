@extends('admin.template')
@section('content')
<style type="text/css">
    td a:focus, td a:hover {
        text-decoration: none;
        color: #000000;
    }
    td  a {
        text-decoration: underline;
    }
</style>
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-12">
                All Product List
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="row wrap_div">    
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <label>Brand Type</label>
                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="brand_type" name="brand_type[]" multiple>
                        <?php foreach ($brand_type as $key => $value) { 
                            if (!empty($value->brand_type)): ?>
                            <option value="<?php echo $value->brand_type; ?>"><?php echo $value->brand_type; ?></option>
                        <?php endif; } ?>
                    </select>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <label>Brand Name</label>
                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="brand_name" name="brand_name[]" multiple>
                        <?php foreach ($brand_name as $key => $value) { 
                            if (!empty($value->brand_name)): ?>
                            <option value="<?php echo $value->brand_name; ?>"><?php echo $value->brand_name; ?></option>
                        <?php endif; } ?>
                    </select>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <label> Bottle Size</label>
                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="bottle_size" name="bottle_size[]" multiple>
                        <?php foreach ($bottle_size as $key => $value) {
                            if (!empty($value->bottle_size)): ?>
                            <option value="<?php echo $value->bottle_size; ?>"><?php echo $value->bottle_size; ?></option>
                        <?php endif; } ?>
                    </select>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <label>Product Gender</label>
                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="product_gender" name="product_gender[]" multiple>
                        <?php foreach ($product_gender as $key => $value) { 
                            if (!empty($value->gender)): ?>
                            <option value="<?php echo $value->gender; ?>"><?php echo $value->gender; ?></option>
                        <?php endif; } ?>
                    </select>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <label>Product Type</label>
                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="product_type" name="product_type[]" multiple>
                        <?php foreach ($product_type as $key => $value) { 
                            if (!empty($value->product_type)): ?>
                            <option value="<?php echo $value->product_type; ?>"><?php echo $value->product_type; ?></option>
                        <?php endif; } ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-12">
                <form action="#" method="POST" name="importform" enctype="multipart/form-data">
                @csrf       
                <div class="form-group">
                    <a class="btn btn-primary wrap_right" href="{{ route('products-export-excel') }}">Export Record</a>
                </div> 
            </form>
            <br>
            <div class="table-responsive dt-responsive" id="myDiv">
                 <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Brand Type</th>
                            <th>Brand Name</th>
                            <th>Product Name</th>
                            <th>Barcode</th>
                            <th>Bottle Size</th>
                            <th>Product Gender</th>
                            <th>Product Type</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
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
    $(document).ready(function(){
        // $("#example").dataTable().fnDestroy();
        $(".wrap_div").on('keyup change', function (e){
            var brand_types=[]; 
             $('select[name="brand_type[]"] option:selected').each(function() {
              brand_types.push($(this).val());
             });

            var brand_names=[]; 
             $('select[name="brand_name[]"] option:selected').each(function() {
              brand_names.push($(this).val());
             });

             var bottle_sizes=[]; 
             $('select[name="bottle_size[]"] option:selected').each(function() {
              bottle_sizes.push($(this).val());
             });

             var product_genders=[]; 
             $('select[name="product_gender[]"] option:selected').each(function() {
              product_genders.push($(this).val());
             });

             var product_types=[]; 
             $('select[name="product_type[]"] option:selected').each(function() {
              product_types.push($(this).val());
             });
             filters = true;
             // $("#example").dataTable().fnDestroy();
             fill_datatable(filters, brand_types, brand_names, bottle_sizes,product_genders, product_types);
        });

        var filters = false;
        var brand_types = '';
        var brand_names = '';
        var bottle_sizes = '';
        var product_genders = '';
        var product_types = '';
        fill_datatable(brand_types, brand_names, bottle_sizes,product_genders, product_types);
        
        function fill_datatable(filters = '', brand_types = '', brand_names = '', bottle_sizes = '', product_genders = '', product_types = ''){
            var dataTable = $('#example').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax:{
                    url: "{{ route('product-list-ajax') }}",
                    data:{filters:filters, brand_types:brand_types, brand_names:brand_names, bottle_sizes:bottle_sizes, product_genders:product_genders, product_types:product_types}
                },
                columns: [
                {
                    "data": "id",
                    "orderable": "false",
                    "render": function (data, type, full, meta) {
                         return meta.settings._iDisplayStart + meta.row + 1;
                    }
                },
                {
                    data:'brand_type',
                    name:'brand_type'
                },
                {
                    data:'brand_name',
                    name:'brand_name'
                },
                {
                    data:'product_name',
                    name:'product_name'
                },
                {
                    data:'barcode',
                    name:'barcode'
                },
                {
                    data:'bottle_size',
                    name:'bottle_size'
                },
                {
                    data:'product_gender',
                    name:'product_gender'
                },
                {
                    data:'product_type',
                    name:'product_type'
                },
                {
                    data:'created_at',
                    "render": function (data) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return (month.toString().length > 1 ? month : "0" + month) + "-" + date.getDate() + "-" + date.getFullYear();
                    }
                },
                {
                    "data": "id",
                    "render": function (data, type, row, meta) {
                        return "<button class='btn btn-primary btn-xs' style=margin-right:5px; onclick=view_product(" + JSON.stringify(row.id) + ")><i class='fa fa-search'></i></button>" +
                        "<button class='btn btn-info btn-xs' style=margin-right:5px; onclick=edit_product(" + JSON.stringify(row.id) + ")><i class='fa fa-edit'></i></button>"+
                        "<button class='btn btn-danger btn-xs' style=margin-right:5px; onclick=delete_product(" + JSON.stringify(row.id) + ")><i class='fa fa-trash'></i></button>"
                    }
                }
                ]
            });
        }
    });

    function view_product(id){
        var base_url = window.location.origin;
        url = base_url+'/view-product/'+id;
        window.open(url, '_blank');
    }

    function edit_product(id){
        var base_url = window.location.origin;
        url = base_url+'/edit-product/'+id;
        window.open(url, '_blank');
    }

    function delete_product(id){
        var base_url = window.location.origin;
        location.href = base_url+'/delete-product/'+id;
    }
</script>
@endsection

