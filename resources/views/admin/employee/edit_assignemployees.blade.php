@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                Edit Assign Employee Data
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
                            <form method="post" enctype="multipart/form-data" id="form"
                            action="{{url('/update-assign-employee-to-company')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{$cdetail->id}}">
                            <div class="row form-group">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label>Select a Employee</label>
                                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="employee_id">
                                        <option>Select Employee</option>
                                        @foreach($employees as $e)
                                        <option value="{{$e->id}}" <?php echo $cdetail->employee_id == $e->id  ? 'selected' : '' ; ?>>{{$e->employee_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <label>Select a Company</label>
                                    <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="company_id">
                                        <option>Select Company</option>
                                        @foreach($companies as $e)
                                        <option value="{{$e->id}}" <?php echo $cdetail->company_id == $e->id  ? 'selected' : '' ; ?>>{{$e->company_name}}</option>
                                        @endforeach
                                    </select>
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
