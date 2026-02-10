<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Google Font: Open Sans -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    {{--
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/ionicon/ionicon.min.css') }}"> --}}
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('adminlte3/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    {{-- sweet Alert --}}
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/toastr/toastr.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    {{--
    <link rel="stylesheet" href="{{ asset('adminlte3/dist/css/adminlte.min.css') }}"> --}}
    <link rel="stylesheet" href="{{asset('adminlte3/dist/css/adminlte.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/summernote/summernote-bs4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/jquery-ui/jquery-ui.theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/jquery-ui/jquery-ui.min.css') }}">
    {{--
    <link rel="stylesheet" href="{{ asset('adminlte3/dist/css/custom/jqueryDatepicker.css') }}"> --}}

    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/bootstrap-select/bootstrap-select.min.css') }}">

    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/select2/css/select2.min.css') }}">

    <link rel="stylesheet" href="{{ asset('adminlte3/dist/css/custom/custom.css') }}">
    {{-- custom.css
    select picker,
    search select,
    jquery datepicker
    --}}


    {{--
    <script src="{{ asset('adminlte3/plugins/jquery-ui/jquery-ui.theme.min.css') }}"></script> --}}


    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div id="app" class="wrapper">

        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('globelink/images/4.png') }}" alt="Loading..." height="60"
                width="80">
        </div> --}}
        <div class="preloader flex-column justify-content-center align-items-center">
            <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;">
                <span class="sr-only">Loading...</span>
            </div>
        </div>



        {{-- navbar --}}
        @include('layouts.navbar')

        {{-- sidebar --}}
        @if (auth()->check() && request()->route()->getName() != '')
            @include('layouts.sidebar')
        @endif

        {{-- <div></div> --}}
        {{-- Show Any Kind of Notice --}}
        @if (session('tableData.notice'))
            <marquee class="font-weight-bold  text-danger font-italic" width="100%" direction="left">
                <span style="font-size: 20px">
                    {{ session('tableData.notice') }}

                </span>
            </marquee>
        @endif

        {{-- main content --}}
        @yield('content')

        {{-- footer --}}
        @if (auth()->check() && request()->route()->getName() != '')
            @include('layouts.footer')
        @endif


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
        <!-- /.control-sidebar -->

    </div>

    <!-- jQuery | JqueryUI | jQuery Mapael-->
    <script src="{{ asset('adminlte3/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte3/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    {{--
    <script src="{{ asset('adminlte3/dist/js/custom/jqueryDatepicker.js') }}"></script> --}}

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

    <script>
        $("#autosearch").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $(".table tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    </script>

    <script src="{{ asset('adminlte3/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('adminlte3/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('adminlte3/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
    <script src="{{ asset('adminlte3/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte3/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    {{-- bootstrap-select --}}
    <script src="{{ asset('adminlte3/plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('adminlte3/plugins/bootstrap-select/bootstrap-select-defaults-US.min.js') }}"></script>

    {{-- seletc 2 --}}
    <script src="{{ asset('adminlte3/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminlte3/dist/js/custom/custom.js') }}"></script>

    <!-- ChartJS -->
    <script src="{{ asset('adminlte3/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('adminlte3/plugins/sparklines/sparkline.js') }}"></script>
    {{-- Toastr --}}
    <script src="{{ asset('adminlte3/plugins/toastr/toastr.min.js') }}"></script>

    <!-- JQVMap -->
    <script src="{{ asset('adminlte3/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('adminlte3/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('adminlte3/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('adminlte3/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminlte3/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script
        src="{{ asset('adminlte3/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('adminlte3/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('adminlte3/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App | DEMO(testing) | Dashboard -->
    <script src="{{ asset('adminlte3/dist/js/adminlte.js') }}"></script>
    {{--
    <script src="{{ asset('adminlte3/dist/js/demo.js') }}"></script> --}}
    <script src="{{ asset('adminlte3/dist/js/pages/dashboard.js') }}"></script>
    <script src="{{ asset('adminlte3/dist/js/pages/dashboard2.js') }}"></script>
    <script src="{{ asset('adminlte3/dist/js/pages/dashboard3.js') }}"></script>
    <script src="{{asset('adminlte3/dist/js/crudHandler.js')}}"></script>
    <script src="{{asset('adminlte3/dist/js/ajaxService.js')}}"></script>
    {{-- @vite('resources/js/crudHandler.js') --}}
    @stack('js')
</body>

</html>