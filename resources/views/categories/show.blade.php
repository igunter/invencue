@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="p-5 mb-4 bg-body-tertiary rounded-3">
        <h1 class="display-6 fw-bold">{{ $category->name }}</h1>
    </div>
@endsection
