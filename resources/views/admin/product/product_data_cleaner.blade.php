@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                All Product Data Cleaner List
            </div>
            <!-- <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin-dash')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{url('/product-data-cleaner')}}">Product Data Cleaner</a> </li>
                    </ul>
                </div>
            </div> -->
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @if(count($products)>0)
            <div class="table-responsive dt-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead class="nexa-dark font-weight-bold">
                        <tr>
                            <th scope="col">SN</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Date</th>
                            <th scope="col">#</th>
                        </tr>
                    </thead>
                    <?php $sn = 1; ?>
                    <tbody>
                        @foreach($products as $e)
                        <tr>
                            <td><?php echo $sn; ?></td>
                            <td>{{$e->product_name}}</td>
                            <td>{{$e->created_at}}</td>
                            <td class="btn-group" style="display: flex;">
                                <a href="{{url('/approve-temp-product/'.$e->id)}}" class="btn btn-success btn-xs">
                                    <i class="fa fa-check"></i>
                                </a>
                                <a href="{{url('/delete-temp-product/'.$e->id)}}" class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash"></i>
                                <a href="{{url('/view-temp-product/'.$e->id)}}" class="btn btn-primary btn-xs">
                                    <i class="fa fa-search"></i>
                                </a>
                                <a href="{{url('/edit-temp-product/'.$e->id)}}" class="btn btn-info btn-xs">
                                    <i class="fa fa-edit"></i>
                                </a>&nbsp;
                            </td>
                        </tr>
                        <?php $sn++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            {!! norecord('No Product available to display') !!}
            @endif
        </div>
    </div>
</div>
@endsection
@section('header')
@endsection
@section('footer')
@endsection

