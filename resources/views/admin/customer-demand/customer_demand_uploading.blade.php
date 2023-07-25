@extends('admin.template')
@section('content')
<style type="text/css">
    .modal {
      padding: 0 !important;
    }
    .modal .modal-dialog {
      width: 100%;
      max-width: none;
      height: 100%;
      margin: 0;
    }
    .modal .modal-content {
      height: 100%;
      border: 0;
      border-radius: 0;
    }
    .modal .modal-body {
      overflow-y: auto;
    }
    input[type="checkbox"] {
        margin-top: 18px;
    }
</style>
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                {{$title}}
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <form action="{{url('customer-file-uploading')}}" method="post" enctype="multipart/form-data" id="excel_file_uploading" class="dropzone">
                @csrf
                <div>
                </div>
            </form> 
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="table-responsive dt-responsive" id="myDiv">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Company Name</th>
                            <th>Date & Time</th>
                            <th width="280px">Action</th>
                        </tr>
                    </thead>
                    <tbody id="wrap_tbody">
                    </tbody>  
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="show_records" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Company and Products</h5>
                <button type="button" class="close wrap_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="update-customer-demand-page-data" action="javascript:void(0)">
                    @csrf  
                    <div class="wrap_div">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary wrap_close" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary wrap_submit">Save changes</button>
            </div>
            </form> 
        </div>
    </div>
</div>
@endsection
@section('header')
@endsection
@section('footer')
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    let newarr = []
    var dropzone = new Dropzone('#excel_file_uploading', {
      thumbnailWidth: 200,
      timeout: 18000000,
      maxFilesize: 1,
      success: function(file, response, data) {
        var responseData = jQuery.parseJSON(response);
        if (responseData.status ==true) {
            $('#show_records').modal('show');
            $("#update-customer-demand-page-data .wrap_div").html(responseData.html);
        }else{
            toastr.warning(responseData.message, 'Error');
        }
    },
    error: function(file, message) {
        toastr.warning('File not uploaded', 'Error');
    }
});

$("#update-customer-demand-page-data").submit(function(){
    $(".wrap_close").prop("disabled",true);
    $(".wrap_submit").prop("disabled",true);
    var formData = $("#update-customer-demand-page-data").serialize();
    $.ajax({
        type:"POST",
        dataType:"json",
        url: "{{url('/update-customer-demand-page-data')}}",
        data: formData,
        success: function(data) {
            if (data.result == true) {
                toastr.success('Customer Demand Added Successfully');
                setTimeout(function(){
                    window.location.reload();
                }, 1500);
            }
        }
    });
});

$(".wrap_close").click(function(){
    var company_name = $("#update-customer-demand-page-data").find("input[name='old_company_name']").val();
    $.ajax({
        type:"POST",
        dataType:"json",
        url: "{{url('/delete-customer-demand-page-data')}}",
        data: {
            "_token": "{{ csrf_token() }}",
            "company_name":  {company_name},
        },
        success: function(data) {
            window.location.reload();
        }
    });
});

function this_customer_demands(id){
    var base_url = window.location.origin;
    url = base_url+'/view-company-customers-demands/'+id;
    window.open(url, '_blank');
}

function this_delete_customer_demands(id){
    var result = confirm("Want to delete?");
    if (result) {
        $.ajax({
            type:"POST",
            dataType:"json",
            url: "{{url('/delete-this-customers-demands')}}",
            data: {
                "_token": "{{ csrf_token() }}",
                "data":  {id},
            },
            success: function(data) {
                toastr.success(data.message);
                load_data();
            }
        });
    }
}

function this_save_customer_demands(id){
    var result = confirm("Want to save?");
    if (result) {
        $.ajax({
            type:"POST",
            dataType:"json",
            url: "{{url('/save-this-customers-demands')}}",
            data: {
                "_token": "{{ csrf_token() }}",
                "data":  {id},
            },
            success: function(data) {
                toastr.success(data.message);
                load_data();
            }
        });
    }
}

$(document).ready(function(){
    load_data();
});

  function load_data(){
    $.ajax({
       url:"{{ url('get-all-customers-demands') }}",
       type:"POST",
       dataType:"json",
       data: {
           "_token": "{{ csrf_token() }}",
       },
       success:function(response){
          $('#wrap_tbody').html(response.html);
          $('#example').dataTable();   
      }
  });
}
</script>
@endsection

