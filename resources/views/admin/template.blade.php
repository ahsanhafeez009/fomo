<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>{{$title}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Admindek Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords" content="flat ui, admin Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="colorlib" />
    <link rel="shortcut icon" href="{{asset('images/logo.png')}}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
           integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/feather.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/font-awesome-n.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/chartist.css')}}" type="text/css" media="all">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/pages.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/widget.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
    <style type="text/css">
        button.wrap_right {
            float: right;
        }
        a.btn.btn-primary.wrap_right {
            float: right;
        }
        .bg-primary {
            background-color: #263544!important;
        }
        body[themebg-pattern=theme1] {
            background-color: #263544 !important;
        }
        .loader-bg .loader-bar {
            background: #263544 !important;
        }
        .margin_bottom_{
            margin-bottom: 10px;
        }
        .page-header.card {
            margin: 30px 20px 15px !important;
        }
        .btn-primary:hover {
            color: #eeeeee;
            background-color: #0069d9 !important;
            border-color: #0062cc !important;
        }
        .select2-container {
            width: 100% !important;
        }

        #example_length .select2-container {
            width: 47% !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            background-color: #263544 !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #263544 !important;
        }
        .btn-primary, .sweet-alert button.confirm, .wizard>.actions a {
            background-color: black !important;
            border-color: black !important;
            color: #fff !important;
            cursor: pointer !important;
        }
        .btn-primary:hover {
            background-color: black !important;
            border-color: black !important;
            color: #fff !important;
            cursor: pointer !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #263544 !important;
            border: 1px solid #263544 !important;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #263544 !important;
            color: white !important;
        }
        .page-item.active .page-link {
            background-color: #263544 !important;
            border-color: #263544 !important;
            color: #fff !important;
        }
        .page-link {
            color: #263544 !important;
        }
        .select2-container--default:focus {
            border-color: #263544;
        }
        .pcoded-submenu li.active>a, .pcoded-item li .pcoded-submenu li:hover>a {
            color: #ffffff !important;
        }
        .navbar_item_display{
            display: block !important;
        }
        .img-fluid {
            max-width: 40% !important;
        }
        /*table.dataTable tbody tr:hover {
            background-color: #263544 !important;
            color: white !important;
        }
        table.dataTable tbody tr:hover a {
            color: white !important;
        }*/
        table.dataTable.table tbody tr.odd {
            color: white;
            background: #263544;
        }

        table.dataTable.table tbody tr.odd a{
            color: white !important;
        }
        
        table.dataTable tbody tr:hover {
            cursor: pointer;
        }
    </style>
