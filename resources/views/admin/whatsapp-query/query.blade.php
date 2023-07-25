@extends('admin.template')
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                All Whatsapp Query
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin-dash')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{url('/whatsapp-query')}}">Whatsapp Query</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        
    </div>
</div>
@endsection
@section('header')
@endsection
@section('footer')
@endsection

