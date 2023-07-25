@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                Edit {{$product->product_name}} Details
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                 <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{url('/admin-dash')}}"><i class="feather icon-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Edit Product</a> </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-block">
        <div class="row">
            <div class="col-sm-12">
                <form method="post" enctype="multipart/form-data" action="{{url('/update-product')}}">
                    @csrf
                    <input type="hidden" name="id" value="{{$product->id}}">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label>Brand Type</label>
                            <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="brand_type">
                                <option value="">Select Brand Type</option>
                                <?php 
                                foreach ($brand_type as $key => $value) { ?>
                                    <?php if (!empty($product->brand_type)) { ?>
                                        <option value="<?php echo $value->brand_type; ?>" <?php echo $product->brand_type == $value->brand_type ? 'selected' : '' ; ?>><?php echo $value->brand_type; ?></option>
                                    <?php }else{ ?>
                                        <?php if (!empty($value->brand_type)) { ?>
                                        <option value="<?php echo $value->brand_type; ?>"><?php echo $value->brand_type; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label>Brand Name</label>
                            <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="brand_name">
                                <option value="">Select Brand Name</option>
                                <?php 
                                foreach ($brand_name as $key => $value) { ?>
                                    <?php if (!empty($product->brand_name)) { ?>
                                        <option value="<?php echo $value->brand_name; ?>" <?php echo $product->brand_name == $value->brand_name ? 'selected' : '' ; ?>><?php echo $value->brand_name; ?></option>
                                    <?php }else{ ?>
                                        <?php if (!empty($value->brand_name)) { ?>
                                        <option value="<?php echo $value->brand_name; ?>"><?php echo $value->brand_name; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row form-group">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <label for="email">Product Name*</label>
                            <input type="text" name="product_name" required value="{{old('product_name',$product->product_name)}}" class="form-control" id="product_name" placeholder="Product Name">
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <label>Barcode*</label>
                            <input type="text" name="barcode" value="{{old('barcode',$product->barcode)}}" class="form-control" id="barcode" placeholder="Barcode">
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <label for="email">Bottle Size*</label>
                            <input type="text" name="bottle_size" value="{{old('bottle_size',$product->bottle_size)}}" class="form-control" id="bottle_size" placeholder="Bottle Size">
                        </div>
                    </div>
                    <div class="row form-group">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label>Product Gender</label>
                                <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="product_gender">
                                    <option value="">Select Product Gender</option>
                                    <?php 
                                    foreach ($product_gender as $key => $value) { ?>
                                        <?php if (!empty($product->product_gender)) { ?>
                                            <option value="<?php echo $value->gender; ?>" <?php echo $product->product_gender == $value->gender ? 'selected' : '' ; ?>><?php echo $value->gender; ?></option>
                                        <?php }else{ ?>
                                            <?php if (!empty($value->gender)) { ?>
                                            <option value="<?php echo $value->gender; ?>"><?php echo $value->gender; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label>Product Type</label>
                                <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="product_type">
                                    <option value="">Select Product Type</option>
                                    <?php 
                                    foreach ($product_type as $key => $value) { ?>
                                        <?php if (!empty($product->product_type)) { ?>
                                            <option value="<?php echo $value->product_type; ?>" <?php echo $product->product_type == $value->product_type ? 'selected' : '' ; ?>><?php echo $value->product_type; ?></option>
                                        <?php }else{ ?>
                                            <?php if (!empty($value->product_type)) { ?>
                                            <option value="<?php echo $value->product_type; ?>"><?php echo $value->product_type; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    <div class="row form-group">
                        <div class="col">
                            <button class="btn btn-primary wrap_right" type="submit">Update &rarr;</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('footer')
