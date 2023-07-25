@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
            </div>
            <div class="col-lg-4">
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="container-fluid" id="main">
                <div class="row row-offcanvas row-offcanvas-left">
                    <div class="col main pt-5 mt-3">
                        <div class="row mb-3">
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 py-2">
                                <div class="card text-white bg-info h-100">
                                    <div class="card-body bg-info">
                                        <div class="rotate">
                                             <i class="fa fa-user fa-4x"></i>
                                        </div>
                                        <h6 class="text-uppercase">Users</h6>
                                        <h1 class="display-4">{{$total_users}}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 py-2">
                                <div class="card bg-success text-white h-100">
                                    <div class="card-body bg-success">
                                        <div class="rotate">
                                            <i class="fa fa-building fa-4x"></i>
                                            </div>
                                        <h6 class="text-uppercase">Companies</h6>
                                        <h1 class="display-4">{{$total_companies}}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 py-2">
                                <div class="card text-white bg-danger h-100">
                                    <div class="card-body bg-danger">
                                        <div class="rotate">
                                            <i class="fa fa-list fa-4x"></i>
                                        </div>
                                        <h6 class="text-uppercase">Products</h6>
                                        <h1 class="display-4">{{$total_products}}</h1>
                                    </div>
                                </div>
                            </div>
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
