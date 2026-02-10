@extends('layouts.app', [
    'activePage' => 'user',
    'title' => 'GLA Admin',
    'navName' => 'User',
    'activeButton' => 'laravel',
])

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Profile</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">User Profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-3">
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="{{ asset('adminlte3/dist/img/user.png') }}" alt="User profile picture">
                                <h3 class="profile-username">{{ auth()->user()->name }}</h3>
                                <p class="text-muted">Department</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#information" data-toggle="tab">
                                            {{ __('Information') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#password" data-toggle="tab">
                                            {{ __('Password') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">

                                    {{-- TAB 1: USER INFORMATION --}}
                                    <div class="tab-pane active" id="information">
                                        <form id="profileForm" method="post" enctype="multipart/form-data">
                                            @csrf
                                            @method('patch')

                                            <div class="form-group row">
                                                <label for="input-name" class="col-sm-2 col-form-label">
                                                    <i class="fa fa-user"></i> {{ __('Name') }}
                                                </label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="name" id="input-name"
                                                        class="form-control form-control-sm"
                                                        value="{{ auth()->user()->name }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="input-email" class="col-sm-2 col-form-label">
                                                    <i class="fas fa-envelope-open"></i> {{ __('Email') }}
                                                </label>
                                                <div class="col-sm-10">
                                                    <input type="email" name="email" id="input-email"
                                                        class="form-control form-control-sm"
                                                        value="{{ auth()->user()->email }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-12 text-right">
                                                    <button type="submit" class="btn btn-success">
                                                        {{ __('Save') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- TAB 2: PASSWORD --}}
                                    <div class="tab-pane" id="password">
                                        <form id="passwordForm" method="post">
                                            @csrf
                                            @method('patch')

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">
                                                    {{ __('Current Password') }}
                                                </label>
                                                <div class="col-sm-8">
                                                    <input type="password" name="old_password"
                                                        class="form-control form-control-sm" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">
                                                    {{ __('New Password') }}
                                                </label>
                                                <div class="col-sm-8">
                                                    <input type="password" name="password"
                                                        class="form-control form-control-sm" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">
                                                    {{ __('Confirm Password') }}
                                                </label>
                                                <div class="col-sm-8">
                                                    <input type="password" name="password_confirmation"
                                                        class="form-control form-control-sm" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-12 text-right">
                                                    <button type="submit" class="btn btn-success">
                                                        {{ __('Change password') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div> <!-- /.tab-content -->
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Profile Form AJAX
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('profile.update') }}",
                    method: 'POST',
                    data: $(this).serialize(),

                    success: function(response) {
                        toastr.success('Profile updated successfully');
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors;
                        if (errors) {
                            Object.values(errors).forEach(msg => toastr.error(msg[0]));
                        } else {
                            toastr.error('Something went wrong');
                        }
                    }
                });
            });

            // Password Form AJAX
            $('#passwordForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('profile.password') }}",
                    method: 'POST',
                    data: $(this).serialize(),

                    success: function(response) {
                        toastr.success('Password changed successfully');
                        $('#passwordForm')[0].reset();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors;
                        if (errors) {
                            Object.values(errors).forEach(msg => toastr.error(msg[0]));
                        } else {
                            toastr.error('Something went wrong');
                        }
                    }
                });
            });
        });
    </script>
@endpush
