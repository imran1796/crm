@extends('layouts.app', [
    'activePage' => 'permission',
    'title' => 'GLA Admin',
    'navName' => 'Permission',
    'activeButton' => 'laravel',
])

@section('content')
    @php
        $permissions = collect([
            (object) ['id' => 1, 'name' => 'Create User', 'department' => 'hr', 'guard_name' => 'web'],
            (object) ['id' => 2, 'name' => 'Edit User', 'department' => 'hr', 'guard_name' => 'web'],
            (object) ['id' => 3, 'name' => 'Delete User', 'department' => 'hr', 'guard_name' => 'web'],

            (object) ['id' => 4, 'name' => 'Create Report', 'department' => 'finance', 'guard_name' => 'web'],
            (object) ['id' => 5, 'name' => 'View Report', 'department' => 'finance', 'guard_name' => 'web'],
            (object) ['id' => 6, 'name' => 'Export Report', 'department' => 'finance', 'guard_name' => 'web'],

            (object) ['id' => 7, 'name' => 'Add Product', 'department' => 'inventory', 'guard_name' => 'web'],
            (object) ['id' => 8, 'name' => 'Update Product', 'department' => 'inventory', 'guard_name' => 'web'],
            (object) ['id' => 9, 'name' => 'Delete Product', 'department' => 'inventory', 'guard_name' => 'web'],

            (object) ['id' => 10, 'name' => 'System Backup', 'department' => null, 'guard_name' => 'web'],
        ]);
    @endphp


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
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
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

        });
    </script>
@endpush
