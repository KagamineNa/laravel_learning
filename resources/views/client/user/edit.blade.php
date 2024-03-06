@extends('layouts.clients')

@section('content')
    <section>
        <div class="container ">
            @if (session('msg'))
                <div class="alert alert-success">
                    {{ session('msg') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    Du lieu nhap vao khong hop le
                </div>
            @endif
            <h1>{{ $title }}</h1>
            <form action="{{ route('user.update') }}" method="post">
                @csrf
                <div class="form-group my-3">
                    <label for="fullname">Ho va Ten</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Nhap ho va ten"
                        value="{{ $userDetail->fullname }}">
                    @error('fullname')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group my-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Nhap email"
                        value="{{ $userDetail->email }}">
                    @error('email')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Cap Nhat</button>
                <a href="{{ route('user.list') }}" class= "btn btn-warning">Quay lai</a>
            </form>
        </div>
    </section>
@endsection
