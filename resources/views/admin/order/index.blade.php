@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')


    @livewire('admin.order-table')
    @livewire('admin.order-details')
@endsection
