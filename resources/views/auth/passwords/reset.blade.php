<form method="POST" action="{{ url('/password/reset/') }}">
    <input type="hidden" value="{{ $token  }}" name="token">
    Email: <input type="email" name="email"><br/>
    Password: <input type="password" name="password"><br/>
    Password confirmation: <input type="password" name="password_confirmation"><br/>
    <input type="submit" value="Reset">
</form>