@extends('layouts.admin')
@section('title', 'Add Product')
@section('content')
<div class="content-card">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.products._form')
    </form>
</div>
@endsection
