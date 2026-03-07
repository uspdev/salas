@section('styles')
  @parent
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
  <link rel="stylesheet" href="css/arquivos.css">
  <style>
    #card-arquivos {
      border: 1px solid DarkGoldenRod;
      border-top: 3px solid DarkGoldenRod;
    }
  </style>
@endsection

@php
    $max_upload_size = config('salas.upload_max_filesize');
@endphp

<a name="card_arquivos"></a>
<div class="card bg-light mb-3" id="card-arquivos">
  <div class="card-header d-flex align-items-center">
    <span class="h5 mb-0">Imagens</span>
    @if (Gate::allows('owner', $reserva) && Gate::allows('reserva.editar', $reserva))
      <label for="input_arquivo" class="mb-0">
        <span class="btn btn-sm btn-light text-primary ml-2 text-nowrap"> <i class="fas fa-plus"></i> Adicionar</span>
      </label>
      <span data-toggle="tooltip" data-html="true" title="Tamanho máximo de cada imagem: {{ $max_upload_size }}KB ">
        <i class="fas fa-question-circle text-secondary fa-lg ml-2"></i>
      </span>
      <form id="form_arquivo" action="arquivos" method="post" enctype="multipart/form-data"
        class="w-100 d-inline-block">
        @csrf
        <input type="hidden" name="reserva_id" value="{{ $reserva->id }}">
        <input type="hidden" id="max_upload_size" name="max_upload_size" value="{{ $max_upload_size }}">
        <input type="file" name="arquivo[]" id="input_arquivo" accept="image/jpeg,image/png"
          class="d-none" multiple capture="environment">
      </form>
    @endif
  </div>
  <div class="card-body">
    @if (count($reserva->arquivos) > 0)
      <div class="arquivos-imagens">
        @foreach ($reserva->arquivos as $arquivo)
          @if (preg_match('/jpeg|png/i', $arquivo->mimeType))
            @if (Gate::allows('owner', $reserva) && Gate::allows('reserva.editar', $reserva))
              <a onclick="ativar_exclusao_imagem()" class="d-inline-block ml-1 mr-1" data-fancybox="arquivo-galeria"
                href="arquivos/{{ $arquivo->id }}" data-caption='<form action="arquivos/{{ $arquivo->id }}" method="post">
                            @csrf
                            @method("delete")
                            <button type="submit" onclick="return confirm(&#39;Tem certeza que deseja deletar {{ $arquivo->nome_original }}?&#39;);" class="btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>
                        </form>'>
                <img class="arquivo-img" width="50px" src="arquivos/{{ $arquivo->id }}"
                  alt="{{ $arquivo->nome_original }}" data-toggle="tooltip" data-placement="top"
                  title="{{ $arquivo->nome_original }}">
              </a>
            @else
              <a class="d-inline-block ml-1 mr-1" data-fancybox="arquivo-galeria" href="arquivos/{{ $arquivo->id }}">
                <img class="arquivo-img" width="50px" src="arquivos/{{ $arquivo->id }}"
                  alt="{{ $arquivo->nome_original }}" data-toggle="tooltip" data-placement="top"
                  title="{{ $arquivo->nome_original }}">
              </a>
            @endif
          @endif
        @endforeach
      </div>
    @else
      Sem imagens anexadas
    @endif
  </div>
</div>

@include('common.modal-processando')

@section('javascripts_bottom')
  @parent
  <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
  <script src="js/arquivos.js"></script>
@endsection
