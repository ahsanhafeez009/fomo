@extends('admin.template')
@section('content')
<style type="text/css">
  #overlay {
     position: fixed;
     display: block;
     top: 0;    /* making preloader cover all screen */
     right: 0;
     left: 0;
     bottom: 0;
     background-repeat: no-repeat;
     background-position:center; center;
     background-size: auto auto;
     background-image: url(../images/preload.png); /* your picture should be here */
     background-color:#000; 
     z-index:99;
 }
</style>
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                All Company List
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin-dash')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{url('/company-list')}}">Company List</a> </li>
                    </ul>
                </div>
            </div>
        </div>
  </div>
  <div class="card">
    <div class="card-block">
          <div class="row wrap_div">    
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <label>Company Type</label>
                  <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="company_type" name="company_type">
                      <option>Select Company Type</option>
                      <?php foreach ($company_type as $key => $value) { ?>
                          <option value="<?php echo $value->company_type; ?>"><?php echo $value->company_type; ?></option>
                      <?php } ?>
                  </select>
              </div>
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <label>Status</label>
                  <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="status" name="status">
                      <option>Select Status</option>
                      <?php foreach ($status as $key => $value) { ?>
                          <option value="<?php echo $value->status; ?>"><?php echo $value->status; ?></option>
                      <?php } ?>
                  </select>
              </div>
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <label>Sale Rept</label>
                  <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="sale_rept" name="sale_rept">
                      <option>Select Sale Rept</option>
                      <?php foreach ($sale_rept as $key => $value) { ?>
                          <option value="<?php echo $value->sale_rept; ?>"><?php echo $value->sale_rept; ?></option>
                      <?php } ?>
                  </select>
              </div>
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <label>Sale Terms</label>
                  <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="sale_terms" name="sale_terms">
                      <option>Select Sale Terms</option>
                      <?php foreach ($sale_terms as $key => $value) { ?>
                          <option value="<?php echo $value->sale_terms; ?>"><?php echo $value->sale_terms; ?></option>
                      <?php } ?>
                  </select>
              </div>
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <label>Country</label>
                  <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="country" name="country">
                      <option>Select Country</option>
                      <?php foreach ($country as $key => $value) { ?>
                          <option value="<?php echo $value->country; ?>"><?php echo $value->country; ?></option>
                      <?php } ?>
                  </select>
              </div>
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <label>State</label>
                  <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="state" name="state">
                      <option>Select State</option>
                      <?php foreach ($state as $key => $value) { ?>
                          <option value="<?php echo $value->state; ?>"><?php echo $value->state; ?></option>
                      <?php } ?>
                  </select>
              </div>
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <label>City</label>
                  <select class="js-example-basic-multiple-limit col-sm-12 form-control" id="city" name="city">
                      <option>Select City</option>
                      <?php foreach ($city as $key => $value) { ?>
                          <option value="<?php echo $value->city; ?>"><?php echo $value->city; ?></option>
                      <?php } ?>
                  </select>
              </div>
        </div>
        <br>
        <img class="img-fluid rounded img-thumbnail" style="display:none;" id="overlay" src="{{asset('images/Loading_icon.gif')}}">
        <div class="table-responsive dt-responsive" id="myDiv">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                   <tr>
                       <th>Company Name</th>
                       <th>Company Type</th>
                       <th>Status</th>
                       <th>Decoded (Yes/No)</th>
                       <th>FOMO Sales Rep</th>
                       <th>Sales Terms</th>
                       <th>Country</th>
                       <th>State</th>
                       <th>City</th>
                       <th>Area</th>
                       <th>Address</th>
                       <th>Website</th>
                       <th>Company Email</th>
                       <th>Company No.</th>
                       <th>Contact Person</th>
                       <th>Designation</th>
                       <th>Contact Email</th>
                       <th>Contact No.</th>
                       <th>Remarks</th>
                       <th>#</th>  
                   </tr>
               </thead>
                   <tbody class="wrap_tbody">
                     @foreach($companies as $e)
                     <tr>
                         <td>{{$e->company_name }}</td>
                         <td>{{$e->company_type }}</td>
                         <td>{{$e->status }}</td>
                         <td>{{$e->decoded }}</td>
                         <td>{{$e->sale_rept }}</td>
                         <td>{{$e->sale_terms }}</td>
                         <td>{{$e->country }}</td>
                         <td>{{$e->state }}</td>
                         <td>{{$e->city }}</td>
                         <td>{{$e->area }}</td>
                         <td>{{$e->address }}</td>
                         <td>{{$e->website }}</td>
                         <td>{{$e->company_email }}</td>
                         <td>{{$e->contact_number }}</td>
                         <td>{{$e->contact_person }}</td>
                         <td>{{$e->designation }}</td>
                         <td>{{$e->contact_email }}</td>
                         <td>{{$e->contact_number }}</td>
                         <td>{{$e->remarks }}</td>
                         <td class="btn-group" style="display:flex;">
                             <a href="{{url('/view-company/'.$e->id)}}" class="btn btn-primary btn-xs">
                              <i class="fa fa-search"></i>
                          </a>
                          <a href="{{url('/edit-company/'.$e->id)}}" class="btn btn-info btn-xs">
                             <i class="fa fa-edit"></i>
                         </a>&nbsp;
                         <a href="{{url('/delete-company/'.$e->id)}}" class="btn btn-danger btn-xs">
                             <i class="fa fa-trash"></i>
                         </a>&nbsp;
                     </td>
                 </tr>
                 @endforeach
             </tbody>
             <tfoot>
               <tr>
                   <th>Company Name</th>
                   <th>Company Type</th>
                   <th>Status</th>
                   <th>Decoded (Yes/No)</th>
                   <th>FOMO Sales Rep</th>
                   <th>Sales Terms</th>
                   <th>Country</th>
                   <th>State</th>
                   <th>City</th>
                   <th>Area</th>
                   <th>Address</th>
                   <th>Website</th>
                   <th>Company Email</th>
                   <th>Company No.</th>
                   <th>Contact Person</th>
                   <th>Designation</th>
                   <th>Contact Email</th>
                   <th>Contact No.</th>
                   <th>Remarks</th>
                   <th>#</th>  
               </tr>
             </tfoot>
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
   $(document).ready(function () {
      $(".wrap_div").on('keyup change', function (e){
        var company_type =  $('#company_type').find(":selected").val();
        var status =  $('#status').find(":selected").val();
        var sale_rept =  $('#sale_rept').find(":selected").val();
        var sale_terms =  $('#sale_terms').find(":selected").val();
        var country =  $('#country').find(":selected").val();
        var state =  $('#state').find(":selected").val();
        var city =  $('#city').find(":selected").val();
        if ($('#company_type').find(":selected").val() !="Select Company Type"){
            var company_type =  $('#company_type').find(":selected").val();
        }else{
            var company_type =  '';
        }

        if ($('#status').find(":selected").val() !="Select Status"){
            var status =  $('#status').find(":selected").val();
        }else{
            var status =  '';
        }

        if ($('#sale_rept').find(":selected").val() !="Select Sale Rept"){
            var sale_rept =  $('#sale_rept').find(":selected").val();
        }else{
            var sale_rept =  '';
        }

        if ($('#sale_terms').find(":selected").val() !="Select Sale Terms"){
            var sale_terms =  $('#sale_terms').find(":selected").val();
        }else{
            var sale_terms =  '';
        }

        if ($('#country').find(":selected").val() !="Select Country"){
            var country =  $('#country').find(":selected").val();
        }else{
            var country =  '';
        }

        if ($('#state').find(":selected").val() !="Select State"){
            var state =  $('#state').find(":selected").val();
        }else{
            var state =  '';
        }

        if ($('#city').find(":selected").val() !="Select City"){
            var city =  $('#city').find(":selected").val();
        }else{
            var city =  '';
        }
        load_data(company_type, status, sale_rept, sale_terms, country, state, city);
    });
    });
     function load_data(full_text_search_query = ''){
       $.ajax({
          url:"{{ url('search') }}",
          method:"POST",
          data:{"company_type":"company_type", "status":"status", "sale_rept":"sale_rept", "sale_terms":"sale_terms", "country":"country", "state":"state", "city":"city", "_token":"{{ csrf_token() }}"},
          dataType:"json",
          success:function(data){
             var output = '';
             if(data.length > 0){
                for(var count = 0; count < data.length; count++){
                   output += '<tr>';
                   output += '<td>'+data[count].company_name+'</td>';
                   output += '<td>'+data[count].company_type+'</td>';
                   output += '<td>'+data[count].status+'</td>';
                   output += '<td>'+data[count].decoded+'</td>';
                   output += '<td>'+data[count].sale_rept+'</td>';
                   output += '<td>'+data[count].sale_terms+'</td>';
                   output += '<td>'+data[count].country+'</td>';
                   output += '<td>'+data[count].state+'</td>';
                   output += '<td>'+data[count].city+'</td>';
                   output += '<td>'+data[count].area+'</td>';
                   output += '<td>'+data[count].address+'</td>';
                   output += '<td>'+data[count].website+'</td>';
                   output += '<td>'+data[count].company_email+'</td>';
                   output += '<td>'+data[count].company_number+'</td>';
                   output += '<td>'+data[count].contact_person+'</td>';
                   output += '<td>'+data[count].designation+'</td>';
                   output += '<td>'+data[count].contact_email+'</td>';
                   output += '<td>'+data[count].remarks+'</td>';
                   output += '<td>'+data[count].remarks+'</td>';
                   output += '</tr>';
                 }
             }else{
                     output += '<tr>';
                     output += '<td colspan="12">No Data Found</td>';
                     output += '</tr>';
                 }
             $('.wrap_tbody').html(output);
             $('#example').dataTable();   
         }
     });
   }
</script>
@endsection
