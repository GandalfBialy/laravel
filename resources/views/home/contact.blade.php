@extends('layouts.app')

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
@endsection