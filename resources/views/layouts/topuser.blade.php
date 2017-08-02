@foreach($top_users as $top_user)
    <div class="author">
        <div class="name">
            <img class="avatar" src="{{ $top_user->avatar }}" style="border-radius:1000px; height: 40px; width: 40px;">
            <div class="info">
                <a href="{{ url('/user/'.$top_user->id) }}">{{ $top_user->name }} </a>
                <span> {{ $top_user->articles->count() }}篇文章 {{ $top_user->followers->count() }}位粉丝</span>
            </div>
        </div>
    </div>
@endforeach