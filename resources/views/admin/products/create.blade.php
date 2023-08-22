@extends('layouts.admin')

@section('title', 'Create New Product')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Products</a></li>
    <li class="breadcrumb-item active">Create</li>
</ol>
@endsection

@section('content')

<form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
    @csrf

    @include('admin.products._form', [
            'botton' => 'Add',
        ])
</form>

@endsection