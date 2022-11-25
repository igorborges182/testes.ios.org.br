var unidade_selected = '';

jQuery(document).ready(function(){


    function detectMob() {
        let check = false;
        (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
        return check;
    }

    if(!detectMob()){
        jQuery(".gf_page_steps").closest("section").css('position', 'relative');
        jQuery(".gf_page_steps").closest("section").find("div").css('position', 'unset');
        jQuery(".gf_page_steps").detach().appendTo('.gform_body');
        jQuery(".gf_page_steps").css('position', 'fixed');

        jQuery(document).on( 'scroll', function(){
            if((jQuery(".gf_page_steps").closest("section").prev().outerHeight(true) + jQuery(".gf_page_steps").closest("section").outerHeight(true)) - 200 <= jQuery(document).scrollTop() + 200) {
                jQuery('.gf_page_steps').css({'position': 'absolute',
                                                'top': -((jQuery(".gf_page_steps").closest("section").prev().outerHeight(true) - jQuery(".gf_page_steps").closest("section").outerHeight(true)) + (jQuery(".elementor-location-footer").outerHeight(true)) - 248),
                                            });
            } else {
                jQuery('.gf_page_steps').css({'position': 'fixed',
                                                'top': '',
                                            });
            }
        });
    }

    jQuery('.page-id-1779 .elementor-col-33, .course, .unidade, .horario, .horario input').hide();

    jQuery('.unidade select').change(function(){
        jQuery('.unidade select option[value='+this.value+']').attr('selected','selected');
        unidade_selected = this.value;
    });

});

var nat_def = '';
jQuery(document).on('gform_page_loaded', function(event, form_id, current_page){
    jQuery(document).ready(function(){


        var curso = jQuery('.curso select').val();
        var unidade = unidade_selected;
        var horario = jQuery('.horario input').val();
        jQuery('.horario').hide();
        jQuery('.curso_indisponivel').hide();
        jQuery('.indisponivel').remove();
        jQuery('.curso select').attr("style", "pointer-events: none;");
        jQuery('.unidade select').attr("style", "pointer-events: none;");
        jQuery('.curso select').addClass("disabled");
        jQuery('.unidade select').addClass("disabled");
        jQuery('.gform_next_button').attr('disabled', 'disabled');
        jQuery('#next-step').attr('disabled', 'disabled');

        jQuery.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            data: { action: "lista_unidades", curso: curso },
            success: function(data){
                jQuery('.curso select').removeAttr('disabled');
                jQuery('.gform_next_button').removeAttr('disabled');
                jQuery('#next-step').removeAttr('disabled');
                if(data) {
                    jQuery('[name="input_1175"]').html(data);
                    jQuery('[name="input_1175"]').val(unidade);
                    jQuery('.unidade').show();

                    jQuery.ajax({
                        type: "POST",
                        url: "/wp-admin/admin-ajax.php",
                        data: { action: "lista_horarios", unidade: unidade, curso: curso },
                        success: function(data){
                            jQuery('.curso select').attr("style", "pointer-events: all;");
                            jQuery('.unidade select').attr("style", "pointer-events: all;");
                            jQuery('.curso select').removeClass("disabled");
                            jQuery('.unidade select').removeClass("disabled");
                            jQuery('.gform_next_button').removeAttr('disabled');
                            jQuery('#next-step').removeAttr('disabled');

                            if(data == 'curso_indisponivel'){
                                jQuery('.horario').hide();
                                jQuery('.curso_indisponivel').show();
                                jQuery('.gform_next_button').attr('disabled', 'disabled');
                                jQuery('#next-step').attr('disabled', 'disabled');
                            } else if(data) {
                                jQuery('.horario').append(data);
                                jQuery('.horario [data-course-id="'+curso+'"][data-periodo="'+horario+'"]').addClass("active");
                                jQuery('.horario input').val(horario);
                                jQuery('.horario').show();
                                jQuery('.curso_indisponivel').hide();
                            } else {
                                jQuery('.unidade').append('<h3 class="indisponivel">Não há horários disponíveis para essa unidade.</h3>');
                                jQuery('.curso_indisponivel').hide();
                            }
                        }
                    });

                } else {
                    jQuery('.curso').append('<h3 class="indisponivel">Esse curso não está disponível em nenhuma unidade.</h3>');
                }
            }
        });

        if(jQuery('#gf_step_2_4').hasClass('gf_step_active')){
            jQuery('#next-step').hide();
        } else {
            jQuery('#next-step').show();
        }
        
        if(jQuery('[name="input_41"]:checked').val() == 1){
            jQuery('.nat_deficiencia select').removeAttr('disabled');
            jQuery('.nat_deficiencia').show();
            jQuery('.nat_deficiencia').addClass('enabled');
        } else {
            jQuery('.nat_deficiencia').hide();
            jQuery('.nat_deficiencia').removeClass('enabled');
        }

        if(nat_def != ""){
            jQuery('.nat_deficiencia select option[value='+nat_def+']').attr('selected','selected');
        }
    });

    if(jQuery('.nat_deficiencia select').val() != ""){
        nat_def = jQuery('.nat_deficiencia select').val();
    }

});


