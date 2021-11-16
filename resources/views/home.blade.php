@extends('main')
@section('content')
  {!! $calendar->calendar() !!}
  {!! $calendar->script() !!}

@endsection  