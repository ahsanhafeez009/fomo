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
                            <a href="{{url('/company-dash')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{url('/company-dash')}}">Customer Dashboard</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('header')
    <style type="text/css">
    </style>
@endsection
@section('footer')
    <script type="text/javascript">
    </script>
@endsection
