@extends('layouts.admin')

@section('title', 'Admin Account')

@section('content')
<div class="section-heading admin-heading-row">
    <div>
        <h1>Admin Account</h1>
        <p class="muted">Update admin name, email and password securely.</p>
    </div>
    <a class="btn ghost" href="{{ route('admin.dashboard') }}">Back to Dashboard</a>
</div>

@if($errors->any())
    <div class="alert-box error-box">
        <strong>Please fix the following:</strong>
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="content-card admin-profile-card">
    <form method="POST" action="{{ route('admin.profile.update') }}" class="form-grid clean-form-grid">
        @csrf
        @method('PUT')

        <div>
            <label>Name *</label>
            <input name="name" value="{{ old('name', $admin->name) }}" required>
        </div>

        <div>
            <label>Email *</label>
            <input type="email" name="email" value="{{ old('email', $admin->email) }}" required>
        </div>

        <div class="field-full">
            <label>Current Password *</label>
            <input type="password" name="current_password" required placeholder="Enter current admin password to save changes">
        </div>

        <div>
            <label>New Password</label>
            <input type="password" name="password" placeholder="Leave blank to keep current password">
            <small class="muted">Minimum 8 characters.</small>
        </div>

        <div>
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" placeholder="Confirm new password">
        </div>

        <div class="field-full admin-form-actions">
            <button class="btn" type="submit">Update Admin Account</button>
        </div>
    </form>
</div>
@endsection
