@extends('layouts.admin')
@section('title', 'Add Category')
@section('content')
<div class="content-card">
    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.categories._form')
    </form>
</div>
@endsection
