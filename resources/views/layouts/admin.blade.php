@extends('layouts.base')

@section('body')
    <x-dashboard role="admin">
        @yield('content')
    </x-dashboard>
@endsection
