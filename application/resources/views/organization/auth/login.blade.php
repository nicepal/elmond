@extends('organization.layouts.master')
@section('content')
<div class="login_area">
    <div class="login">
        <div class="login__header">
            <h2>{{ __($pageTitle) }}</h2>
            <p>@lang('Organization Admin Panel')</p>
        </div>
        <div class="login__body">
            <form action="{{ route('organization.login') }}" method="POST">
                @csrf
                <div class="field">
                    <input type="email" name="email" placeholder="@lang('Email Address')" value="{{ old('email') }}" required>
                    <span class="show-pass"><i class="fas fa-envelope"></i></span>
                </div>
                <div class="field">
                    <input type="password" name="password" id="organization-password" placeholder="@lang('Password')" required>
                    <span class="show-pass password-toggle" style="cursor: pointer;"><i class="fas fa-eye-slash"></i></span>
                </div>
                <div class="login__footer">
                    <div class="field_remember">
                        <div class="remember_wrapper">
                            <input type="checkbox" name="remember" id="remember">
                            <label class="remember" for="remember">@lang('Remember Me')</label>
                        </div>
                    </div>
                </div>
                <x-captcha></x-captcha>
                <div class="field">
                    <button type="submit" class="sign-in">@lang('Sign In')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('assets/admin/css/auth.css')}}">
<style>
    .ball {
        position: absolute;
        border-radius: 100%;
        opacity: 0.7;
    }
</style>
@endpush

@push('script')
<script>
    $(document).ready(function() {
        $('.password-toggle').on('click', function() {
            var passwordField = $('#organization-password');
            var icon = $(this).find('i');
            
            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                passwordField.attr('type', 'password');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });
    });
</script>
@endpush