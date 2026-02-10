@extends('layouts.app', ['activePage' => 'role', 'title' => 'GLA Admin', 'navName' => 'Role', 'activeButton' => 'laravel'])

@section('content')
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Create New Role</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Role</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">

                <form id="roleCreateForm" class="card card-outline card-lightblue" method="POST">
                    @csrf
                    <div class="row card-body">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group row">
                                <div class="col-md-1"><strong>Name:</strong></div>
                                <div class="col-md-11"><input type="text" name="name"
                                        class="form-control form-control-sm" placeholder="Name"></div>


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
                                                            id="perm-{{ $permission->id }}" value="{{ $permission->name }}">
                                                        <label class="form-check-label text-truncate"
                                                            for="perm-{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
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
            $('#roleCreateForm').on('submit', function(e) {
                e.preventDefault();

                var url = '{{ route('roles.store') }}';
                var formData = new FormData(this);
                console.log(formData);

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
