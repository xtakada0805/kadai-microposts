@if (count($microposts) > 0)
    <ul class="list-unstyled">
        @foreach($microposts as $micropost)
            <li class="media mb-3">
                <img class="mr-2 rounded" src="{{ Gravatar::get($micropost->user->email, ['size' => 50]) }}" alt="">
                <div class="media-body">
                    <div>
                        {!! link_to_route('users.show', $micropost->user->name, ['user' => $micropost->user->id]) !!}
                        <span class="text-muted">posted at {{ $micropost->created_at }}</span>
                    </div>
                    <div>
                        <!--投稿内容-->
                        <p class="mb-0">{!! nl2br(e($micropost->content)) !!}</p>
                    </div>
                    
                    <div class="d-flex flex-row">
                        @if (Auth::id() != $micropost->user_id)
                            @if(Auth::user()->is_favorite($micropost->id))
                                {!! Form::open(['route' => ['favorites.unfavorite', $micropost->id], 'method' => 'delete']) !!}
                                    {!! Form::submit('Unfavorite', ['class' => 'btn btn-success btn-sm']) !!}
                                {!! Form::close() !!}
                            @else
                                {!! Form::open(['route' => ['favorites.favorite', $micropost->id], 'method' => 'post']) !!}
                                    {!! Form::submit('Favorite', ['class' => 'btn btn-light btn-sm']) !!}
                                {!! Form::close() !!}
                            @endif
                        @endif
                        @if (Auth::id() == $micropost->user_id)
                            <!--投稿削除ボタン-->
                            {!! Form::open(['route' => ['microposts.destroy', $micropost->id], 'method' => 'delete']) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        @endif
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    <!--ページネーション-->
    {{ $microposts->links() }}
@endif