jQuery(document).on('gform_post_render', function() {
    jQuery('.nat_deficiencia select').removeAttr('disabled');
    jQuery('.gf_page_steps').append('<button id="next-step">Próximo</button>');
    
    if(jQuery('#gform_page_2_6').css('display') !== 'none'){
        jQuery('#next-step').addClass( 'removedBUttonMobile' );
    }

    if(jQuery('[name="input_41"]:checked').val() == 1){
        jQuery('.nat_deficiencia select').removeAttr('disabled');
        jQuery('.nat_deficiencia').show();
        jQuery('.nat_deficiencia').addClass('enabled');
    } else {
        jQuery('.nat_deficiencia').hide();
        jQuery('.nat_deficiencia').removeClass('enabled');
    }

    jQuery('[name="input_41"]').on('change', function() {
        if(jQuery('[name="input_41"]:checked').val() == 1){
            jQuery('.nat_deficiencia select').removeAttr('disabled');
            jQuery('.nat_deficiencia').show();
            jQuery('.nat_deficiencia').addClass('enabled');
        } else {
            jQuery('.nat_deficiencia').hide();
            jQuery('.nat_deficiencia').removeClass('enabled');
        }
    });

    jQuery('#next-step').click(function(){
        jQuery('.gform_page').each(function(){
            if(jQuery(this).is(':visible')) {
                jQuery(this).children('.gform_page_footer input').trigger('click');
            }
        });
    });
    
    jQuery('.curso select').on('change', function() {
        jQuery('.unidade').hide();
        jQuery('.horario .course').remove();
        jQuery('.horario').hide();
        jQuery('.curso_indisponivel').hide();
        jQuery('.indisponivel').remove();
        jQuery('.curso select').attr('disabled', 'disabled');
        jQuery('.gform_next_button').attr('disabled', 'disabled');
        jQuery('#next-step').attr('disabled', 'disabled');
        jQuery.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            data: { action: "lista_unidades", curso: jQuery('.curso select').val() },
            success: function(data){
                jQuery('.curso select').removeAttr('disabled');
                jQuery('.gform_next_button').removeAttr('disabled');
                jQuery('#next-step').removeAttr('disabled');
                if(data) {
                    jQuery('[name="input_1175"]').html(data);
                    jQuery('.unidade').show();
                } else {
                    jQuery('.curso').append('<h3 class="indisponivel">Esse curso não está disponível em nenhuma unidade.</h3>');
                }
            }
        });

        return false;
    });

    jQuery('.unidade select').on('change', function() {
        jQuery('.horario .course').remove();
        jQuery('.horario').hide();
        jQuery('.curso_indisponivel').hide();
        jQuery('.indisponivel').remove();
        jQuery('.curso select').attr('disabled', 'disabled');
        jQuery('.unidade select').attr('disabled', 'disabled');
        jQuery('.gform_next_button').attr('disabled', 'disabled');
        jQuery('#next-step').attr('disabled', 'disabled');
        jQuery.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            data: { action: "lista_horarios", unidade: jQuery('.unidade select').val(), curso: jQuery('.curso select').val() },
            success: function(data){
                jQuery('.curso select').removeAttr('disabled');
                jQuery('.unidade select').removeAttr('disabled');
                jQuery('.gform_next_button').removeAttr('disabled');
                jQuery('#next-step').removeAttr('disabled');

                if(data == 'curso_indisponivel'){
                    jQuery('.horario').hide();
                    jQuery('.curso_indisponivel').show();
                    jQuery('.gform_next_button').attr('disabled', 'disabled');
                    jQuery('#next-step').attr('disabled', 'disabled');
                } else if(data) {
                    jQuery('.horario').append(data);
                    jQuery('.horario').show();
                    jQuery('.curso_indisponivel').hide();
                } else {
                    jQuery('.unidade').append('<h3 class="indisponivel">Não há horários disponíveis para essa unidade.</h3>');
                    jQuery('.curso_indisponivel').hide();
                }
            }
        });

        return false;
    });

    jQuery(document).on("click",".course", function (e) {
        jQuery('.course').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('[name="input_140"]').val(jQuery(this).data('periodo'));
    });

    function limpa_formulario_cep() {
        jQuery('[name="input_50"]').val("");
        jQuery('[name="input_54"]').val("");
        jQuery('[name="input_56"]').val("");
        jQuery('[name="input_131"] option').attr('selected',false);
    }

    jQuery('[name="input_49"]').focusout(function() {
        var cep = jQuery(this).val().replace(/\D/g, '');

        var validacep = /^[0-9]{8}$/;

        if (! validacep.test(cep)) {
            limpa_formulario_cep();
            alert("Formato de CEP inválido.");
        }

        if (cep !== '' && validacep.test(cep)) {
            jQuery('[name="input_50"]').val("...");
            jQuery('[name="input_54"]').val("...");
            jQuery('[name="input_56"]').val("...");
            jQuery('[name="input_131"] option').attr('selected',false);

            jQuery.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                if (("erro" in dados)) {
                    limpa_formulario_cep();
                    alert("CEP não encontrado.");
                }

                if (!('erro' in dados)) {
                    jQuery('[name="input_50"]').val(dados.logradouro);
                    jQuery('[name="input_54"]').val(dados.bairro);
                    jQuery('[name="input_56"]').val(dados.localidade);
                    jQuery('[name="input_131"] option[value=' + dados.uf + ']').attr('selected','selected');
                }
            });
        }
    });

    jQuery('#field_58 select').on('change', function() {
        jQuery('#field_58').removeClass('gf_left_third');

        if(jQuery('#field_58 select option:selected').val() > 0) {
            jQuery('#field_58').addClass('gf_left_third');
        }
    });

    jQuery('.gchoice_95_0').click(function() {
        jQuery('#field_95').addClass('gf_left_half');
    });

    jQuery('.gchoice_95_1').click(function() {
        jQuery('#field_95').removeClass('gf_left_half');
    });
    
    jQuery('#field_7000').appendTo('#gform_fields_4');
    
    jQuery('#choice_103_1, #choice_2_103_0').on('change',function(){
        var check = jQuery('#choice_2_103_1').prop('checked')
        if (check){
            alert('Revise a autorização do uso de informações por parte do IOS.');
            document.querySelector("#gform_submit_button_2").style.cssText = 'display:none !important';
        } else {
            document.querySelector("#gform_submit_button_2").style.cssText = 'display:block !important';
        }
    })


    jQuery(document).ready(function(){
        if(nat_def != "" && jQuery('.nat_deficiencia select').val() == ""){
            jQuery('.nat_deficiencia select').val(nat_def).change();
        }
    });


});

