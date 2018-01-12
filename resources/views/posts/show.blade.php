@extends('layouts.app')
 
 @section('content')
        
        
        <div class="well">

        <h1>{{$posts->title}}</h1>
        <img  style="width:100%"src="/storage/cover_images/{{$posts->cover_image}}">
        <br>
        <br>
        <p>{!!$posts->body!!}</p>
        
        </div>
        <a href="/posts" class="btn btn-primary"> Go Back</a>
        @if(!Auth::guest())
         @if(Auth::user()->id==$posts->user_id)
        <a href="/posts/{{$posts->id}}/edit" class="btn btn-default">Edit </a>
        {!!Form::open(['action'=>['PostsController@destroy',$posts->id],'method'=>'POST','class'=>'pull-right'])!!}
            {{Form::hidden('_method','DELETE')}}
            {{Form::submit('DELETE',['class'=>'btn btn-danger'])}}
        {!!Form::close()!!}
          @endif
        @endif
   @endsection