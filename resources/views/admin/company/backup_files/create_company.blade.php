@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                Create a Company
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <button class="btn btn-primary wrap_modal wrap_right">Bulk Upload</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="row">
                <div class="col-sm-12">
                    <form method="post" action="{{url('/register-company')}}">
                        @csrf
                        <div class="row form-group">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="company_name">Company Name</label>
                                <input name="company_name" type="text" value="" class="form-control" placeholder="Enter Company Name" required>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label>Company Type</label>
                                <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="company_type">
                                    <option>Select Company Type</option>
                                    <?php 
                                    foreach ($company_types as $key => $value) { ?>
                                        <option value="<?php echo $value->name; ?>"><?php echo $value->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="industry">Industry</label>
                                <input type="text" name="industry" value="" class="form-control" placeholder="Enter Industry" required>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label>Status</label>
                                <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="status">
                                    <option>Select Status</option>
                                    <?php 
                                    foreach ($company_status as $key => $value) { ?>
                                        <option value="<?php echo $value->status; ?>"><?php echo $value->status; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="fomo_incharge">FOMO Incharge</label>
                                <input type="text" name="fomo_incharge" value="" class="form-control" placeholder="FOMO Incharge">
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="payment_terms">Payment Terms</label>
                                <input type="text" name="payment_terms" value="" class="form-control" placeholder="Enter Payment Terms">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="trade_license_expiry">Trade License Expiry</label>
                                <input type="date" class="form-control" name="trade_license_expiry" placeholder="Enter Trade License Expiry" value="" id="trade_license_expiry" placeholder="Enter Trade License Expiry">
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="trn_number">TRN Number</label>
                                <input type="text" class="form-control" name="trn_number" placeholder="Enter TRN Number" value="">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="company_email">Company Email</label>
                                <input type="email" class="form-control" name="company_email" value="" placeholder="Enter Company Email" required>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="company_number">Company Number</label>
                                <input type="number" class="form-control" value="" name="company_number" placeholder="Enter Company Number">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="location">Location</label>
                                <input type="text" name="location" value="" class="form-control" placeholder="Enter Location">
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="country">Country</label>
                                <input type="text" name="country" value="" class="form-control" placeholder="Enter Country">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="state">State</label>
                                <input type="text" name="state" value="" class="form-control" placeholder="Enter State">
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <label for="city">City</label>
                                <input type="text" class="form-control" name="city" value="" placeholder="Enter City" id="city">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter Address"></textarea>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="remarks">Remarks</label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter Remarks"></textarea>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" style="float:right;">Register</button>
                    </form>
                </div>
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
                        <div class="page-header-breadcrumb">
                            <!-- <a href="{{url('/download-company-sample')}}" class="btn btn-primary btn-block">Sample File</a> -->
                        </div>
                    </div>
                </div>
                <form method="post" enctype="multipart/form-data" action="{{url('/import-companies')}}">
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
<script type="text/javascript">
    $(".wrap_modal").click(function () {
        $('#myModal2').modal();
    });
</script>
@endsection
