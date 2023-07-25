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
                        <?php  
                        $i=1;
                        foreach ($customer_demands as $key => $e) { ?>
                            <tr>
                             <td><?php echo $i; ?></td>
                             <td><?php echo $e->company_name; ?></td>
                             <td><?php echo $e->created_at; ?></td>
                             <td class="btn-group" style="display: flex;">
                                 <a href="javascript:void(0)" data-file_name_id="<?php echo $e->id; ?>" class="btn btn-primary btn-xs" onclick="this_customer_demands(<?php echo $e->id; ?>)">
                                     <i class="fa fa-search"></i>
                                 </a>
                                 <a href="javascript:void(0)" data-file_name_id="<?php echo $e->id; ?>" class="btn btn-danger btn-xs" onclick="this_delete_customer_demands(<?php echo $e->id; ?>)">
                                     <i class="fa fa-trash"></i>
                                 </a>
                             </td>
                         </tr>
                         <?php $i++; }?>
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
function this_customer_demands(id){
    var base_url = window.location.origin;
    url = base_url+'/view-customers-demands/'+id;
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
</script>
@endsection

