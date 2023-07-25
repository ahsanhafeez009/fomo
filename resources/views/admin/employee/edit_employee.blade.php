@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                Edit {{title}} Details
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
                            action="{{url('/update-employee-profile')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{$cdetail->id}}">
                            <div class="row form-group">
                                <div class="col-12 text-center">
                                    <img class="img-fluid circle"
                                    src="{{$mdetail->avatar?asset('storage/pics/'.$mdetail->avatar):asset('images/nouser.png')}}"><br/>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <label>Name</label>
                                    <input name="employee_name" type="text" value="{{old('name', $cdetail->employee_name)}}" class="form-control" placeholder="Full name">
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
    <script type="text/javascript">
    </script>
@endsection
