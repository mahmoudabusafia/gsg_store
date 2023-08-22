@extends('layouts.admin') 

@section('title')
<div class="d-flex justify-content-between">
    <h2>Trashed Products</h2>
</div> 
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Home</a></li>
    <li class="breadcrumb-item active">Products</li>
</ol>
@endsection

@section('content')

    <x-alert />

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Deleted At</th>
                <th>
                    <form action="{{ route('roles.restore')}}" method="post">
                    @csrf
                    @method('put')
                    <button type="submit" class="btn btn-sm btn-warning">Restore All</button>
                    </form>
                </th>
                <th>
                    <form action="{{ route('roles.force-delete') }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-sm btn-danger">Delete All forever</button>
                    </form>
                </th>
            </tr>
        </thead>
        <tbody>
             @foreach($roles as $role)
                <tr>
                    <td><img src="{{ asset('uploads/' . $role->image_path) }}" width="50" alt=""></td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->category_name }}</td>
                    <td>{{ $role->price }}</td>
                    <td>{{ $role->quantity }}</td>
                    <td>{{ $role->status }}</td>
                    <td>{{ $role->deleted_at }}</td>
                    <td>
                        <form action="{{ route('roles.restore', $role->id) }}" method="post">
                        @csrf
                        @method('put')
                        <button type="submit" class="btn btn-sm btn-warning">Restore</button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('roles.force-delete', $role->id) }}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-sm btn-danger">Delete for Ever</button>
                        </form>
                    </td>
                </tr>
             @endforeach 
        </tbody>
    </table>

    {{ $roles->links('pagination') }}    
        
    
@endsection

