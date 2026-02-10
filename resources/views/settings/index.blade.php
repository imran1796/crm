@extends('layouts.app', ['activePage' => 'dashboard', 'title' => 'GLA Admin', 'navName' => 'Dashboard', 'activeButton' => 'laravel'])

@section('content')
    <div class="content-wrapper">

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h5>Appearance</h5>
                    </div>
                    <div class="card-body">

                        <!-- Section: Dark Mode -->
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="darkModeToggle">
                                    <label class="form-check-label" for="darkModeToggle">Dark Mode</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6>Header Options</h6>
                    </div>
                    <div class="card-body">
                        <!-- Header Options -->

                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="headerFixedToggle">
                            <label class="form-check-label" for="headerFixedToggle">Fixed Navbar</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="headerDropdownLegacyToggle">
                            <label class="form-check-label" for="headerDropdownLegacyToggle">Dropdown Legacy
                                Offset</label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="headerNoBorderToggle">
                            <label class="form-check-label" for="headerNoBorderToggle">No Border</label>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6>Sidebar Options</h6>
                    </div>
                    <div class="card-body">
                        <!-- Sidebar Options -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" id="sidebarCollapseToggle">
                                    <label class="form-check-label" for="sidebarCollapseToggle">Collapsed</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" id="sidebarFixedToggle">
                                    <label class="form-check-label" for="sidebarFixedToggle">Fixed</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" id="sidebarMiniToggle">
                                    <label class="form-check-label" for="sidebarMiniToggle">Mini</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" id="sidebarMiniMdToggle">
                                    <label class="form-check-label" for="sidebarMiniMdToggle">Mini MD</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" id="sidebarMiniXsToggle">
                                    <label class="form-check-label" for="sidebarMiniXsToggle">Mini XS</label>
                                </div>

                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" id="sidebarNavFlatToggle">
                                    <label class="form-check-label" for="sidebarNavFlatToggle">Nav Flat Style</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" id="sidebarNavLegacyToggle">
                                    <label class="form-check-label" for="sidebarNavLegacyToggle">Nav Legacy
                                        Style</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" id="sidebarNavCompactToggle">
                                    <label class="form-check-label" for="sidebarNavCompactToggle">Nav Compact</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" id="sidebarNavChildIndentToggle">
                                    <label class="form-check-label" for="sidebarNavChildIndentToggle">Nav Child
                                        Indent</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" id="sidebarNavChildHideToggle">
                                    <label class="form-check-label" for="sidebarNavChildHideToggle">Nav Child Hide on
                                        Collapse</label>
                                </div>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="sidebarNoExpandToggle">
                                    <label class="form-check-label" for="sidebarNoExpandToggle">Disable Hover/Focus
                                        Auto-Expand</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6>Footer Options</h6>
                    </div>
                    <div class="card-body">
                        <!-- Footer Options -->

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="footerFixedToggle">
                            <label class="form-check-label" for="footerFixedToggle">Fixed</label>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h6>Small Text Options</h6>
                    </div>
                    <div class="card-body">
                        <!-- Small Text Options -->

                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="textSmBodyToggle">
                            <label class="form-check-label" for="textSmBodyToggle">Body</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="textSmHeaderToggle">
                            <label class="form-check-label" for="textSmHeaderToggle">Navbar</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="textSmBrandToggle">
                            <label class="form-check-label" for="textSmBrandToggle">Brand</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="textSmSidebarToggle">
                            <label class="form-check-label" for="textSmSidebarToggle">Sidebar Nav</label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="textSmFooterToggle">
                            <label class="form-check-label" for="textSmFooterToggle">Footer</label>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection

