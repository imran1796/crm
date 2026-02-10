@extends('layouts.app', ['activePage' => 'role', 'title' => 'GLA Admin', 'navName' => 'Role', 'activeButton' => 'laravel'])

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Role</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Role</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                <form id="roleUpdateForm" class="card card-outline card-lightblue" method="POST">
                    @csrf
                    @method('PATCH')

                    {{-- <div class="card-header bg-lightblue">Edit Role</div> --}}
                    <div class="row card-body">
                        <input id="role_id" type="hidden" name="id" value="{{$role->id}}">
                        <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                            <div class="form-group row">
                                <div class="col-sm-1"><strong>Role:</strong></div>
                                <div class="col-sm-11"><input type="text" name="name"
                                        value="{{ old('name', $role->name) }}" class="form-control form-control-sm"
                                        placeholder="Name"></div>

                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Permission:</strong>
                                <br />

                                <div class="card-columns">
                                    @foreach ($groupedPermission as $key => $permissions)
                                        <div class="card my-2">
                                            <div class="card-header">
                                                <h6 class="mb-0 text-capitalize font-weight-bold">{{ $key }}</h6>
                                            </div>
                                            <hr class="p-0 m-0">
                                            <div class="card-body py-2">
                                                @foreach ($permissions as $permission)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" name="permission[]"
                                                            id="perm-{{ $permission->id }}"
                                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                                            value="{{ $permission->name }}">
                                                        <label class="form-check-label text-truncate"
                                                            for="perm-{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>

                                                    {{-- <label style="text-transform: none;">
                                                        <input type="checkbox" name="permission[]"
                                                            value="{{ $permission->name }}"
                                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                                            class="name">
                                                        {{ $permission->name }}
                                                    </label>
                                                    <br /> --}}
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>

                    </div>
                </form>
            </div>
        </section>


    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#roleUpdateForm').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                const roleId = $('#role_id').val();
                var url = '{{ route('roles.update', ':role') }}';
                url = url.replace(':role', roleId);

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
                            window.location.href =
                                "{{ route('roles.index') }}";
                        }, 1000);
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
        })
    </script>
@endpush
