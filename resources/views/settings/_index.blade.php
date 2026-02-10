@extends('layouts.app', ['activePage' => 'dashboard', 'title' => 'GLA Admin', 'navName' => 'Dashboard', 'activeButton' => 'laravel'])

@section('content')
@php
$sidebarColors = [
    'primary', 'warning', 'info', 'danger', 'success', 
    'indigo', 'lightblue', 'navy', 'purple', 'fuchsia', 
    'pink', 'maroon', 'orange', 'lime', 'teal', 'olive'
];
@endphp
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard v1</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h5 class="mb-0">Sidebar Variants</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Light Sidebar -->
                            <div class="col-md-6">
                                <h6>Light Sidebar</h6>
                                <select id="sidebar-light-select" class="custom-select mb-3">
                                    <option value="">None Selected</option>
                                    @foreach($sidebarColors as $color)
                                        <option value="sidebar-light-{{ $color }}">{{ ucfirst($color) }}</option>
                                    @endforeach
                                </select>
                            </div>
                
                            <!-- Dark Sidebar -->
                            <div class="col-md-6">
                                <h6>Dark Sidebar</h6>
                                <select id="sidebar-dark-select" class="custom-select mb-3">
                                    <option value="">None Selected</option>
                                    @foreach($sidebarColors as $color)
                                        <option value="sidebar-dark-{{ $color }}">{{ ucfirst($color) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div><!-- /.container-fluid -->
        </section>
    </div>

    @push('scripts')
<script>
$(document).ready(function() {
    var sidebarSkins = @json($sidebarColors);

    $('#sidebar-light-select').on('change', function() {
        var selected = $(this).val();
        var $sidebar = $('.main-sidebar');

        // Remove all sidebar-light-* classes
        sidebarSkins.forEach(function(color) {
            $sidebar.removeClass('sidebar-light-' + color);
        });

        // Add selected class if any
        if(selected) {
            $sidebar.addClass(selected);

            // Optional: remove dark sidebar class if present
            sidebarSkins.forEach(function(color){
                $sidebar.removeClass('sidebar-dark-' + color);
            });

            // Adjust scrollbar theme
            $('.sidebar').removeClass('os-theme-dark').addClass('os-theme-light');
        }
    });

    $('#sidebar-dark-select').on('change', function() {
        var selected = $(this).val();
        var $sidebar = $('.main-sidebar');

        // Remove all sidebar-dark-* classes
        sidebarSkins.forEach(function(color) {
            $sidebar.removeClass('sidebar-dark-' + color);
        });

        // Add selected class if any
        if(selected) {
            $sidebar.addClass(selected);

            // Optional: remove light sidebar class if present
            sidebarSkins.forEach(function(color){
                $sidebar.removeClass('sidebar-light-' + color);
            });

            // Adjust scrollbar theme
            $('.sidebar').removeClass('os-theme-light').addClass('os-theme-dark');
        }
    });
});
</script>
@endpush
@endsection
