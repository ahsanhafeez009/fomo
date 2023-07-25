@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-12">
                All Brands List
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @if(count($brands)>0)
            <div class="table-responsive dt-responsive" id="myDiv">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead class="nexa-dark font-weight-bold">
                        <tr>
                            <th scope="col">SN</th>
                            <th scope="col">Brand Name</th>
                            <th scope="col">Date</th>
                            <th scope="col">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sn = 1; ?>
                        @foreach($brands as $e)
                        <tr>
                            <td><?php echo $sn; ?></td>
                            <td>{{$e->brand_name}}</td>
                            <td>{{$e->created_at}}</td>
                            <td class="btn-group" style="display: flex;">
                            <a href="{{url('/delete-brand/'.$e->id)}}" data-fancybox class="btn btn-danger btn-xs">
                                <i class="fa fa-trash"></i>
                            </a>&nbsp;
                            </td>
                        </tr>
                        <?php $sn++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            {!! norecord('No Brand available to display') !!}
            @endif
        </div>
    </div>
</div>
@endsection
@section('header')
@endsection
@section('footer')

@endsection

