@extends('layouts.admin')
@section('title', 'Add Coupon')
@section('content')
<div class="section-heading"><h1>Add Coupon</h1><a href="{{ route('admin.coupons.index') }}">Back</a></div>
<form method="POST" action="{{ route('admin.coupons.store') }}" class="content-card admin-coupon-form">
    @csrf
    @include('admin.coupons.form')
</form>
@endsection
