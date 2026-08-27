@extends('layouts.admin')
@section('title', 'Edit Category')
@section('content')
<div class="content-card">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.categories._form')
    </form>
</div>
@endsection
