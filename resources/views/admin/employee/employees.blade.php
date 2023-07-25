@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                All Employee List
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin-dash')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{url('/employees-list')}}">Employee List</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @if(count($employees)>0)
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr class="bg-primary text-white">
                            <th>SN</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>#</th>  
                        </tr>
                        </thead>
                        <tbody>
                        @php($sn=($employees->currentpage()-1)* $employees->perpage() + 1)
                        @foreach($employees as $e)
                            <tr>
                                <td>{{$sn++}}</td>
                                <td>{{$e->employee_name }}</td>
                                <td>{{$e->created_at}}</td>
                                <td class="btn-group" style="display:flex;">
                                    <a href="{{url('/view-employee/'.$e->id)}}" class="btn btn-primary btn-xs">
                                        <i class="fa fa-search"></i>
                                    </a>
                                    <a href="{{url('/edit-employee/'.$e->id)}}" class="btn btn-info btn-xs">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{url('/delete-employee/'.$e->id)}}" class="btn btn-danger btn-xs">   
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    {!! ('No Employees available to display') !!}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('header')
@endsection
@section('footer')
@endsection
