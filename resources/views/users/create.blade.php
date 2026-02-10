@extends('layouts.app', ['activePage' => 'user', 'title' => 'GLA Admin', 'navName' => 'User', 'activeButton' => 'laravel'])

@section('content')
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Create User</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">

                <div class="card card-outline card-lightblue">
                    <div class="card-body px-4">

                        <form id="createUserForm">
                            @csrf

                            <div class="row">
                                <p class="col-sm-2 "><strong>Name:</strong></p>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control form-control-sm">

                                </div>
                            </div>

                            <div class="row">
                                <p class="col-sm-2 "><strong>Email:</strong></p>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control form-control-sm">

                                </div>
                            </div>

                            <div class="row">
                                <p class="col-sm-2 "><strong>Password:</strong></p>
                                <div class="col-sm-10">
                                    <input type="password" name="password" class="form-control form-control-sm">

                                </div>
                            </div>

                            <div class="row">
                                <p class="col-sm-2 "><strong>Confirm Password:</strong></p>
                                <div class="col-sm-10">
                                    <input type="password" name="confirm-password" class="form-control form-control-sm">
                                </div>
                            </div>

                            {{-- multiselect-search="true"
                            multiselect-max-items="3"
                            multiselect-select-all="true"
                            multiselect-hide-x="true" --}}
                            <div class="row mb-2">
                                <p class="col-sm-2 "><strong>Role:</strong></p>
                                <div class="col-sm-10">
                                    <select name="roles[]" class="form-control form-control-sm selectpicker" multiple>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}">{{ $role }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>


                            <div class="text-end pull-right">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#createUserForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('users.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        toastr.success(response.success);
                        setTimeout(() => {
                            window.location.href = "{{ route('users.index') }}";
                        }, 2000);
                    },
                    error: function(response) {
                        if (response.responseJSON.error) {
                            toastr.error(response.responseJSON.error);
                        }
                        for (let field in response.responseJSON.errors) {
                            for (let i = 0; i < response.responseJSON.errors[field].length; i++) {
                                toastr.error(response.responseJSON.errors[field][i]);
                            }
                        }
                    }
                });
            });
        });
    </script>
@endpush
