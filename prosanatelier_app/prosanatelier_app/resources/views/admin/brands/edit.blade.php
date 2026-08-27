@extends('layouts.admin')
@section('title', 'Edit Brand')
@section('content')
<div class="content-card">
    <form method="POST" action="{{ route('admin.brands.update', $brand) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.brands._form')
    </form>
</div>
@endsection
