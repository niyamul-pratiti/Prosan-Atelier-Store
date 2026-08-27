@extends('layouts.admin')
@section('title', 'Add Brand')
@section('content')
<div class="content-card">
    <form method="POST" action="{{ route('admin.brands.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.brands._form')
    </form>
</div>
@endsection
