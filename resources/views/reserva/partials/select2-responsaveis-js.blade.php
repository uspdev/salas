<script>
    $(document).ready(function(){
        var addResponsavelUnidade = $('.add-responsavel-unidade');
        var addResponsavelExterno = $('.add-responsavel-externo');
        var $oSelect2 = addResponsavelUnidade.find(":input[name='responsaveis_unidade[]']");
        var selectExterno = addResponsavelExterno.find(":input[name='responsaveis_externo[]']");
        var dataAjax = $('#form-reserva').data("ajax");

        $('#btn-add-responsavel-externo-input').on('click', function(){
            if($(":input[name='responsaveis_externo[]']").length < 2){
                selectExterno.clone().prependTo(addResponsavelExterno).val("");
            }else{
                selectExterno.clone().prependTo(addResponsavelExterno).val("");
                $(this).css('display', 'none');
            }
        });

        $('[name=tipo_responsaveis]').on('change', function(e){
            switch (e.target.value) {
                case 'eu':
                    addResponsavelUnidade.css('display', 'none');
                    addResponsavelExterno.attr('style', 'display: none !important');
                    $oSelect2.attr('required', false);
                    selectExterno.attr('required', false);
                    break;
            
                case 'unidade': 
                    addResponsavelExterno.attr('style', 'display: none !important');
                    addResponsavelUnidade.css('display', 'block');
                    $oSelect2.attr('required', true);
                    selectExterno.attr('required', false);
                    break;

                case 'externo': 
                    addResponsavelExterno.attr('style', 'display: block !important');
                    addResponsavelUnidade.css('display', 'none');
                    $oSelect2.attr('required', false);
                    selectExterno.attr('required', true);
                    break;
            }
        });

        $oSelect2.select2({
            ajax: {
                url: dataAjax,
                dataType: 'json',
                delay: 1000
            },
            maximumSelectionLength: 3,
            placeholder: function() {
                $(this).data('placeholder');
            },
            dropdownParent: addResponsavelUnidade,
            minimumInputLength: 4,
            theme: 'bootstrap4',
            width: 'resolve',
            language: 'pt-BR'
        });

        switch ('{{old('tipo_responsaveis', $reserva->tipo_responsaveis)}}') {
            case 'eu':
                addResponsavelUnidade.css('display', 'none');
                addResponsavelExterno.attr('style', 'display: none !important');
                break;
        
            case 'unidade':
                addResponsavelExterno.attr('style', 'display: none !important');
                $oSelect2.attr('required', true);
                break;

            case 'externo':
                addResponsavelUnidade.css('display', 'none');
                selectExterno.attr('required', true);
                break;
        }
    });
</script>