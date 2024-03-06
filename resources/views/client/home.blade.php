@extends('layouts.clients')

@section('content')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @if (session('msg'))
                        <div class="alert alert-{{ session('type') }} text-center">{{ session('msg') }}</div>
                    @endif
                    <h1>{{ $title }}</h1>
                    @include('client.contents.slide')
                    @datetime('2024-11-15 15:00:30')
                    {{-- cusstom datetime, create in Provider AppService --}}

                    @env('local')
                    <p>Environment is local</p>
                    @elseenv('production')
                    <p>Environment is production</p>
                @else
                    <p>Environment is not local or production</p>
                    @endenv

                    <x-package-alert type="success" data-icon="facebook" message="{{ $message }}" />
                    {{-- luu y khi viet thuoc tinh data-icon, not dataIcon --}}
                    {{-- cusstom component, create in Provider AppService --}}

                </div>
            </div>
        </div>
    </section>
@endsection

@section('sidebar')
    @parent
    <div class="sidebar">
        <h3>Sub Main SideBar</h3>
        <button class="show">Click here</button>
    </div>
@endsection
