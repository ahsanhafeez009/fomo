@extends('admin.template')
@section('content')
<style>
    table.dataTable>tbody>tr.selected>* {
        box-shadow: inset 0 0 0 9999px rgb(0 0 0 / 90%) !important;
        color: white !important;
    }
</style>
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row wrap_div">    
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <label>Country</label>
                <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="country" name="country[]" multiple>
                    <?php foreach (@$country_data as $key => $value) { 
                        if (!empty($value->country)) { ?>
                            <option value="<?php echo $value->country; ?>"><?php echo $value->country; ?></option>
                    <?php } } ?>
                </select>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <label>State</label>
                <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="state" name="state[]" multiple>
                    <?php foreach (@$state_data as $key => $value) { 
                        if (!empty($value->state)) { ?>
                        <option value="<?php echo $value->state; ?>"><?php echo $value->state; ?></option>
                    <?php } } ?>
                </select>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <label>City</label>
                <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="city" name="city[]" multiple>
                    <?php foreach (@$city_data as $key => $value) { 
                        if (!empty($value->city)) { ?>
                        <option value="<?php echo $value->city; ?>"><?php echo $value->city; ?></option>
                    <?php } } ?>
                </select>
            </div>
        </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <button id="export" class="btn btn-primary wrap_right" style="border-radius: 10px;">Action</button>
    </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="table-responsive dt-responsive" id="myDiv">
                 <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Country</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Company Name</th>
                            <th>Detail</th>
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
        $('#example tbody').on('click', 'tr', function () {
            $(this).toggleClass('selected');
            if ($(this).hasClass("selected")) {
                $(this).find(".editor-active").prop('checked', true);
            }
            if (!$(this).hasClass("selected")) {
                $(this).find(".editor-active").prop('checked', false);
            }
        });
        $(".wrap_div").on('keyup change', function (e){
            e.preventDefault();
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

            var company_name=[]; 
                $('select[name="company_name[]"] option:selected').each(function() {
                    company_name.push($(this).val());
                });
            filters = true;
            fill_datatable(filters, country, state, city, company_name);
        });

        var filters = false;
        var country = '';
        var state = '';
        var city = '';
        var company_name = '';
        fill_datatable(filters, company_name, country, state, city);
        
        function fill_datatable(filters = '', country = '', state = '', city = '', company_name = ''){
            var dataTable = $('#example').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax:{
                    url: "{{ route('customer-demand-page') }}",
                    data:{filters:filters, country:country, state:state, city:city, company_name:company_name}
                },
                columns: [
                {
                    data:   "active",
                    render: function ( data, type, row ) {
                        if ( type === 'display' ) {
                            return '<input type="checkbox" class="editor-active" name="checked_customer_demands" value=' + JSON.stringify(row.id) + '>';
                        }
                        return data;
                    },
                    className: "dt-body-center"
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
                    data:'company_name',
                    name:'company_name'
                },
                {
                    "data": "id",
                    "render": function (data, type, row, meta) {
                        return "<button class='btn btn-primary btn-xs' style=margin-right:5px; onclick=this_customer_demands(" + JSON.stringify(row.id) + ")><i class='fa fa-search'></i></button>"
                    }
                }
                ]
            });
            $.fn.dataTable.ext.errMode = 'none';
        }
    });

    function this_customer_demands(id){
        var base_url = window.location.origin;
        url = base_url+'/view-customers-demands/'+id;
        window.open(url, '_blank');
    }

    $('.wrap_right').click(function(){
        var result=[]; 
        $("input:checkbox[name=checked_customer_demands]:checked").each(function(){
            result.push($(this).val());
            console.log(result);
        });
    });
</script>
@endsection

