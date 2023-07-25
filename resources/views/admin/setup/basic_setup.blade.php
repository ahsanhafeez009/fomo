@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin-dash')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{url('/basic-setup')}}">Basic Panel Setting</a> </li>
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
                            <form class="forms" method="post" enctype="multipart/form-data"
                            action="{{url('/save-basic-setup')}}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label>Company Legal Name*</label>
                                    <input required type="text" class="form-control"
                                    value="{{old('LEGAL_NAME', env('LEGAL_NAME'))}}"
                                    name="LEGAL_NAME">
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Company Brand/Short Name*</label>
                                    <input required type="text" class="form-control"
                                    value="{{old('APP_NAME', env('APP_NAME'))}}"
                                    name="APP_NAME">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label>Company Email*</label>
                                    <input type="text" class="form-control"
                                    value="{{old('COMPANY_MAIL', env('COMPANY_MAIL'))}}"
                                    name="COMPANY_MAIL">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Company Phone No*</label>
                                    <input type="text" class="form-control"
                                    value="{{old('COMPANY_PHONE', env('COMPANY_PHONE'))}}"
                                    name="COMPANY_PHONE">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label>Replace Logo</label>
                                    <div class="mb-6">
                                        <input type="file" class="form-control" name="logo" id="logo-field">
                                    </div>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Replace Favicon</label>
                                    <div class="mb-6">
                                        <input type="file" class="form-control" name="favicon" id="favicon-field">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="password-3">Current Admin Password*</label>
                                <input type="password" required class="form-control" name="current_password" id="password-3"
                                placeholder="Current Password">
                            </div>
                            <div class="form-group mb-0 wrap_right">
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
@section('footer')
    <script>
        $('#logo-field').on('change', function () {
            var fileName = $(this).val();
            var fileName = $(this).val().replace('C:\\fakepath\\', " ");
            $(this).next('#logo-field-label').html(fileName);
        });
        $('#favicon-field').on('change', function () {
            var fileName = $(this).val();
            var fileName = $(this).val().replace('C:\\fakepath\\', " ");
            $(this).next('#favicon-field-label').html(fileName);
        })
    </script>
@endsection
