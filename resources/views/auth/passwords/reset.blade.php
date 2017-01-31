<?php
    $errors = isset($errors) ? $errors : [];
    $email = isset($email) ? $email : "";
?>
@extends('layouts.layout')

@section('header')
    @include('layouts.navbar')
@endsection

@section('content')
    <div class="container" id="dm-login-page">

        <div class="{{ array_key_exists('token', $errors) ? 'dm-error-panel' : 'panel panel-default' }}">
            <div class="panel-body">
                @if(array_key_exists('token', $errors))
                    Expired link.
                @else
                <form class="form" role="form" method="POST" action="{{ url('/password/reset') }}">
                    @if(isset($password_reset) && $password_reset)
                        Your password has been successfully reset.
                    @else
                    <input type="hidden" value="{{ $token  }}" name="token">
                    <div class="form-group{{ array_key_exists('email', $errors) ? ' has-error' : '' }}">
                        Email: <input type="email" class="form-control" name="email" value="{{ $email }}" required autofocus>
                        @if (array_key_exists('email', $errors))
                            <span class="help-block">
                                <strong>{{ $errors['email'][0] }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ array_key_exists('password', $errors) ? ' has-error' : '' }}">
                        Password: <input type="password" class="form-control" name="password" required>
                        @if (array_key_exists('password', $errors))
                            <span class="help-block">
                                <strong>{{ $errors['password'][0] }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        Password confirmation: <input type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-block dm-btn">
                        Reset
                    </button>
                    @endif
                </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