jQuery(document).ready(function(){
    // Owl Carousel
    jQuery(".owl-carousel").owlCarousel({
        loop:true,
        number:3,
        dots:true,
        autoWidth:true,
        autoHeight:true,
        mouseDrag:true,
        touchDrag:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:3
            }
        }
    });

    // jQuery Mask
    jQuery('#alg_open_price').mask("##0,00", {reverse: true});
    // Validações nos valores do produto open price
    jQuery('#alg_open_price').change(function(e) {
        if(parseInt(jQuery('#alg_open_price').val().split(',')[0]) < parseInt(jQuery('.alg_open_price-min-value').text())){
            alert("Valor abaixo do mínimo permitido.");
            jQuery('#alg_open_price').val(jQuery('.alg_open_price-min-value').text()+',00');
        } else if(parseInt(jQuery('#alg_open_price').val().split(',')[0]) > parseInt(jQuery('.alg_open_price-max-value').text())){
            alert("Valor acima do máximo permitido.");
            jQuery('#alg_open_price').val(jQuery('.alg_open_price-max-value').text()+',00');
        }
    });
    jQuery('#alg_open_price').on('keypress',function(e) {
        if(e.which == 13) {
            e.preventDefault();
            e.stopPropagation();
        }
    });

    // jQuery Mask
    jQuery('.telefone input').mask("(99) 9999-99999");
    jQuery('.telefone input').keyup(function(event) {
       if(jQuery(this).val().length > 14){
          jQuery('.telefone input').mask('(00) 00000-0009');
       } else {
          jQuery('.telefone input').mask('(00) 0000-00009');
       }
    });
    
});
