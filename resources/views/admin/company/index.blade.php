@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                All User Demands List
            </div>
            <div class="col-lg-4">
                <!-- <div class="page-header-breadcrumb">
                    <button class="btn btn-primary wrap_modal wrap_right">Bulk Upload</button>
                </div> -->
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <form action="{{url('/import-companies')}}" method="post" enctype="multipart/form-data" id="import-companies" class="dropzone">
                @csrf
                <div>
                </div>
            </form> 
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="table-responsive dt-responsive" id="myDiv">
                <table id="user-demand-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>File Name</th>
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
@endsection
@section('header')
@endsection
@section('footer')
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    let newarr = []
    var dropzone = new Dropzone('#import-companies', {
        thumbnailWidth: 200,
        timeout: 18000000,
        success: function(file, response) {
            var responseData = jQuery.parseJSON(response);
            if (responseData.status==true) {
                toastr.success(responseData.message, responseData.title);
            }
            if (responseData.status==false) {
                toastr.warning(responseData.message, responseData.title);
            }
            load_data();
        }
    });
    
    $(document).ready(function(){
        load_data();
    });

    function delete_company_file(id) {
        var result = confirm("Want to delete?");
        if (result) {
            $.ajax({
                type:"POST",
                dataType:"json",
                url: "{{url('/delete-company-file')}}",
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
    };

    function accept_company_file(id) {
        var result = confirm("Want to Accept?");
        if (result) {
            $.ajax({
                type:"POST",
                dataType:"json",
                url: "{{url('/accept-company-file')}}",
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
    };

    function load_data(){
          $.ajax({
             url:"{{ url('get-all-companies') }}",
             type:"POST",
             dataType:"json",
             data: {
                     "_token": "{{ csrf_token() }}",
                  },
             success:function(response){
                $('#wrap_tbody').html(response.html);
                $('#user-demand-table').dataTable();   
            }
        });
    }
</script>
@endsection
