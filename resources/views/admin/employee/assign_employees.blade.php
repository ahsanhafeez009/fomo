@extends('admin.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
               Assign Employees
            </div>
            <div class="col-lg-4">
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="row">
                <div class="col-sm-12">
                    <form method="post" action="{{url('/assign-employee-to-company')}}">
                        @csrf
                        <div class="row form-group">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label>Select a Employee</label>
                                <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="employee_id">
                                    <option>Select Employee</option>
                                    @foreach($employees as $e)
                                    <option value="{{$e->id}}">{{$e->employee_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label>Select a Company</label>
                                <select class="js-example-basic-multiple-limit col-sm-12 form-control" name="company_id">
                                    <option>Select Company</option>
                                    @foreach($companies as $e)
                                    <option value="{{$e->id}}">{{$e->company_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" style="float:right;">Submit</button>
                    </form>
                </div>
            </div>
            <div class="card-block">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th>SN</th>
                            <th>Employee Name</th>
                            <th>Company Name</th>
                            <th>Date</th>
                            <th>#</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        ?>
                        @foreach($assignemployees as $e)
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td>{{$e->employee_name}}</td>
                            <td>{{$e->company_name}}</td>
                            <td>{{$e->created_at}}</td>
                            <td class="btn-group">
                             <a href="{{url('/edit-assignemployees/'.$e->id)}}" class="btn btn-info btn-xs">
                                <i class="fa fa-edit"></i>
                            </a>&nbsp;
                            <a href="{{url('/delete-assignemployees/'.$e->id)}}" data-fancybox class="btn btn-danger btn-xs">
                                <i class="fa fa-trash"></i>
                            </a>&nbsp;
                        </td>
                    </tr>
                    <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
@section('header')
@endsection
@section('footer')
@endsection