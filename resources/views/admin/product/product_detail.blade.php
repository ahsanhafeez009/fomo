@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                {{$heading}} Details
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin-dash')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{url('/product-dash')}}">Product Dashboard</a> </li>
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
                        <img class="img-fluid rounded img-thumbnail" style="max-width: 100%; width: 50%; height: auto" src="{{asset('images/perfume.jpg')}}">
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Product Name: </strong> {{$cdetail->product_name}}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Brand Name: </strong> {{$cdetail->brand_name}}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Brand Type: </strong> {{$cdetail->brand_type}}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Barcode: </strong> {{$cdetail->barcode}}
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Bottle Size: </strong> {{$cdetail->bottle_size}}
                            </p>
                        </div><div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Product Gender: </strong> {{$cdetail->product_gender}}
                            </p>
                        </div><div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Product Type: </strong> {{$cdetail->product_type}}
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong class="font-weight-800 dt-card__title text-primary">Registration
                            Date: </strong> {{$cdetail->created_at}}</p>
                        </div>
                    </div>
                    <hr/>
                </div>
                @else
                {!! errorrecord('No Product Found') !!}
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
