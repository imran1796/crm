@extends('layouts.app', ['activePage' => 'login', 'title' => 'GLA Admin'])

@section('content')
    <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center bg-light">
        <div class="login-box text-center" style="max-width: 400px; width: 100%;">

            <!-- Logo -->
            <div class="mb-4">
                <a href="{{ url('/') }}" class="text-primary font-weight-bold h2 text-decoration-none">Globelink</a>
            </div>

            <!-- Card -->
            <div class="card shadow-sm rounded-lg border-0">
                <div class="card-body p-4">

                    <p class="text-muted mb-4">Sign in to your account</p>

                    <form action="{{ route('login') }}" method="POST" novalidate>
                        @csrf
                    
                        <!-- Show general errors (like invalid credentials) -->
                        {{-- @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif --}}
                    
                        <!-- Email -->
                        <div class="form-group mb-3">
                            <div class="input-group ">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-envelope text-muted"></i></span>
                                </div>
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       class="form-control border-left-0 @error('email') is-invalid @enderror" 
                                       placeholder="Email" required autofocus>
                            </div>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    
                        <!-- Password -->
                        <div class="form-group mb-3">
                            <div class="input-group ">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-lock text-muted"></i></span>
                                </div>
                                <input type="password" 
                                       name="password" 
                                       class="form-control border-left-0 @error('password') is-invalid @enderror" 
                                       placeholder="Password" required>
                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    
                        <!-- Remember + Forgot -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label small" for="remember">Remember Me</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot Password?</a>
                        </div>
                    
                        <!-- Submit -->
                        <button type="submit" class="btn btn-primary btn-block  font-weight-bold">
                            Sign In
                        </button>
                    </form>
                    
                </div>
            </div>

            <!-- Footer -->
            <p class="mt-3 text-muted small">&copy; {{ date('Y') }} Globelink. All rights reserved.</p>
        </div>
    </div>
@endsection
