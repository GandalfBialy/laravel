{{--  @extends('layouts.app')

@section('title', $posts['title'])

@section('content')
  @if($posts['is_new'])
    <div>Nowy post na blogu! if</div>

    @else  --}}
    {{--  @elseif(!$posts['is_new'])  --}}
    {{--  <div>Stary jest - elseif/else</div>  --}}
  {{--  @endif

  @unless($posts['is_new'])
    <div>old post! - unless</div>
  @endunless

  <h1>{{ $posts['title'] }}</h1>
  <p>{{ $posts['content'] }}</p>

  @isset($posts['has_comments'])
    <div>there are som comments --> isset</div>
  @endisset
@endsection  --}}

@extends('layouts.app')

@section('title', $post->title)

@section('content')

<h1>
  {{ $post->title }}
</h1>

<p>
  {{ $post->content }}
</p>

<p>
  Added {{ $post->created_at->diffForHumans() }}
</p>

@if(now()->diffInMinutes($post->created_at) < 5)
<div class="alert alert-info">
  New!
</div>
@endif

@endsection