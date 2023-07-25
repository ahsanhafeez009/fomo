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
                All Company List
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="row wrap_div">    
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <label>Company Type</label>
                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="company_type" name="company_type[]" multiple>
                        <?php foreach ($company_type as $key => $value) { 
                            if (!empty($value->name)): ?>
                            <option value="<?php echo $value->name; ?>"><?php echo $value->name; ?></option>
                        <?php endif; } ?>
                    </select>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <label>Status</label>
                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="status" name="status[]" multiple>
                        <?php foreach ($status as $key => $value) { 
                            if (!empty($value->status)): ?>
                            <option value="<?php echo $value->status; ?>"><?php echo $value->status; ?></option>
                        <?php endif; } ?>
                    </select>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <label>FOMO Incharge</label>
                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="fomo_incharge" name="fomo_incharge[]" multiple>
                        <?php foreach ($fomo_incharge as $key => $value) { 
                            if (!empty($value->fomo_incharge)): ?>
                            <option value="<?php echo $value->fomo_incharge; ?>"><?php echo $value->fomo_incharge; ?></option>
                        <?php endif; } ?>
                    </select>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <label>Payment Terms</label>
                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="payment_terms" name="payment_terms[]" multiple>
                        <?php foreach ($payment_terms as $key => $value) { 
                            if (!empty($value->payment_terms)): ?>
                            <option value="<?php echo $value->payment_terms; ?>"><?php echo $value->payment_terms; ?></option>
                        <?php endif; } ?>
                    </select>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <label>Country</label>
                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="country" name="country[]" multiple>
                        <?php foreach ($country as $key => $value) { 
                            if (!empty($value->country)): ?>
                            <option value="<?php echo $value->country; ?>"><?php echo $value->country; ?></option>
                        <?php endif; } ?>
                    </select>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <label>State</label>
                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="state" name="state[]" multiple>
                        <?php foreach ($state as $key => $value) { 
                            if (!empty($value->state)): ?>
                            <option value="<?php echo $value->state; ?>"><?php echo $value->state; ?></option>
                        <?php endif; } ?>
                    </select>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <label>City</label>
                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="city" name="city[]" multiple>
                        <?php foreach ($city as $key => $value) { 
                            if (!empty($value->city)): ?>
                            <option value="<?php echo $value->city; ?>"><?php echo $value->city; ?></option>
                        <?php endif; } ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-12">
                <form action="#" method="POST" name="importform" enctype="multipart/form-data">
                @csrf       
                <div class="form-group">
                    <a class="btn btn-primary wrap_right" href="{{ route('company-export-excel') }}">Export Record</a>
                </div> 
            </form>
        </div>
            <br>
            <div class="table-responsive dt-responsive" id="myDiv">
                 <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Company Name</th>
                            <th>Company Type</th>
                            <th>Industry</th>
                            <th>Status</th>
                            <th>FOMO Incharge</th>
                            <th>Payment Terms</th>
                            <th>Trade License Expiry</th>
                            <th>TRN Number</th>
                            <th>Company Email</th>
                            <th>Company No.</th>
                            <th>Location</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Address</th>
                            <th>Remarks</th>
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
        $(".wrap_div").on('keyup change', function (e){
            e.preventDefault();
            var company_type=[]; 
            $('select[name="company_type[]"] option:selected').each(function() {
                company_type.push($(this).val());
            });

            var status=[]; 
            $('select[name="status[]"] option:selected').each(function() {
                status.push($(this).val());
            });

            var fomo_incharge=[]; 
            $('select[name="fomo_incharge[]"] option:selected').each(function() {
                fomo_incharge.push($(this).val());
            });

            var payment_terms=[]; 
            $('select[name="payment_terms[]"] option:selected').each(function() {
                payment_terms.push($(this).val());
            });

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
            filters = true;
            fill_datatable(filters, company_type, status, fomo_incharge,payment_terms, country, state, city);
        });

        var filters = false;
        var company_type = '';
        var status = '';
        var fomo_incharge = '';
        var payment_terms = '';
        var country = '';
        var state = '';
        var city = '';
        fill_datatable(filters, company_type, status, fomo_incharge,payment_terms, country, state, city);
        
        function fill_datatable(filters = '', company_type = '', status = '', fomo_incharge = '', payment_terms = '', country = '', state = '', city = ''){
            var dataTable = $('#example').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax:{
                    url: "{{ route('company-list-ajax') }}",
                    data:{filters:filters, company_type:company_type, status:status, fomo_incharge:fomo_incharge, payment_terms:payment_terms, country:country, state:state, city:city}
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
                    data:'company_name',
                    name:'company_name'
                },
                {
                    data:'company_type',
                    name:'company_type'
                },
                {
                    data:'industry',
                    name:'industry'
                },
                {
                    data:'status',
                    name:'status'
                },
                {
                    data:'fomo_incharge',
                    name:'fomo_incharge'
                },
                {
                    data:'payment_terms',
                    name:'payment_terms'
                },
                {
                    data:'trade_license_expiry',
                    name:'trade_license_expiry'
                },
                {
                    data:'trn_number',
                    name:'trn_number'
                },
                {
                    data:'company_email',
                    name:'company_email'
                },
                {
                    data:'company_number',
                    name:'company_number'
                },
                {
                    data:'location',
                    name:'location'
                },
                {
                    data:'country',
                    name:'country'
                },
                {
                    data:'state',
                    name:'state'
                },
                {
                    data:'city',
                    name:'city'
                },
                {
                    data:'address',
                    name:'address'
                },{
                    data:'remarks',
                    name:'remarks'
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
                        return "<button class='btn btn-primary btn-xs' style=margin-right:5px; onclick=view_company(" + JSON.stringify(row.id) + ")><i class='fa fa-search'></i></button>" +
                        "<button class='btn btn-info btn-xs' style=margin-right:5px; onclick=edit_company(" + JSON.stringify(row.id) + ")><i class='fa fa-edit'></i></button>"+
                        "<button class='btn btn-danger btn-xs' style=margin-right:5px; onclick=delete_company(" + JSON.stringify(row.id) + ")><i class='fa fa-trash'></i></button>"
                    }
                }
                ]
            });
        }
    });

    function view_company(id){
        var base_url = window.location.origin;
        url = base_url+'/view-company/'+id;
        window.open(url, '_blank');
    }

    function edit_company(id){
        var base_url = window.location.origin;
        url = base_url+'/edit-company/'+id;
        window.open(url, '_blank');
    }

    function delete_company(id){
        var base_url = window.location.origin;
        location.href = base_url+'/delete-company/'+id;
    }
</script>
@endsection
