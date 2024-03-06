@extends('layouts.clients')

@section('content')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>{{ $title }}</h1>
                    <form action="{{ route('post-add') }}" method="post" id="product_form">
                        @csrf
                        @error('msg')
                            <div class="alert alert-danger text-center">{{ $message }}</div>
                        @enderror
                        {{-- <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name"
                                value="{{ old('name') }}">
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div> --}}
                        {{-- <div class="mb-3">
                            <label for="price">Price</label>
                            <input type="text" class="form-control" id="price" name="price"
                                placeholder="Enter Price" value="{{ old('price') }}">
                            @error('price')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div> --}}
                        <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name"
                                value="{{ old('name') }}">
                            <p style="color: red;" class="error name_error"></p>
                        </div>
                        <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="price" name="price"
                                placeholder="Enter price" value="{{ old('price') }}">
                            <p style="color: red;" class="error price_error"></p>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
    </section>
@endsection
{{-- 
send form data using Ajax, for better user experience and to avoid page reload. --}}
@section('script')
    <script>
        $(document).ready(function() {
            $("#product_form").on('submit', function(e) {
                e.preventDefault();
                let productName = $('input[name="name"]').val().trim();
                let productPrice = $('input[name="price"]').val().trim();
                let actionUrl = $(this).attr('action');
                let csrfToken = $(this).find('input[name="_token"]').val();

                $('.error').text(
                ''); // xoa hien thi text loi, neu khong lan sau khi chua nhap input no da hien thi loi cu 

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: {
                        name: productName,
                        price: productPrice,
                        _token: csrfToken,
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(err) {
                        let responseJSON = err.responseJSON.errors;
                        for (let key in responseJSON) {
                            $('.' + key + '_error').text(responseJSON[key][0]);
                        }
                    }
                });
            });
        });
    </script>
@endsection
