@extends('layouts.app', ['activePage' => 'user', 'title' => 'GLA Admin', 'navName' => 'User Permission', 'activeButton' => 'laravel'])

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">User Permission Management</h1>
                    </div>
                    {{-- <div class="col-sm-6 d-flex justify-content-end">
                        @can('user-create')
                            <a class="btn btn-success" href="{{ route('') }}"> Create New User</a>
                        @endcan
                    </div><!-- /.col --> --}}
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card"><div class="card-body">


                    
                    @php
                        $rolePermissionNames = $user->roles->flatMap->permissions->pluck('name')->unique();
                    @endphp
                    <form id="userPermissionUpdateForm" class="mb-2">
                        @csrf
                        <div class="card-columns">
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            @foreach ($permissions as $module => $modulePermissions)
                                @php
                                    // Filter only permissions not granted via roles
                                    $customPermissions = $modulePermissions->filter(function ($permission) use (
                                        $rolePermissionNames,
                                    ) {
                                        return !$rolePermissionNames->contains($permission->name);
                                    });
                                @endphp
                                @if ($customPermissions->count())
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ ucfirst($module) }}</h5>
                                        </div>
                                        <hr class="p-0 m-0">
                                        <div class="card-body">
                                            @foreach ($customPermissions as $permission)
                                            <label style="text-transform: none;">
                                                <input 
                                                    type="checkbox" 
                                                    name="permission[]" 
                                                    {{-- value="{{ $permission->id }}"  --}}
                                                    value="{{ $permission->name }}" 
                                                    {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}
                                                >
                                                {{ $permission->name }}
                                            </label>
                                            <br />
                                        @endforeach
                                        
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="my-2 text-center">
                            <button type="submit" class="btn btn-primary">Update Permissions</button>
                        </div>

                    </form>
                    {{-- </div> --}}

                    @foreach ($user->roles as $role)
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header p-2">
                                <h5>Role: {{ $role->name }}</h5>
                            </div>
                            <div class="card-body">
                                @foreach ($role->permissions->groupBy('department') as $dept => $permissions)
                                    <div class="mb-3">
                                        <h6 class="text-primary border-bottom pb-1">{{ ucfirst($dept) }}</h6>
                                        <div class="row">
                                            @foreach ($permissions as $permission)
                                                <div class="col-md-3 mb-2">
                                                    <span class="badge bg-secondary">{{ $permission->name }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach


                </div></div>
                
            </div>
        </section>

    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#userPermissionUpdateForm').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('users.permission.update') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    success: function(response) {
                        toastr.success(response.success);
                        setTimeout(() => {
                            window.location.href = "{{ route('users.index') }}";
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
        });
    </script>
@endpush
