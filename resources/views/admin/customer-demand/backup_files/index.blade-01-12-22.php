@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <div class="page-header-breadcrumb">
                        <a href="{{url('/customer-demand-uploading')}}" class="btn btn-primary wrap_right">Bulk Upload</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row wrap_div">    
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <label>Country</label>
                <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="country" name="country[]" multiple>
                        <option>Select Country</option>
                    <?php foreach (@$country_data as $key => $value) { 
                        if (!empty($value)) { ?>
                        <option value="<?php echo $value->country; ?>"><?php echo $value->country; ?></option>
                    <?php } } ?>
                </select>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <label>State</label>
                <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="state" name="state[]" multiple>
                    <option>Select State</option>
                    <?php foreach (@$state_data as $key => $value) { 
                        if (!empty($value)) { ?>
                        <option value="<?php echo $value->state; ?>"><?php echo $value->state; ?></option>
                    <?php } } ?>
                </select>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <label>City</label>
                <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="city" name="city[]" multiple>
                    <option>Select City</option>
                    <?php foreach (@$city_data as $key => $value) { 
                        if (!empty($value)) { ?>
                        <option value="<?php echo $value->city; ?>"><?php echo $value->city; ?></option>
                    <?php } } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="table-responsive dt-responsive" id="myDiv">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Company Name</th>
                            <th>Company Type</th>
                            <th>Brand Type</th>
                            <th>Brand Name</th>
                            <th>Barcode (EAN code)</th>
                            <th>Product Name</th>
                            <th>Product Gender</th>
                            <th>Product Type</th>
                            <th>Date</th>
                            <th>Qty</th>
                            <th>Target Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        foreach ($customer_demand as $key => $value) { ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $value->country; ?></td>
                                <td><?php echo $value->state; ?></td>
                                <td><?php echo $value->city; ?></td>
                                <td><?php echo $value->company_name; ?></td>
                                <td><?php echo $value->company_type; ?></td>
                                <td><?php echo $value->brand_type; ?></td>
                                <td><?php echo $value->brand_name; ?></td>
                                <td><?php echo $value->barcode; ?></td>
                                <td><?php echo $value->product_name; ?></td>
                                <td><?php echo $value->product_gender; ?></td>
                                <td><?php echo $value->product_type; ?></td>
                                <td><?php echo $value->date; ?></td>
                                <td><?php echo $value->qty; ?></td>
                                <td><?php echo $value->price; ?></td>
                                <?php $i++; } ?>
                            </tr>
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="{{url('/import-query-data')}}">
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
</div> -->
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

            var state=[]; 
            $('select[name="state[]"] option:selected').each(function() {
             state.push($(this).val());
         });

            var city=[]; 
            $('select[name="city[]"] option:selected').each(function() {
             city.push($(this).val());
         });
            $.ajax({
                type:"POST",
                dataType:"json",
                url: "{{url('/customer-demand-ajax')}}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "country": country,
                    "state": state,
                    "city": city,
                },
                success: function(data) {
                    $('.overlay').hide();
                    $('#myDiv').html(data.html);
                    $('#myDiv table').DataTable();
                }
            });
        });
        
        // $(".wrap_modal").click(function () {
        //     $('#myModal2').modal();
        // });
    });

</script>
@endsection

