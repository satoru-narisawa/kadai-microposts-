@if(Auth::user()->favoriting($micropost->id))
    {!! Form::open(["route"=>["favorites.unfavorite",$micropost->id],"method" => "delete"]) !!}
        {!! Form::submit("unfavorite",["class" => "btn btn-warning btn-sm"]) !!}
    {!! Form::close() !!}
@else
    {!! Form::open(["route"=>["favorites.favorite",$micropost->id]]) !!}
        {!! Form::submit("favorite",["class" => "btn btn-success btn-sm"]) !!}
    {!! Form::close() !!}
@endif