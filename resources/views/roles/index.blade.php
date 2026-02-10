@extends('layouts.app', ['activePage' => 'role', 'title' => 'GLA Admin', 'navName' => 'Role', 'activeButton' => 'laravel'])

@section('content')
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Role Management</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 d-flex justify-content-end">
                        @can('role-create')
                            <a class="btn btn-success" href="{{ route('roles.create') }}"> Create New Role</a>
                        @endcan
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>


        <section class="content">
            <div class="container-fluid">

                <div class="card card-outline card-lightblue">

                    <div class="card-body table-responsive p-0">
                        <table class="table table-sm  table-hover table-style-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            {{-- <a class="btn btn-sm btn-info" href="{{ route('roles.show', $role->id) }}">Show</a> --}}
                                            @can('role-edit')
                                                <a class="btn btn-sm btn-primary" href="{{ route('roles.edit', $role->id) }}"><i
                                                        class='fas fa-edit'></i></a>
                                            @endcan
                                            {{-- @can('role-delete')
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        @endcan --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
                {!! $roles->render() !!}
            </div>
        </section>

    </div>
@endsection
