@extends('layouts.app')

@section('content')
    <div class="container app-content">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <h1 class="text-center" style="margin-bottom: 30px">{{ $article->title }}</h1>
                <div class="author">
                    <div class="name">
                        <img class="avatar" src="{{ $article->user->avatar }}" style="border-radius:1000px; height: 48px; width: 48px;">
                        <div class="info">
                            <a href="{{ url('/user/'.$article->user->id) }}">{{ $article->user->name }} </a>
                            <span> {{ $article->created_at }}</span>
                        </div>
                        <span style="float: right; vertical-align: middle;">
                        @can('update', $article)
                                <a href="{{ url('/article/'.$article->id.'/edit') }}"><i class="fa fa-edit"></i> edit</a>
                            @endcan
                            @can('delete', $article)
                                |<form id="delete" action="{{ url('/article/'.$article->id) }}" method="POST" style="display: inline">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <a onclick="document.getElementById('delete').submit();"><i class="fa fa-trash"></i> delete</a>
                            </form>
                            @endcan
                        </span>
                    </div>
                </div>
                <hr>
                {!! $article->content !!}
            </div>
            <div class="col-lg-2"></div>
        </div>
        <hr>
        <div class="row">
            <div class="text-center">
                @if(!$article->vote(\Auth::id())->exists())
                    <a id="like" class="btn btn-danger like-button" article-id="{{ $article->id }}" like-status="0">
                        <i class="fa fa-heart-o" id="like-icon"></i> | <span id="article-like">喜欢</span> <span id="article-vote-count">{{ $article->votes->count() }}</span>
                    </a>
                @else
                    <a  id="like" class="btn btn-danger like-button" article-id="{{ $article->id }}" like-status="1">
                        <i class="fa fa-heart" id="like-icon"></i> | <span id="article-like">取消喜欢</span> <span id="article-vote-count">{{ $article->votes->count() }}</span>
                    </a>
                @endif
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{--<div class="row">--}}
                        <p>Comments</p>
                    {{--</div>--}}
                </div>
                <div class="panel-body">
                    @if($article->comments->count()>0)
                        @foreach($article->comments as $comment)
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-md-11">
                                            <a href="{{ url('/user', ['id' => $comment->user->id]) }}">{{ $comment->user->name }}</a> at {{ $comment->created_at->toDateTimeString() }}
                                        </div>
                                        <div class="col-md-1">
                                            <a href="#comment-content" name="{{ $comment->user->name }}" id="{{ $comment->user->id }}" onclick="clickReply(this)"><i class="fa fa-mail-reply"></i></a>
                                        </div>
                                        {{--<div class="col-md-1">--}}
                                            {{--@if(!$comment->vote(\Auth::id())->exists())--}}
                                                {{--<a href="{{ url('/comment/'.$comment->id.'/vote') }}"><i class="fa fa-thumbs-o-up"></i></a>--}}
                                            {{--@else--}}
                                                {{--<a href="{{ url('/comment/'.$comment->id.'/unvote') }}"><i class="fa fa-thumbs-up"></i></a>--}}
                                            {{--@endif--}}
                                            {{--{{ $comment->votes->count() }}--}}
                                        {{--</div>--}}
                                    </div>
                                </div>
                                <div class="panel-body">
                                    @if($comment->upper_id != null)
                                        <a href="{{ url('/user', ['id' => $comment->upperUser->id]) }}">@ {{ $comment->upperUser->name }}</a>
                                    @endif
                                    {!! $comment->content !!}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center" style="height: 50px">
                            <h4>暂时没有评论哦~</h4>
                        </div>
                    @endif
                    <hr>
                    @if(Auth::guest())
                        <div class="col-sm-offset-5">
                            <a class="btn btn-primary" href="{{ url('login') }}">login</a> 登陆发表评论哦～
                        </div>
                    @else
                        回复：<span id="upper_name"></span>
                        <form role="form" method="post" action="{{ url('/comment/store') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <textarea id="comment-content" class="form-control" rows="5" name="content"></textarea>
                                <input class="hidden" value="{{ Auth::user()->id }}" name="user_id">
                                <input class="hidden" value="{{ $article->id }}" name="article_id">
                                <input class="hidden" id="upper_id" name="upper_id">
                            </div>
                            <div class="form-group">
                                <div class="text-center">
                                    <button class="btn btn-primary" type="button" onclick="form.submit();">submit</button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
            </div>
            {{--评论区结束--}}
        </div>
    </div>
@endsection

@section('js')
    <script>
        function clickReply(obj) {
            window.location.hash = "#comment-content";
            //document.getElementById('comment-content').value = "@"+obj.name+' ';
            document.getElementById('upper_name').innerText = obj.name;
            document.getElementById('upper_id').value = obj.id;
        }

        $(document).ready(function () {
            $(".like-button").click(function (e) {
//                var target = $(e.target);
//                alert(target.attr('article-id'));
                var article_id = $(this).attr('article-id');
                var like_status = $(this).attr('like-status');
                if (like_status == 0) {
                    $.ajax({
                        method: "GET",
                        url: "/article/"+article_id+"/vote",
                        dataType: "json",
                        success: function (data) {
                            if (data.error != 0) {
                                alert(data.msg);
                                return;
                            }
                            document.getElementById('like').setAttribute('like-status', '1');
                            document.getElementById('like-icon').setAttribute('class', 'fa fa-heart');
                            document.getElementById('article-vote-count').innerText = data.count;
                            document.getElementById('article-like').innerText = "取消喜欢";
                        }
                    });
                } else {
                    $.ajax({
                        method: "GET",
                        url: "/article/"+article_id+"/unvote",
                        dataType: "json",
                        success: function (data) {
                            if (data.error != 0) {
                                alert(data.msg);
                                return;
                            }
                            document.getElementById('like').setAttribute('like-status', '0');
                            document.getElementById('like-icon').setAttribute('class', 'fa fa-heart-o');
                            document.getElementById('article-vote-count').innerText = data.count;
                            document.getElementById('article-like').innerText = "喜欢";
                        }
                    });
                }

            });
        });
    </script>
@endsection