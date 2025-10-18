@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class=" overflow-hidden  sm:rounded-lg p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($products as $product)
            <a href="{{ route('product.show', $product['id']) }}">
                <x-cart :producto="$product" />
            </a>
        @endforeach
    </div>
@endsection
