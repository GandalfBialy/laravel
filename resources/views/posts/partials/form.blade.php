<div>
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
@endif