@extends('layouts.app', ['activePage' => 'user', 'title' => 'GLA Admin', 'navName' => 'User', 'activeButton' => 'laravel'])
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Users</h1>
                    </div>
                    <div class="col-sm-6 d-flex justify-content-end">
                        @can('user-create')
                            <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User</a>
                        @endcan
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-sm  table-hover table-style-1">
                            <thead class="bg-gradient-lightblue">
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($data as $dept_id => $users)
                                    <tr>
                                        <td colspan="5">
                                            <p class="m-0 text-center">
                                                <strong>{{ $users->first()->department->department_name ?? 'Unavailable' }}</strong>
                                            </p class="m-0">
                                        </td>
                                    </tr>
                                    @foreach ($users as $user)
                                        @if (!$user->hasRole('System Admin') || \Illuminate\Support\Facades\Auth::user()->hasRole('System Admin'))
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if (!empty($user->getRoleNames()))
                                                        @foreach ($user->getRoleNames() as $v)
                                                            <h6 class="badge badge-success text-white">{{ $v }}
                                                            </h6>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    <a class="btn btn-sm btn-primary"
                                                        href="{{ route('users.edit', $user->id) }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    {{-- <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form> --}}


                                                    @if ($user->email_verified_at == null)
                                                        <button type="button" data-id="{{ $user->id }}"
                                                            class="btn btn-sm btn-danger verifyBtn">
                                                            <i class="fa fa-ban"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" data-id="{{ $user->id }}"
                                                            class="btn btn-sm btn-success verifyBtn">
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                    @endif
                                                    @can('user-permission')
                                                        <a href="{{ route('users.permission', $user->id) }}"
                                                            class="btn btn-sm btn-warning">
                                                            <i class="fa-solid fa-key"></i>
                                                        </a>
                                                    @endcan
                                                </td>


                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tbody>


                        </table>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.verifyBtn').on('click', function() {
                var userId = $(this).data('id');
                var url = '{{ route('users.verify', ':id') }}';
                url = url.replace(':id', userId);
                $.ajax({
                    type: 'POST',
                    url: url,
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
