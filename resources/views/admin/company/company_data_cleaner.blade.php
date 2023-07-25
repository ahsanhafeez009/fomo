@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                All Company Data Cleaner List
            </div>
            <!-- <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin-dash')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{url('/company-data-cleaner')}}">Companies</a> </li>
                    </ul>
                </div>
            </div> -->
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @if(count($companies)>0)
            <div class="table-responsive dt-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Company Name</th>
                            <th>Date</th>
                            <th>#</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sn = 1; ?>
                        @foreach($companies as $e)
                        <tr>
                            <td><?php echo $sn; ?></td>
                            <td>{{$e->company_name }}</td>
                            <td>{{$e->created_at }}</td>
                            <td class="btn-group" style="display:flex;">
                                <a href="{{url('/approve-temp-company/'.$e->id)}}" class="btn btn-success btn-xs">
                                    <i class="fa fa-check"></i>
                                </a>
                                <a href="{{url('/delete-temp-company/'.$e->id)}}" class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash"></i>
                                </a>&nbsp;
                                <a href="{{url('/view-temp-company/'.$e->id)}}" class="btn btn-primary btn-xs">
                                    <i class="fa fa-search"></i>
                                </a>
                                <a href="{{url('/edit-temp-company/'.$e->id)}}" class="btn btn-info btn-xs">
                                    <i class="fa fa-edit"></i>
                                </a>&nbsp;
                            </td>
                        </tr>
                    <?php $sn++; ?>
                    @endforeach
                    </tbody>
                    <tfoot>
                         <tr>
                            <th>SN</th>
                            <th>Company Name</th>
                            <th>Date</th>
                            <th>#</th> 
                        </tr>
                    </tfoot>
                </table>
                @else
                {!! norecord('No Company available to display') !!}
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

