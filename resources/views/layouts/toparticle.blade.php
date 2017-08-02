@foreach($top_articles as $top_article)
    <div>
        <a href="{{ url('/article/'.$top_article->id) }}">{{ $top_article->title }}</a>
        <p>{{ $top_article->comments->count() }}评论</p>
    </div>
@endforeach