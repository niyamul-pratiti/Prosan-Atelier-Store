@extends('layouts.admin')
@section('title', 'Create Order')
@section('content')
<div class="section-heading">
    <h1>Create Order</h1>
    <a class="btn ghost" href="{{ route('admin.orders.index') }}">Back to Orders</a>
</div>
@include('admin.orders._form', [
    'order' => null,
    'action' => route('admin.orders.store'),
    'method' => 'POST',
])
@endsection
