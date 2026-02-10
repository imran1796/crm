@extends('layouts.app', [
    'activePage' => 'permission',
    'title' => 'GLA Admin',
    'navName' => 'Permission',
    'activeButton' => 'laravel',
])

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Permissions</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 d-flex justify-content-end">
                        @can('permission-create')
                            {{-- <a class="btn btn-success" href="{{ route('permissions.create') }}">Create New Permission</a> --}}
                            <button class="btn btn-success" data-toggle="modal" data-target="#createPermissionModal">
                                Create New Permission
                            </button>
                        @endcan
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-outline card-lightblue">
                    <div class="card-body table-responsive p-0">
                        <form action="" method="POST">
                            @csrf
                            <table class="table table-sm  table-hover table-style-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Dept</th>
                                        <th>Guard</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions->groupBy('department') as $dept => $data)
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <strong>{{ empty($dept) ? 'Unavailable' : ucfirst($dept) }}</strong>
                                            </td>
                                        </tr>
                                        @foreach ($data as $permission)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $permission->name }}</td>
                                                <td>{{ $permission->department }}</td>
                                                <td>{{ $permission->guard_name }}</td>

                                                <td>
                                                    @can('permission-delete')
                                                        <button type="button" class="btn btn-sm btn-danger delete-permission"
                                                            data-id="{{ $permission->id }}">
                                                            <i class='fas fa-trash'></i>
                                                        </button>
                                                    @endcan
                                                    {{-- @can('permission-edit')
                                                <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="{{ $permission->id }}"
                                                    data-name ="{{ $permission->name }}"
                                                    data-dept ="{{ $permission->department }}">
                                                    <i class="fas fa-pen-square"></i>
                                                </button>
                                                @endcan --}}
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>

                @component('components.modal', [
                    'id' => 'createPermissionModal',
                    'title' => 'Create New Permission',
                    'size' => '',
                    'submitButton' => 'savePermissionButton',
                ])
                    <div class="form-group">
                        <label for="name"><strong>Name:</strong></label>
                        <input type="text" name="name" id="name" class="form-control form-control-sm"
                            placeholder="Permission Name">
                    </div>
                    <div class="form-group">
                        <label for="name"><strong>Department:</strong></label>
                        <select name ="department" class="form-control form-control-sm">
                            <option value="">Select</option>
                            @foreach (['general', 'import', 'export', 'do', 'equipment', 'accounts', 'sales'] as $departmentOption)
                                <option value="{{ $departmentOption }}">
                                    {{ $departmentOption }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <label for="name"><strong>Department:</strong></label>
                    @foreach (['general', 'import', 'export', 'do', 'equipment', 'accounts', 'sales'] as $departmentOption)
                            <input type="radio" name="departments[{{ $permission->id }}]" value="{{ $departmentOption }}">
                            {{ $departmentOption }}
                    @endforeach --}}
                @endcomponent

                @component('components.modal', [
                    'id' => 'editPermissionModal',
                    'title' => 'Edit Permission',
                    'size' => '',
                    'submitButton' => 'updatePermissionButton',
                    'method' => 'PUT',
                ])
                    <input type="hidden" name="id" id="edit_permission_id">

                    <div class="form-group">
                        <label for="name"><strong>Name:</strong></label>
                        <input type="text" name="name" id="edit_permission_name" class="form-control form-control-sm"
                            placeholder="Permission Name">
                    </div>
                    <div class="form-group">
                        <label for="name"><strong>Department:</strong></label>
                        <select name ="department" id="edit_permission_department" class="form-control form-control-sm">
                            <option value="">Select</option>
                            @foreach (['general', 'import', 'export', 'do', 'equipment', 'accounts', 'sales'] as $departmentOption)
                                <option value="{{ $departmentOption }}">
                                    {{ $departmentOption }}</option>
                            @endforeach
                        </select>
                    </div>
                @endcomponent
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#createPermissionModalForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                console.log(formData);

                $.ajax({
                    type: 'POST',
                    url: '{{ route('permissions.store') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    enctype: 'multipart/form-data',
                    success: function(response) {
                        toastr.success(response.success);
                        setTimeout(() => {
                            window.location.href = "{{ route('permissions.index') }}";
                        }, 2000);
                    },
                    error: function(response) {
                        if (response.responseJSON.error) {
                            toastr.error(response.responseJSON.error);
                        }
                        for (let field in response.responseJSON.errors) {
                            for (let i = 0; i < response.responseJSON.errors[field]
                                .length; i++) {
                                toastr.error(response.responseJSON.errors[field][i]);
                            }
                        }
                    }
                });
            });

            $('.edit-btn').on('click', function() {
                $('#edit_permission_id').val($(this).data('id'));
                $('#edit_permission_name').val($(this).data('name'));
                $('#edit_permission_department')
                    .val($(this).data('dept'))
                    .trigger('change');
                $('#editPermissionModal').modal('show');
            });

            $('#editPermissionModalForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                const permissionId = $('#edit_permission_id').val();
                var url = '{{ route('permissions.update', ':id') }}';
                url = url.replace(':id', permissionId);


                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    enctype: 'multipart/form-data',
                    success: function(response) {
                        toastr.success(response.success);
                        setTimeout(() => {
                            window.location.href = "{{ route('permissions.index') }}";
                        }, 2000);
                    },
                    error: function(response) {
                        if (response.responseJSON.error) {
                            toastr.error(response.responseJSON.error);
                        }
                        for (let field in response.responseJSON.errors) {
                            for (let i = 0; i < response.responseJSON.errors[field]
                                .length; i++) {
                                toastr.error(response.responseJSON.errors[field][i]);
                            }
                        }
                    }
                });

            });

            // $(document).on('click', '.delete-permission', function() {
            //     let permissionId = $(this).data('id');

            //     if (!confirm('Are you sure?')) {
            //         return;
            //     }

            //     $.ajax({
            //         url: ,
            //         type: "POST",
            //         data: {
            //             _method: "DELETE",
            //             _token: "{{ csrf_token() }}"
            //         },
            //         success: function(response) {
            //             demo.customShowNotification('success', response.success);
            //             window.location.reload();
            //         },
            //         error: function(xhr) {
            //             let errorMessage = xhr.responseJSON.error || "Something went wrong!";
            //             demo.customShowNotification('danger', errorMessage);
            //         }
            //     });
            // });
        });
    </script>
@endpush
