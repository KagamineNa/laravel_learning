@extends('layouts.clients')

@section('content')
    <section>
        <div class="container text-center">
            <h1>{{ $title }}</h1>
            @if (session('msg'))
                <div class="alert alert-success text-center">{{ session('msg') }}</div>
            @endif
        </div>
    </section>
@endsection

@section('sidebar')
    @parent
    <div class="sidebar">
        <h3>Danh sach san pham</h3>
    </div>
@endsection
