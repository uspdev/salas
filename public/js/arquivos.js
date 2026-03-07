$(document).ready(function() {
    
    $('[data-toggle="tooltip"]').tooltip();    // ativa os tooltips do bootstrap

    $('#input_arquivo').change(function() {
        var file_name = $(this).val().split(/(\\|\/)/g).pop();
        if (file_name.length == 0){
            $('#nome_arquivo').fadeOut();
            $('#nome_arquivo p').text('');
            return;
        } else {
            var files = $('#input_arquivo')[0].files;
            for (var i = 0; i < files.length; i++)
                $('#nome_arquivo ul').append(
                    '<li title="(' + (files[i].size / 1024).toFixed(2) + 'KB)"><span id="' + i + '" class="btn text-danger btn-sm"> <i class="fas fa-times"></i></span>' + files[i].name + '</li>');
            $('#nome_arquivo').fadeIn();
            $('#nome_arquivo ul li span').click(remove);
        }
        $('#modal_processando').modal('show');
        $('#form_arquivo').submit();
    });

    $('.btn-editar.btn-arquivo-acao, .limpar-edicao-nome').click(function() {
        $(this).parent().parent().parent().toggleClass('modo-edicao');
        $(this).parent().parent().parent().toggleClass('modo-visualizacao');
    });
});

function remove() {
    var index = $(this).attr('id');
    var files = Array.from($('#input_arquivo')[0].files);
    files.splice(index, 1);
    var fileList = new FileListItems(files);
    $('#input_arquivo')[0].files = fileList;
    $(this).parent().remove();
    $('.nome-arquivo li span').each(function(index) {
        $(this).attr('id', index);
    });
}

function FileListItems (files) {
    var b = new ClipboardEvent("").clipboardData || new DataTransfer();
    for (var i = 0, len = files.length; i<len; i++)
        b.items.add(files[i]);
    return b.files;
}

function ativar_exclusao_imagem() {
    $('.deletar-imagem-btn').click(function() {
        if (confirm("Tem certeza que deseja remover a imagem?")) {
            $('#modal_processando').modal('show');
            $('.deletar-imagem-' + $(this).attr('data-id')).submit();
        }
    });
}

function ativar_exclusao_arquivo(arquivo) {
    if (confirm('Tem certeza que deseja deletar ' + arquivo + '?')) {
        $('#modal_processando').modal('show');
        return true;
    } else
        return false;
}

function ativar_alteracao_arquivo() {
    $('#modal_processando').modal('show');
    return true;
}
