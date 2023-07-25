@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                All Supplier Record List
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <!-- <button class="btn btn-primary wrap_modal wrap_right">Bulk Upload</button> -->
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <form action="{{url('/import-supplier-record')}}" method="post" enctype="multipart/form-data" id="import_supplier_record" class="dropzone">
                @csrf
                <div>
                </div>
            </form> 
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="table-responsive dt-responsive" id="myDiv">
                <table id="supplier-record-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Supplier Name</th>
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
    var dropzone = new Dropzone('#import_supplier_record', {
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
        },
        error: function(file, response) {
            load_data();
            toastr.warning('File is Corrupted', 'Error');
        }
    });

    $(document).ready(function(){
        load_data();
    });

    function delete_this_supplier_record(id) {
        var result = confirm("Want to delete?");
        if (result) {
            $.ajax({
                type:"POST",
                dataType:"json",
                url: "{{url('/delete-supplier-records')}}",
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
         url:"{{ url('get-all-suppliers') }}",
         type:"POST",
         dataType:"json",
         data: {
             "_token": "{{ csrf_token() }}",
         },
         success:function(response){
            $('#wrap_tbody').html(response.html);
            $('#supplier-record-table').dataTable();   
        }
    });
  }
</script>
@endsection
