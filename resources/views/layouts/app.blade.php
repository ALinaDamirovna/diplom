<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<?php $mainDIR = '/';?>
<head>

    <meta charset="utf-8" />
    <title>@yield('title')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link sizes="192x192" href="{{ asset('assets/images/192.jpg') }}" rel="icon" type="image/jpeg">

    <!-- jvectormap -->
    <link href="{{ asset('assets/libs/jqvmap/jqvmap.min.css') }}" rel="stylesheet" />


    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->

</head>

<style>
    nav svg {
        width: 23px;
    }
    nav > div:first-child {
        display: none;
    }
    nav > div:nth-child(2) > div:first-child {
        display: none;
    }
</style>
<body data-sidebar="dark">

<!-- <body data-layout="horizontal" data-topbar="dark"> -->

<!-- Begin page -->
<div id="layout-wrapper">


    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box text-center">
                    <a href="{{ $mainDIR }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo-sm-dark" height="22">
                                </span>
                        <span class="logo-lg">
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo-dark" height="24">
                                </span>
                    </a>

                    <a href="{{ $mainDIR }}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo-sm-light" height="22">
                                </span>
                        <span class="logo-lg">
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo-light" height="24">
                                </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                    <i class="ri-menu-2-line align-middle"></i>
                </button>
            </div>

            <div class="d-flex">

                <div class="dropdown d-none d-lg-inline-block ms-1">
                    <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                        <i class="ri-fullscreen-line"></i>
                    </button>
                </div>

                <div class="dropdown d-inline-block user-dropdown">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif
                    @else
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/logo.png') }}"
                                 alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-1">{{ Auth::user()->name }}</span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <!--
                            <a class="dropdown-item" href="#"><i class="ri-user-line align-middle me-1"></i> Profile</a>
                            <a class="dropdown-item" href="#"><i class="ri-wallet-2-line align-middle me-1"></i> My Wallet</a>
                            <a class="dropdown-item d-block" href="#"><span class="badge bg-success float-end mt-1">11</span><i class="ri-settings-2-line align-middle me-1"></i> Settings</a>
                            <a class="dropdown-item" href="#"><i class="ri-lock-unlock-line align-middle me-1"></i> Lock screen</a>
                            <div class="dropdown-divider"></div>
                        -->
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="ri-shut-down-line align-middle me-1 text-danger"></i> {{ __('Logout') }}</a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    @endguest
                </div>

            </div>
        </div>
    </header>

    <!-- ========== Left Sidebar Start ========== -->
    <div class="vertical-menu">

        <div data-simplebar class="h-100">

            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="metismenu list-unstyled" id="side-menu">

                    <li>
                        <a href="{{ $mainDIR }}" class="waves-effect">
                            <i class="mdi mdi-home-variant-outline"></i>
                            <span>Главная</span>
                        </a>
                    </li>

                    @can('change-orders')
                    <li>
                        <a href="{{ $mainDIR }}orders/" class="waves-effect">
                            <i class="mdi mdi-home-variant-outline"></i>
                            <span>Заказы</span>
                        </a>
                    </li>
                    @endcan


                    @can('crud-products')
                    <li>
                        <a href="{{ $mainDIR }}promocodes/" class="waves-effect">
                            <i class="mdi mdi-home-variant-outline"></i>
                            <span>Промокоды</span>
                        </a>
                    </li>

                    <li class="menu-title">Информация</li>

                    <li>
                        <a href="{{ $mainDIR }}products/" class=" waves-effect">
                            <i class="ri-shopping-basket-2-line"></i>
                            <span>Товары</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ $mainDIR }}options/" class=" waves-effect">
                            <i class="ri-shopping-basket-2-line"></i>
                            <span>Опции</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $mainDIR }}additions/" class=" waves-effect">
                            <i class="ri-shopping-basket-2-line"></i>
                            <span>Добавки</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $mainDIR }}product_cats/" class=" waves-effect">
                            <i class="ri-shopping-basket-2-line"></i>
                            <span>Категории</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ $mainDIR }}option_cats/" class=" waves-effect">
                            <i class="ri-shopping-basket-2-line"></i>
                            <span>Категории опций</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ $mainDIR }}settings/" class=" waves-effect">
                            <i class="ri-shopping-basket-2-line"></i>
                            <span>Настройки</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
            <!-- Sidebar -->
        </div>
    </div>
    <!-- Left Sidebar End -->



    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">






                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">@yield('title')</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">PL</a></li>
                                    <li class="breadcrumb-item active">@yield('title')</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->
                @yield('content')

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- JAVASCRIPT -->
<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

<script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

@yield('addjs')

<script src="{{ asset('assets/js/app.js') }}"></script>

</body>
</html>

