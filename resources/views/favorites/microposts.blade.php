<ul class="list-unstyled">
    @foreach($favorites as $favorite)
        <li class="media mb-3">
            <img class="mr-2 rounded" src="{{ Gravatar::src($favorite->user->email,50) }}"alt="">
            <div class="media-body">
                <div>
                    {!! link_to_route("users.show",$favorite->user->name,["id" => $favorite->user->id]) !!}<span class="text-muted">posted at {{ $favorite->created_at }}</span>
                </div>
                <div>
                    <p class="mb-0">{!! nl2br(e($favorite->content)) !!}</p>
                </div>
                <div>
                    @if(Auth::id() == $favorite->user_id)
                        {!! Form::open(["route"=>["microposts.destroy",$favorite->id],"method" => "delete"]) !!}
                            {!! Form::submit("Delete",["class"=>"btn btn-danger btn-sm"]) !!}
                        {!! Form::close() !!}
                    @endif
                </div>
                <div>
                    @if(Auth::user()->favoriting($favorite->pivot->micropost_id))
                        {!! Form::open(["route"=>["favorites.unfavorite",$favorite->pivot->micropost_id],"method" => "delete"]) !!}
                            {!! Form::submit("unfavorite",["class" => "btn btn-warning btn-sm"]) !!}
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(["route"=>["favorites.favorite",$favorite->pivot->micropost_id]]) !!}
                            {!! Form::submit("favorite",["class" => "btn btn-success btn-sm"]) !!}
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>
        </li>
    @endforeach
</ul>
{{ $favorites->render("pagination::bootstrap-4") }}