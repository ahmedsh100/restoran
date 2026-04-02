@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="auth-card">
                    <div class="auth-header">
                        <i class="fa fa-sign-in-alt fa-3x mb-3"></i>
                        <h2 class="mb-1">Welcome Back</h2>
                        <p class="mb-0 opacity-75">Sign in to your account</p>
                    </div>
                    <div class="auth-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                                </div>
                                @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
                                </div>
                                @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Remember Me
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-3 btn-lg">
                                <i class="fa fa-sign-in-alt me-2"></i>Sign In
                            </button>
                        </form>
                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-semibold">Register here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
