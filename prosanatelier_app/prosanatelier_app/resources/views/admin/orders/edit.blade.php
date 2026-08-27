@extends('layouts.admin')
@section('title', 'Edit Order ' . $order->order_number)
@section('content')
<div class="section-heading">
    <h1>Edit {{ $order->order_number }}</h1>
    <a class="btn ghost" href="{{ route('admin.orders.show', $order) }}">Back to Order</a>
</div>
@include('admin.orders._form', [
    'order' => $order,
    'action' => route('admin.orders.update', $order),
    'method' => 'PUT',
])
@endsection
