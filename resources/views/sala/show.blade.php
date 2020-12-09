@extends('main')
@section('content')
  @include('sala.partials.fields')

  {!! $calendar->calendar() !!}
  {!! $calendar->script() !!}
  
@endsection  