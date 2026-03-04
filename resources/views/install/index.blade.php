<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Installer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="col-md-8 mx-auto card p-4 shadow-sm">
        <h2 class="mb-3"> Laravel Installer</h2>

        {{-- Success message --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error messages --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('install.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label">Application Name</label>
                <input type="text" name="app_name" class="form-control" value="{{ old('app_name','My CRM') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Application URL</label>
                <input type="url" name="app_url" class="form-control"
                       value="{{ old('app_url', url('/')) }}" required>
            </div>

            <h5 class="mt-4">Database Configuration</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">DB Host</label>
                    <input type="text" name="db_host" class="form-control"
                           value="{{ old('db_host','127.0.0.1') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">DB Port</label>
                    <input type="text" name="db_port" class="form-control"
                           value="{{ old('db_port','3306') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Database Name</label>
                <input type="text" name="db_name" class="form-control"
                       placeholder="crm_database" value="{{ old('db_name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Database Username</label>
                <input type="text" name="db_user" class="form-control"
                       value="{{ old('db_user','root') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Database Password</label>
                <input type="password" name="db_pass" class="form-control" value="{{ old('db_pass') }}">
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Install Application</button>
        </form>
    </div>
</div>
</body>
</html>