</head>
<body class="dt-header--fixed theme-semidark dt-layout--full-width" data-gr-c-s-loaded="true" cz-shortcut-listen="true">
    <div class="loader-bg">
        <div class="loader-bar"></div>
    </div>
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <nav class="navbar header-navbar pcoded-header">
                <div class="navbar-wrapper">
                    <div class="navbar-logo">
                        <a href="{{url('/home')}}" title="logo">
                            <img src="{{asset('images/white-logo.png')}}" class="img-fluid" alt="logo">
                        </a>
                        <!-- <a href="{{url('/home')}}" style="font-size: 20px !important;font-weight: bold;">
                            {{env('APP_NAME')}}
                        </a> -->
                    </div>
                    <div class="navbar-container container-fluid">
                        <ul class="nav-right">
                            <li class="user-profile header-notification">
                                <div class="dropdown-primary dropdown">
                                    <div class="dropdown-toggle" data-toggle="dropdown">
                                        <img src="{{asset('admin_assets/images/avatar-4.jpg')}}" class="img-radius" alt="User-Profile-Image">
                                        <span> {{ Auth::user()->name ?? 'None' }}</span>
                                        <i class="feather icon-chevron-down"></i>
                                    </div>
                                    <ul class="show-notification profile-notification dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                        <li>
                                            <a href="{{url('/admin-setting')}}">
                                                <i class="feather icon-settings"></i> Settings
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                <i class="feather icon-log-out"></i> {{ __('Logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <nav class="pcoded-navbar">
                        <div class="nav-list">
                            <div class="pcoded-inner-navbar main-menu">
                                <ul class="pcoded-item pcoded-left-item">
                                    <li class="pcoded-hasmenu">
                                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                                            <span class="pcoded-micon">
                                                <i class="feather icon-menu"></i>
                                            </span>
                                            <span class="pcoded-mtext">Customer Database</span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                                <?php $urlpage = $_SERVER['REQUEST_URI'];?>
                                                <?php if ($urlpage == "/company-list") { ?>
                                                    <li class="active">
                                                       <a href="{{url('/company-list')}}" class="waves-effect waves-dark">
                                                            <span class="pcoded-mtext">Company List</span>
                                                        </a>
                                                    </li>
                                                    <li class="">
                                                        <a href="{{url('/create-company')}}" class="waves-effect waves-dark">
                                                            <span class="pcoded-mtext">Create a Company</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($urlpage == "/create-company") { ?>
                                                    <li class="">
                                                     <a href="{{url('/company-list')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Company List</span>
                                                    </a>
                                                </li>
                                                <li class="active">
                                                    <a href="{{url('/create-company')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Create a Company</span>
                                                    </a>
                                                </li>
                                                <?php } ?>
                                                <?php if ($urlpage != "/company-list" && $urlpage != "/create-company") { ?>
                                                    <li class="">
                                                   <a href="{{url('/company-list')}}" class="waves-effect waves-dark ">
                                                        <span class="pcoded-mtext">Company List</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/create-company')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Create a Company</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                </ul>
                                <ul class="pcoded-item pcoded-left-item">
                                    <li class="pcoded-hasmenu">
                                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                                            <span class="pcoded-micon">
                                                 <i class="feather icon-menu"></i>
                                            </span>
                                            <span class="pcoded-mtext">Product Database</span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                            <?php $urlpage = $_SERVER['REQUEST_URI'];?>
                                            <?php if ($urlpage == "/product-list") { ?>
                                                <li class="active">
                                                    <a href="{{url('/product-list')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Product List</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/create-product')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Create a Product</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($urlpage == "/create-product") { ?>
                                                <li class="">
                                                    <a href="{{url('/product-list')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Product List</span>
                                                    </a>
                                                </li>
                                                <li class="active">
                                                    <a href="{{url('/create-product')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Create a Product</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($urlpage != "/product-list" && $urlpage != "/create-product") { ?>
                                                <li class="">
                                                    <a href="{{url('/product-list')}}" class="waves-effect waves-dark ">
                                                        <span class="pcoded-mtext">Product List</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/create-product')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Create a Product</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                </ul>
                                <ul class="pcoded-item pcoded-left-item">
                                    <li class="pcoded-hasmenu">
                                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                                            <span class="pcoded-micon">
                                                 <i class="feather icon-menu"></i>
                                            </span>
                                            <span class="pcoded-mtext">Customer Demand Module</span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                            <?php $urlpage = $_SERVER['REQUEST_URI'];?>
                                            <?php if ($urlpage == "/customer-demand-uploading") { ?>
                                                <li class="active">
                                                    <a href="{{url('/customer-demand-uploading')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demand Upload</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/customer-demand-files')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demand Files</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/company-data-cleaner')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Company Data Cleaner</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/product-data-cleaner')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Product Data Cleaner</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/customer-demands')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demands</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($urlpage == "/customer-demand-files") { ?>
                                                <li class="">
                                                    <a href="{{url('/customer-demand-uploading')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demand Upload</span>
                                                    </a>
                                                </li>
                                                <li class="active">
                                                    <a href="{{url('/customer-demand-files')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demand Files</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/company-data-cleaner')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Company Data Cleaner</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/product-data-cleaner')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Product Data Cleaner</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/customer-demands')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demands</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($urlpage == "/company-data-cleaner") { ?>
                                                <li class="">
                                                    <a href="{{url('/customer-demand-uploading')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demand Upload</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/customer-demand-files')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demand Files</span>
                                                    </a>
                                                </li>
                                                <li class="active">
                                                    <a href="{{url('/company-data-cleaner')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Company Data Cleaner</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/product-data-cleaner')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Product Data Cleaner</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/customer-demands')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demands</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($urlpage == "/product-data-cleaner") { ?>
                                                <li class="">
                                                    <a href="{{url('/customer-demand-uploading')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demand Upload</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/customer-demand-files')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demand Files</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/company-data-cleaner')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Company Data Cleaner</span>
                                                    </a>
                                                </li>
                                                <li class="active">
                                                    <a href="{{url('/product-data-cleaner')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Product Data Cleaner</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/customer-demands')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demands</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($urlpage == "/customer-demands") { ?>
                                                <li class="">
                                                    <a href="{{url('/customer-demand-uploading')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demand Upload</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/customer-demand-files')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demand Files</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/company-data-cleaner')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Company Data Cleaner</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/product-data-cleaner')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Product Data Cleaner</span>
                                                    </a>
                                                </li>
                                                <li class="active">
                                                    <a href="{{url('/customer-demands')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demands</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($urlpage != "/customer-demand-uploading" && $urlpage != "/customer-demand-files" && $urlpage != "/company-data-cleaner" && $urlpage != "/product-data-cleaner" && $urlpage != "/customer-demands") { ?>
                                                <li class="">
                                                    <a href="{{url('/customer-demand-uploading')}}" class="waves-effect waves-dark ">
                                                        <span class="pcoded-mtext">Customer Demand Upload</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/customer-demand-files')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demand Files</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/company-data-cleaner')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Company Data Cleaner</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/product-data-cleaner')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Product Data Cleaner</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/customer-demands')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Customer Demands</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                </ul>
                                <ul class="pcoded-item pcoded-left-item">
                                    <li class="pcoded-hasmenu">
                                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                                            <span class="pcoded-micon">
                                                 <i class="feather icon-menu"></i>
                                            </span>
                                            <span class="pcoded-mtext">Supplier Module</span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                            <?php $urlpage = $_SERVER['REQUEST_URI'];?>
                                            <?php if ($urlpage == "/users-demands") { ?>
                                                <li class="active">
                                                    <a href="{{url('/users-demands')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Product Demand Upload</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/supplier-record')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Supplier Prices Upload</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/supplier-price-analysis')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Supplier Price Analysis</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($urlpage == "/supplier-record") { ?>
                                                <li class="">
                                                    <a href="{{url('/users-demands')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Product Demand Upload</span>
                                                    </a>
                                                </li>
                                                <li class="active">
                                                    <a href="{{url('/supplier-record')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Supplier Prices Upload</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/supplier-price-analysis')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Supplier Price Analysis</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($urlpage == "/supplier-price-analysis") { ?>
                                                <li class="">
                                                    <a href="{{url('/users-demands')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Product Demand Upload</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/supplier-record')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Supplier Prices Upload</span>
                                                    </a>
                                                </li>
                                                <li class="active">
                                                    <a href="{{url('/supplier-price-analysis')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Supplier Price Analysis</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($urlpage != "/users-demands" && $urlpage != "/supplier-record" && $urlpage != "/supplier-price-analysis") { ?>
                                                <li class="">
                                                    <a href="{{url('/users-demands')}}" class="waves-effect waves-dark ">
                                                        <span class="pcoded-mtext">Product Demand Upload</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/supplier-record')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Supplier Prices Upload</span>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="{{url('/supplier-price-analysis')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-mtext">Supplier Price Analysis</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                    @section('content')
                    @show
                </div>
            </div>
        </div>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{asset('admin_assets/js/jquery-ui.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/node-waves/0.7.6/waves.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="{{asset('admin_assets/js/pcoded.min.js')}}"></script>
<script src="{{asset('admin_assets/js/vertical-layout.min.js')}}"></script>
<script src="{{asset('admin_assets/js/script.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_assets/js/rocket-loader.min.js')}}" data-cf-settings="6933379c940a486f8f2e8112-|49" defer=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>
<script src="{{asset('admin_assets/js/table2excel.js')}}"></script>
<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });

    $(function () {
        $("select").select2();
    });
    
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
    
    $(document).ready(function () {
        if ($("ul.pcoded-submenu li").hasClass("active")) {
           $("li.active").parent(".pcoded-submenu").addClass("navbar_item_display");
        }
    });
</script>
    @section('footer')
    @show
</body>
</html>
