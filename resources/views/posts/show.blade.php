@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-8">
      @if($post->image)
        <div style="background-image: url('{{ $post->image->url() }}'); min-height: 500px; color: white; text-align: center; background-attachment: fixed;">
            <h1 style="padding-top: 100px; text-shadow: 1px 2px #000">
        @else
            <h1>
        @endif
            {{ $post->title }}
            @badge(['show' => now()->diffInMinutes($post->created_at) < 30])
                Brand new Post!
            @endbadge
        @if($post->image)    
            </h1>
        </div>
        @else
            </h1>
        @endif
        {{--  <img src="{{ $post->image->url() }}" alt="">  --}}
        {{--  <h1 style="color: red;">HALO!!!</h1>  --}}

        <p>{{ $post->content }}</p>

        {{--  <img src="{{ Storage::url($post->image->path) }}"  --}}
        {{--  <img src="{{ $post->image->url() }}"  --}}

        @updated(['date' => $post->created_at, 'name' => $post->user->name])
        @endupdated
        @updated(['date' => $post->updated_at])
            Updated
        @endupdated

        @tags(['tags' => $post->tags])@endtags

        <p>Currently read by {{ $counter }} people</p>

        <h4>Comments</h4>

        {{--  @include('comments._form')

        @forelse($post->comments as $comment)
            <p>
                {{ $comment->content }}
            </p>
            @updated(['date' => $comment->created_at, 'name' => $comment->user->name])
            @endupdated
        @empty
            <p>No comments yet!</p>
        @endforelse  --}}

        @commentForm(['route' => route('posts.comments.store', ['post' => $post->id])])
        @endcommentForm

        @commentList(['comments' => $post->comments])
        @endcommentList
    </div>
    <div class="col-4">
        @include('posts.partials.activity')
    </div>
@endsection





{{--  @extends('layouts.app')

@section('content')
    <h1>
        {{ $post->title }}
        @badge(['show' => now()->diffInMinutes($post->created_at) < 30])
            Brand new Post!
        @endbadge
    </h1>

    <p>{{ $post->content }}</p>

    @updated(['date' => $post->created_at, 'name' => $post->user->name])
    @endupdated
    @updated(['date' => $post->updated_at])
        Updated
    @endupdated

    <p>Currently read by {{ $counter }} people</p>

    <h4>Comments</h4>

    @forelse($post->comments as $comment)
        <p>
            {{ $comment->content }}
        </p>
        @updated(['date' => $comment->created_at])
        @endupdated
    @empty
        <p>No comments yet!</p>
    @endforelse
@endsection  --}}




{{-- @extends('layouts.app')

@section('content')
    <h1>
      {{ $post->title }}

      @badge(['type' => 'primary', 'show' => now()->diffInMinutes($post->created_at) < 20])
        brand new post
      @endbadge --}}
      {{-- @component('components.badge', ['type' => 'primary'])
        brand new post
      @endcomponent --}}
    {{-- </h1>

    <p>
      {{ $post->content }}
    </p>

    <p>
      Added {{ $post->created_at->diffForHumans() }}
    </p>

    <h4>
      Comments
    </h4>

    @forelse($post->comments as $comment)
        <p>
            {{ $comment->content }}
        </p>
        
        <p class="text-muted">
            added {{ $comment->created_at->diffForHumans() }}
        </p>
    @empty
        <p>No comments yet!</p>
    @endforelse
@endsection --}}





{{--  @extends('layouts.app')

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

@endsection  --}}




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