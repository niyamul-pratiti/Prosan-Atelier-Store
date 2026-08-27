@extends('layouts.admin')
@section('title', 'Edit Product')
@section('content')
@if($product->images->count())
    @foreach($product->images as $image)
        <form id="delete-image-{{ $image->id }}" method="POST" action="{{ route('admin.products.images.destroy', $image) }}">
            @csrf @method('DELETE')
        </form>
    @endforeach
@endif
<div class="content-card">
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.products._form')
    </form>
</div>
@endsection
