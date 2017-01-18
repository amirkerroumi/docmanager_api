<?php
    $errors = isset($errors) ? $errors : [];
    $email = isset($email) ? $email : "";
?>
@extends('layouts.app')

@section('content')
<!-- Styles -->
<link href="/css/app.css" rel="stylesheet">
<form method="POST" action="{{ url('/password/reset/') }}">
    <input type="hidden" value="{{ $token  }}" name="token">
    Email: <input type="email" name="email" value="{{ $email }}"><br/>
    @if (array_key_exists('email', $errors))
        <span class="help-block">
            <strong>{{ $errors['email'][0] }}</strong>
        </span>
    @endif
    Password: <input type="password" name="password"><br/>
    @if (array_key_exists('password', $errors))
        <span class="help-block">
            <strong>{{ $errors['password'][0] }}</strong>
        </span>
    @endif
    Password confirmation: <input type="password" name="password_confirmation"><br/>
    <input type="submit" value="Reset">
</form>
@endsection