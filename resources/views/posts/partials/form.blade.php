<div class="form-group">
  <label>Title</label>
  <input type="text" name="title" class="form-control"
      value="{{ old('title', $post->title ?? null) }}"/>
</div>

<div class="form-group">
  <label>Content</label>
  <input type="text" name="content" class="form-control"
      value="{{ old('content', $post->content ?? null) }}"/>
</div>

<div class="form-group">
  <label>Thumbnail</label>
  <input type="file" name="thumbnail" class="form-control-file"/>
</div>

@errors @enderrors



{{--  <div>
  <input type="text" name="title" value="{{ old('title', optional($post ?? null)->title) }}">
</div>

@error('title')
  <div style="color: red;">{{ $message }}</div>
@enderror

<div>
    <textarea name="content">{{ old('content', optional($post ?? null)->content ?? null) }}</textarea>
</div>

@error('content')
  <div style="color: red;">{{ $message }}</div>
@enderror

@if ($errors->any())
  <div>
    <ul> Summary <br>
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif  --}}