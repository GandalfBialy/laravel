@extends('layouts.app')

@section('content')
  <h1>{{ __('Contact') }}</h1>
  <p>{{ __('Hello this is contact!') }}</p>

  @can('home.secret')
    <p>
      <a href="{{ route('secret') }}">
        Go to special contact details!
      </a>
    </p>
  @endcan
@endsection






{{--  @extends('layouts.app')

@section('title', 'contact')

@section('content')
  <h1>Contact page :OO</h1>
  <p>Some contact infoooooooooooo</p>

  @can('home.secret')
  <p>Special contact details for admins</p>
  <a href="{{ route('home.secret') }}">
    Secret page!!!
  </a>
  @endcan
@endsection  --}}