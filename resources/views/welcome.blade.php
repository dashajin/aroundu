@extends('layouts.app')

@section('content')
    <div class="app-content">
        <el-row>
            <el-col :span="16" :offset="4">
                <el-row>
                    <div id="carousel">
                        <el-carousel :interval="3000" type="card" height="300px">
                            @foreach($articleimages as $image)
                                <el-carousel-item key="{{ $image['image'] }}">
                                    <a href="{{ $image['link'] }}">
                                        <img src='{{ $image['image'] }}' style="height: 300px; width: 400px">
                                    </a>
                                </el-carousel-item>
                            @endforeach
                        </el-carousel>
                    </div>
                </el-row>
                <el-row :gutter='10'>
                    <el-col :sm="16" id="article-content">
                        <el-row>
                            <div class="recommend-collection">
                                @foreach($topics as $topic)
                                    <a href="{{ url('/topic/'.$topic->id) }}" class="collection">
                                        <img src="{{ $topic->image }}">
                                        <div class="collection-name">{{ $topic->name }}</div>
                                    </a>
                                @endforeach
                            </div>
                            <hr>
                        </el-row>
                        @foreach($articles as $article)
                            <articleitem
                                    user_id="{{ $article->user->id }}"
                                    user_name="{{ $article->user->name }}"
                                    user_avatar="{{ $article->user->avatar }}"
                                    article_id="{{ $article->id }}"
                                    article_title="{{ $article->title }}"
                                    article_content="<?php
                                    $contentes = preg_replace("/<[^>]+>/", '', $article->content);
                                    echo str_limit($contentes, 150, '...');
                                    ?>"
                                    article_created_at="{{ $article->created_at }}"
                                    article_image= "{{ is_null($article->image) ? 'isok' : $article->image }}"
                                    article_votes_count="{{ $article->votes->count() }}"
                                    article_comments_count="{{ $article->comments->count() }}"
                            ></articleitem>
                        @endforeach
                        {{ $articles->links() }}
                    </el-col>
                    <el-col :sm="8">
                        <div class="grid-content bg-purple-light"></div>
                    </el-col>
                </el-row>
            </el-col>
        </el-row>
    </div>
@endsection

@section('js')
@endsection

@section('css')
    <style>
        .el-col {
            border-radius: 4px;
        }
        .bg-purple-dark {
            background: #99a9bf;
        }
        .bg-purple {
            background: #d3dce6;
        }
        .bg-purple-light {
            background: #e5e9f2;
        }
        .grid-content {
            border-radius: 4px;
            min-height: 36px;
        }
    </style>
    <style>
        .recommend-collection .collection {
            display: inline-block;
            margin: 0 18px 18px 0;
            min-height: 32px;
            background-color: #f7f7f7;
            border: 1px solid #dcdcdc;
            border-radius: 4px;
            vertical-align: top;
            overflow: hidden;
        }
        .recommend-collection .collection img {
            width: 32px;
            height: 32px;
        }
        .recommend-collection .collection .collection-name {
            display: inline-block;
            padding: 0 11px 0 6px;
            font-size: 14px;
        }
    </style>
@endsection