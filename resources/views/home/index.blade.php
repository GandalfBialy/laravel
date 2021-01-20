@extends('layouts.app')

@section('content')
<h1>{{ __('messages.welcome') }}</h1>
<h1>@lang('messages.welcome')</h1>

<p>{{ __('messages.welcome_with_name', ['name' => 'John']) }}</p>

<p>{{ trans_choice('messages.plural', 0, ['a' => 1]) }}</p>
<p>{{ trans_choice('messages.plural', 1, ['a' => 1]) }}</p>
<p>{{ trans_choice('messages.plural', 2, ['a' => 1]) }}</p>

<p>Using JSON: {{ __('Welcome to Laravel!') }}</p>
<p>Using JSON: {{ __('Hello :name', ['name' => 'Piotr']) }}</p>

<p>This is the content of the main page!</p>
@endsection



{{--  @extends('layouts.app')

@section('title', 'home')

@section('content')
  <h1>{{ __('messages.welcome') }}</h1>
  <h1>@lang('messages.welcome')</h1>

  <p>@lang('messages.welcome_with_name', ['name' => 'Karandasz'])</p>

  <p>{{ trans_choice('messages.plural', 0) }}</p>
  <p>{{ trans_choice('messages.plural', 1) }}</p>
  <p>{{ trans_choice('messages.plural', 2) }}</p>
  <p>{{ trans_choice('messages.plural', 3) }}</p>

  <p>Using JSON: {{ __('Welcome to Laravel!') }}</p>
  <p>Using JSON: {{ __('Hello :name', ['name' => 'Igor']) }}</p>
@endsection  --}}