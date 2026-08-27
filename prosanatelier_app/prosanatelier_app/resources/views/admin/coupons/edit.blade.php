@extends('layouts.admin')
@section('title', 'Edit Coupon')
@section('content')
<div class="section-heading"><h1>Edit Coupon</h1><a href="{{ route('admin.coupons.index') }}">Back</a></div>
<form method="POST" action="{{ route('admin.coupons.update', $coupon) }}" class="content-card admin-coupon-form">
    @csrf @method('PUT')
    @include('admin.coupons.form')
</form>
@endsection
