@extends('layouts.app')

@section('content')
    <div class="container app-content">
        <div class="row">
            <div class="col-md-8">
                <form role="form" method="post" action="{{ url('/user/me/setting') }}">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-group">
                        <label for="name">name:</label>
                        <input type="text" class="form-control" id="title" name="name" value="{{ \Auth::user()->name }}">
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5">
                            <button class="btn btn-primary">submit</button>
                            <a href="{{ url()->previous() }}" class="btn btn-danger">返回</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-center">
                <p style="margin-top: 50px">
                    <img id="avatar" src="{{ $user->avatar }}" alt="" class="img-circle" style="border-radius:1000px; height: 150px; width: 150px">
                </p>
                <p>
                    {{--<button class="btn btn-primary" onclick="uploadAvatar(this)"> 更改头像 </button>--}}
                    <label class="btn btn-primary" for="uploadAvatar">
                        更改头像
                        <input id="uploadAvatar" class="hidden" type="file" name="avatar-change" onchange="uploadAvatar()">
                    </label>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function uploadAvatar() {
            data = new FormData();
            data.append("file", $('#uploadAvatar')[0].files[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                type: "POST",
                url: "/user/me/uploadAvatar",
                cache: false,
                contentType: false,
                processData: false,
                success: function(url) {
                    $('#avatar').attr('src', url);
                }
            });
        }
    </script>
@endsection