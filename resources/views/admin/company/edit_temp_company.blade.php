@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                Edit {{$title}} Detail
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin-dash')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Edit Company Detail</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="row">
                <div class="col-sm-8 offset-sm-2">
                    <div class="card shadow3 border-top3">
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data" id="form" action="{{url('/update-temp-company')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{$cdetail->id}}">
                            <div class="row form-group">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label for="company_name">Company Name</label>
                                    <input name="company_name" type="text" value="{{$cdetail->company_name}}" class="form-control" placeholder="Enter Company Name">
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label>Company Type</label>
                                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="company_type">
                                        <option value="">Select Company Type</option>
                                        <?php 
                                        foreach ($company_types as $key => $value) { ?>
                                            <option value="<?php echo $value->name; ?>" <?php echo $cdetail->company_type == $value->name  ? 'selected' : '' ; ?>><?php echo $value->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label for="industry">Industry</label>
                                    <input name="industry" type="text" value="{{$cdetail->industry}}" class="form-control" placeholder="Enter Industry">
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label>Status</label>
                                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="status">
                                    <option value="">Select Status</option>
                                    <?php 
                                    foreach ($company_statuses as $key => $value) { ?>
                                        <option value="<?php echo $value->status; ?>"  <?php echo $cdetail->status ==$value->status ? 'selected' : '' ; ?>><?php echo $value->status; ?></option>
                                    <?php } ?>
                                </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label for="fomo_incharge">FOMO Incharge</label>
                                    <input type="text" name="fomo_incharge" value="{{$cdetail->fomo_incharge}}" class="form-control" placeholder="FOMO Incharge">
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label for="payment_terms">Payment Terms</label>
                                    <input type="text" name="payment_terms" value="{{$cdetail->payment_terms}}" class="form-control" placeholder="Enter Payment Terms">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label for="trade_license_expiry">Trade License Expiry</label>
                                    <input type="date" class="form-control" name="trade_license_expiry" placeholder="Enter Trade License Expiry" id="trade_license_expiry" value="{{$cdetail->trade_license_expiry}}" placeholder="Enter Trade License Expiry">
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label for="trn_number">TRN Number</label>
                                    <input type="text" class="form-control" name="trn_number" value="{{$cdetail->trn_number}}" placeholder="Enter TRN Number">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label for="company_email">Company Email</label>
                                    <input type="email" class="form-control" name="company_email" value="{{$cdetail->company_email}}" placeholder="Enter Company Email">
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label for="company_number">Company Number</label>
                                    <input type="number" class="form-control" value="{{$cdetail->company_number}}" name="company_number" placeholder="Enter Company Number">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label for="location">Location</label>
                                    <input type="text" name="location" value="{{$cdetail->location}}" class="form-control" placeholder="Enter Location">
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label for="country">Country</label>
                                    <input type="text" name="country" value="{{$cdetail->country}}" class="form-control" placeholder="Enter Country">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label for="state">State</label>
                                    <input type="text" name="state" value="{{$cdetail->state}}" class="form-control" placeholder="Enter State">
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" name="city" value="{{$cdetail->city}}" placeholder="Enter City" id="city">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter Address">{{$cdetail->address}}</textarea>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label for="remarks">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter Remarks">{{$cdetail->remarks}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" id="btn" class="btn btn-primary btn-block load">
                                    Proceed &rarr;
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('footer')
@endsection
