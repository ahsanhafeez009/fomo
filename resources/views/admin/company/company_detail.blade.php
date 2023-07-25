@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                {{$title}} Details
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin-dash')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{url('/company-dash')}}">Company Dashboard</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="row">
                <div class="container" style="max-width: 700px">
                    @if(!empty($cdetail))
                    <div class="text-center">
                        <img class="img-fluid rounded img-thumbnail" style="max-width: 100%; width: 30%; height: auto" src="{{asset('images/company.jpg')}}">
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Name: </strong> {{$cdetail->company_name}}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Email: </strong> {{$cdetail->company_email}}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Status: </strong> {{$cdetail->status}}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Company Type: </strong> {{$cdetail->company_type}}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">FOMO Incharge: </strong> {{$cdetail->fomo_incharge}}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Payment Terms: </strong> {{$cdetail->payment_terms}}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Country: </strong> {{$cdetail->country}}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">State: </strong> {{$cdetail->state}}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">City: </strong> {{$cdetail->city}}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Address: </strong> {{$cdetail->address}}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Registration
                            Date: </strong> {{$cdetail->created_at}}</p>
                        </div>
                    </div>
                </div>
                @else
                {!! errorrecord('No Member Found') !!}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('header')
    <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css"/>
    <style type="text/css">
        @media only screen and (min-width: 768px) {
            .fancybox-content {min-width: 500px}
            }
    </style>
@endsection
@section('footer')
    <script src="//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
@endsection
