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
</div>
<div class="modal fade" id="show_records" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Select Company and Products</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{url('/update-customer-demand-page-data')}}">
            @csrf
            <div class="row">
                <!-- <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <div id="old_company_target"></div>
                </div> -->
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div id="mainTarget"></div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 wrap_company_register" style="display:none;">
                    <input type="checkbox" name="register_new_company" value="1">
                </div>
            </div>
            <br>
            <div id="target"></div>
       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
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
      maxFilesize: 1,
      success: function(file, response) {
        var responseData = jQuery.parseJSON(response);
        $('#show_records').modal('show');
        newarr = responseData.old_product_result
        let mainTarget = document.querySelector('#mainTarget')
        mainTarget.innerHTML = responseData.old_company_result.company_name+'<select class="custom-select form-control" id="select_company" name="company_name"></select>'
        mainTarget = document.querySelector('#mainTarget select')
        let newHTML;
        for (var i = 0; i < responseData.company_result.length; i++) {

            if(responseData.company_result[0]=="No Company Found"){
                $('#select_company').html('<option value="">' + responseData.company_result[i].company_name+ '</option>');
                if (responseData.company_result[i].company_name) {
                   newHTML = '<option value="'+ responseData.company_result[i].company_name+'">' + responseData.company_result[i].company_name+ '</option>' + newHTML;
               }
               $(".wrap_company_register").show();
           }else{
            $(".wrap_company_register").hide();
            newHTML = '<option value="'+ responseData.company_result[i].company_name+'">' + responseData.company_result[i].company_name+ '</option>' + newHTML;
        }
    }
    mainTarget.innerHTML = newHTML
    let divTar = document.querySelector('#target');
    let divTarCheck = document.querySelector('#targetcheck');
    let data = responseData.product_result;
    let allSelects = '';
    let options =  '';   
    let count = 0;
    data.forEach( (sle)=> {
        if (sle.length != 0) {
            options = ''
            sle.forEach( (option) => {
                options = options + `<option value="${option.product_name}">${option.product_name}</option>`
            }
            ) 
            htmlSelect = `
            <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            ${newarr[count]} <select class="custom-select form-control" id="select_product" name="product_name[]"> ${options} </select>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <input type="checkbox" name="register_new_product[]" value="1">
            </div>
            </div>`
            count++;
            allSelects = allSelects +  htmlSelect
        }
    });
    divTar.innerHTML = allSelects
}
});
</script>
@endsection