@push('js')
    <script>
        $(function() {
            // Dark Mode
            $('#darkModeToggle').prop('checked', $('body').hasClass('dark-mode'))
                .on('change', function() {
                    $('body').toggleClass('dark-mode', this.checked)
                })

            // Header Options
            $('#headerFixedToggle').prop('checked', $('body').hasClass('layout-navbar-fixed'))
                .on('change', function() {
                    $('body').toggleClass('layout-navbar-fixed', this.checked)
                })
            $('#headerDropdownLegacyToggle').prop('checked', $('.main-header').hasClass('dropdown-legacy'))
                .on('change', function() {
                    $('.main-header').toggleClass('dropdown-legacy', this.checked)
                })
            $('#headerNoBorderToggle').prop('checked', $('.main-header').hasClass('border-bottom-0'))
                .on('change', function() {
                    $('.main-header').toggleClass('border-bottom-0', this.checked)
                })

            // Sidebar Options
            $('#sidebarCollapseToggle').prop('checked', $('body').hasClass('sidebar-collapse'))
                .on('change', function() {
                    $('body').toggleClass('sidebar-collapse', this.checked);
                    $(window).trigger('resize');
                })
            $('#sidebarFixedToggle').prop('checked', $('body').hasClass('layout-fixed'))
                .on('change', function() {
                    $('body').toggleClass('layout-fixed', this.checked);
                    $(window).trigger('resize');
                })
            $('#sidebarMiniToggle').prop('checked', $('body').hasClass('sidebar-mini'))
                .on('change', function() {
                    $('body').toggleClass('sidebar-mini', this.checked);
                })
            $('#sidebarMiniMdToggle').prop('checked', $('body').hasClass('sidebar-mini-md'))
                .on('change', function() {
                    $('body').toggleClass('sidebar-mini-md', this.checked);
                })
            $('#sidebarMiniXsToggle').prop('checked', $('body').hasClass('sidebar-mini-xs'))
                .on('change', function() {
                    $('body').toggleClass('sidebar-mini-xs', this.checked);
                })
            $('#sidebarNavFlatToggle').prop('checked', $('.nav-sidebar').hasClass('nav-flat'))
                .on('change', function() {
                    $('.nav-sidebar').toggleClass('nav-flat', this.checked);
                })
            $('#sidebarNavLegacyToggle').prop('checked', $('.nav-sidebar').hasClass('nav-legacy'))
                .on('change', function() {
                    $('.nav-sidebar').toggleClass('nav-legacy', this.checked);
                })
            $('#sidebarNavCompactToggle').prop('checked', $('.nav-sidebar').hasClass('nav-compact'))
                .on('change', function() {
                    $('.nav-sidebar').toggleClass('nav-compact', this.checked);
                })
            $('#sidebarNavChildIndentToggle').prop('checked', $('.nav-sidebar').hasClass('nav-child-indent'))
                .on('change', function() {
                    $('.nav-sidebar').toggleClass('nav-child-indent', this.checked);
                })
            $('#sidebarNavChildHideToggle').prop('checked', $('.nav-sidebar').hasClass('nav-collapse-hide-child'))
                .on('change', function() {
                    $('.nav-sidebar').toggleClass('nav-collapse-hide-child', this.checked);
                })
            $('#sidebarNoExpandToggle').prop('checked', $('.main-sidebar').hasClass('sidebar-no-expand'))
                .on('change', function() {
                    $('.main-sidebar').toggleClass('sidebar-no-expand', this.checked);
                })

            // Footer Options
            $('#footerFixedToggle').prop('checked', $('body').hasClass('layout-footer-fixed'))
                .on('change', function() {
                    $('body').toggleClass('layout-footer-fixed', this.checked)
                })

            // Small Text Options
            $('#textSmBodyToggle').prop('checked', $('body').hasClass('text-sm'))
                .on('change', function() {
                    $('body').toggleClass('text-sm', this.checked)
                })
            $('#textSmHeaderToggle').prop('checked', $('.main-header').hasClass('text-sm'))
                .on('change', function() {
                    $('.main-header').toggleClass('text-sm', this.checked)
                })
            $('#textSmBrandToggle').prop('checked', $('.brand-link').hasClass('text-sm'))
                .on('change', function() {
                    $('.brand-link').toggleClass('text-sm', this.checked)
                })
            $('#textSmSidebarToggle').prop('checked', $('.nav-sidebar').hasClass('text-sm'))
                .on('change', function() {
                    $('.nav-sidebar').toggleClass('text-sm', this.checked)
                })
            $('#textSmFooterToggle').prop('checked', $('.main-footer').hasClass('text-sm'))
                .on('change', function() {
                    $('.main-footer').toggleClass('text-sm', this.checked)
                })
        })
    </script>
@endpush
