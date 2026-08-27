<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Prosan Atelier</title>
    <link rel="stylesheet" href="{{ asset('css/prosan-atelier.css') }}">
</head>
<body class="login-body">
<div class="login-card">
    <div class="login-logo"><img src="{{ asset('images/prosan-logo.jpg') }}" alt="Prosan Atelier"><span>Prosan Atelier</span></div>
    <h1>Admin Login</h1>
    <p class="muted">Manage products, categories, brands, stock and orders.</p>
    @include('partials.flash')
    <form method="POST" action="{{ route('admin.login.store') }}" class="form-grid">
        @csrf
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        <label>Password</label>
        <input type="password" name="password" required>
        <button class="btn full" type="submit">Login</button>
    </form>
</div>
</body>
</html>
