@extends('layouts.clients')

@section('content')
    <section>
        <div class="container ">
            @if (session('msg'))
                <div class="alert alert-{{ session('type') }}">
                    {{ session('msg') }}
                </div>
            @endif
            <h1>{{ $title }}</h1>
            <a href="{{ route('user.add') }}" class="btn btn-primary my-3">Them nguoi dung moi</a>
            <hr />
            <form action="" method="GET" class="my-4">
                <div class="row">
                    <div class="col-3">
                        <select class="form-control" name="status">
                            <option value="0">Tat ca trang thai</option>
                            <option value="active" {{ request()->status == 'active' ? 'selected' : false }}>Activated
                            </option>
                            <option value="inactive" {{ request()->status == 'inactive' ? 'selected' : false }}>Inactivated
                            </option>
                        </select>
                    </div>
                    <div class="col-3">
                        <select class="form-control" name="groups_id">
                            <option value="0">Tat ca nhom</option>
                            @if (!empty(getAllGroups()))
                                @foreach (getAllGroups() as $group)
                                    <option value="{{ $group->id }}"
                                        {{ request()->groups_id == $group->id ? 'selected' : false }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-4">
                        <input type="search" class="form-control" placeholder="Tu khoa tim kiem..." name="keywords"
                            value="{{ request()->keywords }}" />
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary btn-block">Tim kiem</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="5%">STT</th>
                        <th width="17%">
                            <a href="?sort-field=fullname&sort-type={{ $sortType }}">Ten</a>
                        </th>
                        <th><a href="?sort-field=email&sort-type={{ $sortType }}">Email</a></th>
                        <th>Nhom</th>
                        <th>Trang thai</th>
                        <th width="15%"><a href="?sort-field=create_at&sort-type={{ $sortType }}">Thoi gian</a></th>
                        <th width="5%">Sua</th>
                        <th width="5%">Xoa</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($usersList[0]))
                        @foreach ($usersList as $key => $user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->fullname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->groups_name }}</td>
                                <td>
                                    @if ($user->status == null)
                                        <button class="btn btn-danger">Inactivated</button>
                                    @else
                                        <button class="btn btn-success">Activated</button>
                                    @endif
                                </td>
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
                        <tr class="text-center">
                            <td colspan="8">Khong co du lieu</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                {{ $usersList->links() }}
            </div>
        </div>
    </section>
@endsection
