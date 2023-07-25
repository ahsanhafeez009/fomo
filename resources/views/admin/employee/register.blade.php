@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                Create a Employee
            </div>
            <div class="col-lg-4">
                
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="row">
                <div class="col-sm-12">
                    <form method="post" action="{{url('/create-employee')}}">
                        @csrf
                        <div class="row form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Employee Name*</label>
                                <input name="employee_name" type="text" value="{{old('employee_name')}}" class="form-control"
                                placeholder="Enter Full name">
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" style="float:right;">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('header')
@endsection
@section('footer')
@endsection
