@extends('main')
@section('content')

  @include('categoria.partials.fields')
  @include('categoria.partials.addForm')<br>
  @include('categoria.partials.pesIndex')<br>
  <a class="btn btn-outline-dark" href="/" role="button">
      <i class="fas fa-arrow-left"></i> Voltar
  </a><br>
@endsection  