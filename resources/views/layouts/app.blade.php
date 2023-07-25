<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
    <title>Login - {{env('APP_NAME')}}</title>
    <link rel="shortcut icon" href="{{asset('images/logo.png')}}" type="image/x-icon">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900'
    type='text/css'>
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/admintheme.css')}}"/>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="preloader">
        <div class="lds-ellipsis">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div id="main-wrapper" class="oxyy-login-register">
    <div class="hero-wrap d-flex align-items-center h-100">
        <div class="hero-mask opacity-4 bg-secondary"></div>
        <div class="hero-bg hero-bg-scroll"></div>
        <div class="hero-content mx-auto w-100 h-100">
            <div class="container">
                <div class="row no-gutters min-vh-100">
                    <!-- Welcome Text
                    ========================= -->
                    <div class="col-md-6 d-flex flex-column">
                        <div class="row no-gutters my-auto">
                            <div class="col-10 col-lg-9 mx-auto text-center">
                                <div class="logo mt-5 mb-3">
                                    <img src="{{asset('images/logo.png')}}" class="img-fluid" alt="logo">
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
</div>
<!-- Script -->
<script src="{{asset('admin_assets/js/jquery.js')}}"></script>
<script src="{{asset('admin_assets/js/bootstrap.js')}}"></script>
<script src="{{asset('admin_assets/js/admintheme.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    @if(Session::has('success-message'))
    toastr.options = {
        "closeButton" : true,
        "progressBar" : true
    }
    toastr.success("{{ session('success-message') }}");
    @endif

    @if(Session::has('error-message'))
    toastr.options = {
        "closeButton" : true,
        "progressBar" : true
    }
    toastr.error("{{ session('error-message') }}");
    @endif

    @if(Session::has('info-message'))
    toastr.options = {
        "closeButton" : true,
        "progressBar" : true    
    }
    toastr.info("{{ session('info-message') }}");
    @endif

    @if(Session::has('warning-message'))
    toastr.options = {
        "closeButton" : true,
        "progressBar" : true
    }
    toastr.warning("{{ session('warning-message') }}");
    @endif
</script>
</body>
</html>

