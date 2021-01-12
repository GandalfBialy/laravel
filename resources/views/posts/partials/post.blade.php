{{--  @forelse($posts as $key => $post)  --}}

    {{-- @break($key = 2) --}}
    {{-- @continue($key = 1) --}}

    {{--  @if($loop->even)
      <div>x| {{ $key }}. {{ $post['title'] }}</div>
    @else
      <div>D| {{ $key }}. {{ $post['title'] }}</div>  --}}
    {{--  @endif


    @empty
    Jak tu niczego nie ma!! :(
@endforelse  --}}


<h3>
  <a href="{{ route('posts.show', ['post' => $post->id]) }}">
    {{ $post->title }}
  </a>
</h3>

<div class="mb-3">
    <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">
      Edit
    </a>

    <form class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
        @csrf
        @method('DELETE')

        <input type="submit" value="Delete :U" class="btn btn-primary">
    </form>
</div>