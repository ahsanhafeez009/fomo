@extends('layouts.app')

@section('content')
<div class="hero-wrap d-flex align-items-center h-100">
        <div class="hero-mask opacity-4 bg-secondary"></div>
        <div class="hero-bg hero-bg-scroll" style="background-image:url('{{asset('images/member-bg.jpg')}}');"></div>
        <div class="hero-content mx-auto w-100 h-100">
            <div class="container">
                <div class="row no-gutters min-vh-100">
                    <!-- Welcome Text
                    ========================= -->
                    <div class="col-md-6 d-flex flex-column">
                        <div class="row no-gutters my-auto">
                            <div class="col-10 col-lg-9 mx-auto text-center">
                                <div class="logo">
                                    <a href="{{url('/admin')}}" title="logo">
                                        <img src="{{asset('images/logo.png')}}" class="img-fluid" alt="logo">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Welcome Text End -->

                    <!-- Login Form
                    ========================= -->
                    <div class="col-md-6 d-flex align-items-center py-5">
                        <div class="container my-auto py-4 shadow-lg bg-white">
                            <div class="row">
                                @section('content')
                                <div class="col-11 col-lg-11 mx-auto">
                                    <h3 class="text-9 font-weight-600 text-center mt-2 mb-3">Sign In</h3>
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        @if(session('msg'))
                                        {!! session('msg') !!}
                                        @endif
                                        @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger" style="color:#000"><i
                                            class="icon-exclamation"></i> {{$error}}
                                        </div>
                                        @endforeach
                                        @endif
                                        <div class="form-group">
                                            <label class="text-dark font-weight-600" for="emailAddress">Username or
                                            Email Address</label>
                                            <input type="text" name="email" class="form-control rounded-0"
                                            id="emailAddress"
                                            required placeholder="Enter Your Email">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-dark font-weight-600"
                                            for="loginPassword">Password</label>
                                            <input type="password" name="password" class="form-control rounded-0"
                                            id="loginPassword"
                                            required placeholder="Enter Password">
                                        </div>
                                        <button class="btn btn-dark btn-block rounded-0 my-4" type="submit">Sign In
                                        </button>
                                    </form>
                                </div>
                                @show
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
