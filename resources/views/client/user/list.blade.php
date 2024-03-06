@extends('layouts.clients')

@section('content')
    <section>
        <div class="container ">
            @if (session('msg'))
                <div class="alert alert-success">
                    {{ session('msg') }}
                </div>
            @endif
            <h1>{{ $title }}</h1>
            <a href="{{ route('user.add') }}" class="btn btn-primary my-3">Them nguoi dung moi</a>
            <hr />
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="5%">STT</th>
                        <th width="30%">Ten</th>
                        <th>Email</th>
                        <th width="15%">Thoi gian</th>
                        <th width="5%">Sua</th>
                        <th width="5%">Xoa</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($usersList))
                        @foreach ($usersList as $key => $user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->fullname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->create_at }}</td>
                                <td>
                                    <a href="{{ route('user.edit', ['id' => $user->id]) }}"
                                        class="btn btn-warning btn-sm">Sua</a>
                                </td>
                                <td>
                                    <a onClick = "return confirm('Ban co chac chan muon xoa?')"
                                        href="{{ route('user.delete', ['id' => $user->id]) }}"
                                        class="btn btn-danger btn-sm">Xoa</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">Khong co du lieu</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </section>
@endsection
