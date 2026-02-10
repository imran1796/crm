@extends('layouts.app', ['activePage' => 'user', 'title' => 'GLA Admin', 'navName' => 'User', 'activeButton' => 'laravel'])

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit User</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-outline card-lightblue">
                    <div class="card-body px-4">
                        <form id="editUserForm">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                            <div class="row">
                                <p class="col-sm-2"><strong>Name:</strong></p>
                                <div class="col-sm-10">
                                    <input type="text" name="name" value="{{ $user->name }}"
                                        class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="row">
                                <p class="col-sm-2"><strong>Email:</strong></p>
                                <div class="col-sm-10">
                                    <input type="email" name="email" value="{{ $user->email }}"
                                        class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="row">
                                <p class="col-sm-2"><strong>Password:</strong></p>
                                <div class="col-sm-10">
                                    <input type="password" name="password" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="row">
                                <p class="col-sm-2"><strong>Confirm Password:</strong></p>
                                <div class="col-sm-10">
                                    <input type="password" name="confirm-password" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <p class="col-sm-2"><strong>Role:</strong></p>
                                <div class="col-sm-10">
                                    <select name="roles[]" class="form-control form-control-sm selectpicker" multiple>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}"
                                                {{ in_array($role, $userRoles) ? 'selected' : '' }}>
                                                {{ $role }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <p class="col-sm-2"><strong>Department:</strong></p>
                                <div class="col-sm-10">
                                    <select name ="department_id" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                        @foreach ($departments as $department)
                                            <option @if ($user->department_id == $department->id) selected @endif
                                                value="{{ $department->id }}">
                                                {{ $department->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <p class="col-sm-2"><strong>Designation:</strong></p>
                                <div class="col-sm-10">
                                    <select name ="designation_id" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                        @foreach ($designations as $designation)
                                            <option @if ($user->designation_id == $designation->id) selected @endif
                                                value="{{ $designation->id }}">
                                                {{ $designation->designation }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <p class="col-sm-2"><strong>Branch:</strong></p>
                                <div class="col-sm-10">
                                    <select name ="branch_id" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                        @foreach ($branches as $branch)
                                            <option @if ($user->branch_id == $branch->id) selected @endif
                                                value="{{ $branch->id }}" >
                                                {{ $branch->short_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            

                            <div class="text-end pull-right">
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
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
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let userId = $('input[name="user_id"]').val();

                $.ajax({
                    url: "{{ url('users') }}/" + userId,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                        'X-HTTP-Method-Override': 'PUT' // Since Laravel requires PUT
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
