@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-12">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item"><a href="{{url('/admin-setting')}}">Admin Setting</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2">
                    <div class="dt-card">
                        <div class="dt-card__body">
                            <form method="post" action="{{url('/save-admin-setting')}}">
                                @csrf
                                <div class="form-group">
                                    <label for="email-1">Email address*</label>
                                    <input required type="email" class="form-control" value="{{old('email', $data->email)}}"
                                    name="email" id="email-1"
                                    placeholder="Enter email">
                                </div>
                                <div class="form-group">
                                    <label for="password-1">Password</label>
                                    <input type="password" class="form-control" name="password" id="password-1"
                                    placeholder="Password">
                                    <small id="emailHelp1" class="form-text text-danger">Note: (Leave Password field Blank if
                                        you
                                        do not want to change)
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="password-2">Retype Password</label>
                                    <input type="password" class="form-control" name="retype_password" id="password-2"
                                    placeholder="Retype Password">
                                    <small id="emailHelp1" class="form-text text-danger">Note: (Leave Retype Password field
                                        Blank if you
                                        do not want to change)
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="password-3">New Password*</label>
                                    <input type="password" required class="form-control" name="new_password" id="password-3"
                                    placeholder="New Password">
                                </div>
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-primary wrap_right">Update</button>
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
