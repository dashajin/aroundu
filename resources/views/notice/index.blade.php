@extends('layouts.app')

@section('content')
    <el-row>
        <el-col :span="16" :offset="4">
            <el-row :gutter="20">
                <el-col :span="6">
                    <ul class="nav nav-pills nav-stacked">
                        <li>
                            <a href="{{ url('/notice/comment') }}"><i class="fa fa-comments"></i> 评论
                                @if(\Auth::user()->receivedCommentUnreadNotifications->count()>0)
                                    <span class="badge" style="background-color: red">{{ \Auth::user()->receivedCommentUnreadNotifications->count() }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/notice/like') }}"><i class="fa fa-heart"></i> 喜欢和赞
                                @if(\Auth::user()->gotVoteUnreadNotifications->count()>0)
                                    <span class="badge" style="background-color: red">{{ \Auth::user()->gotVoteUnreadNotifications->count() }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/notice/follow') }}"><i class="fa fa-male"></i> 关注
                                @if(\Auth::user()->gotFollowerUnreadNotifications->count()>0)
                                    <span class="badge" style="background-color: red">{{ \Auth::user()->gotFollowerUnreadNotifications->count() }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </el-col>
                <el-col :span="18">

                </el-col>
            </el-row>
        </el-col>
    </el-row>
@endsection