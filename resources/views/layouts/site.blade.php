{{-- Halaman publik: pengunjung melihat header biasa, user yang login melihat sidebar user. --}}
@extends('layouts.base')

@section('body')
    @auth
        <x-dashboard role="user">
            @yield('content')
        </x-dashboard>
    @else
        @include('partials.header')
        @yield('content')
    @endauth
@endsection
