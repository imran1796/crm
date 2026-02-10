@extends('layouts.app', ['activePage' => 'permission', 'title' => 'GLA Admin', 'navName' => 'Permission', 'activeButton' => 'laravel'])

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Create New Permission</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('permissions.index')}}">Permission</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form id="postForm" method="POST">
                            @method('post')
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <strong>Name:</strong>
                                        <input type="text" name="name" class="form-control form-control-sm"
                                            placeholder="Name">
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <br>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
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
            $('#postForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
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
                            window.location.href = "{{ route('designations.index') }}";
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
        });
    </script>
@endpush
