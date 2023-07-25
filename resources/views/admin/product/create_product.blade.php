@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                Create a Product
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <a href="{{url('/upload-bulk-products')}}" class="btn btn-primary wrap_modal wrap_right">Bulk Upload</a>
                </div>
            </div>
        </div>
    </div>
<div class="card">
    <div class="card-block">
        <div class="row">
            <div class="col-sm-12">
                <form method="post" enctype="multipart/form-data" action="{{url('/save-product')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label>Brand Type</label>
                            <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="brand_type">
                                <option>Select Brand Type</option>
                                <?php 
                                foreach ($brand_type as $key => $value) { ?>
                                    <option value="<?php echo $value->brand_type; ?>"><?php echo $value->brand_type; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label>Brand Name</label>
                            <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="brand_name">
                                <option>Select Brand Name</option>
                                <?php 
                                foreach ($brand_name as $key => $value) { ?>
                                    <option value="<?php echo $value->brand_name; ?>"><?php echo $value->brand_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row form-group">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <label for="email">Product Name*</label>
                            <input type="text" name="product_name" required value="{{old('product_name')}}" class="form-control" id="product_name" placeholder="Product Name">
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <label>Barcode*</label>
                            <input type="text" name="barcode" required value="{{old('barcode')}}" class="form-control" id="barcode" placeholder="Barcode">
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <label for="email">Bottle Size*</label>
                            <input type="text" name="bottle_size" required value="{{old('bottle_size')}}" class="form-control" id="bottle_size" placeholder="Bottle Size">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label>Product Gender</label>
                            <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="product_gender">
                                <option>Select Product Gender</option>
                                <?php 
                                foreach ($product_gender as $key => $value) { ?>
                                    <option value="<?php echo $value->gender; ?>"><?php echo $value->gender; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label>Product Type</label>
                            <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="product_type">
                                <option>Select Product Type</option>
                                <?php 
                                foreach ($product_type as $key => $value) { ?>
                                    <option value="<?php echo $value->product_type; ?>"><?php echo $value->product_type; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col">
                            <button class="btn btn-primary wrap_right" type="submit" style="float: right;">Create &rarr;</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row align-items-end">
                    <div class="col-lg-8">
                    </div>
                    <div class="col-lg-4 margin_bottom_">
                        <!-- <div class="page-header-breadcrumb">
                            <a href="{{url('/download-product-sample')}}" class="btn btn-primary btn-block">Sample File</a>
                        </div> -->
                    </div>
                </div>
                <form method="post" enctype="multipart/form-data" action="{{url('/import-products')}}">
                    @csrf
                    <div class="row form-group">
                        <div class="col-sm-12">
                            <div class="custom-file mb-6 margin_bottom_">
                                <input type="file" class="custom-file-input" name="raw_file"
                                id="file-field">
                                <span class="custom-file-label" id="file-field-label" for="logo-field">Choose
                                File...</span>
                            </div>
                            <br>
                            <button class="btn btn-primary btn-block">
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
@section('footer')
<script type="text/javascript">
    // $(".wrap_modal").click(function () {
    //     $('#myModal2').modal();
    // });
</script>
@endsection
