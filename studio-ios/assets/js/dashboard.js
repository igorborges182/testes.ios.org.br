jQuery(document).ready(function(){
  jQuery('.filterButton').on('click', function() {
      jQuery('#modalFilter').modal('toggle');
  });

  jQuery('.approve-all').on('click', function(){
    jQuery('.total-candidates').text(parseInt(jQuery('.all-candidates-qty').text()) - parseInt(jQuery('.approved-candidates-qty').text()));
    jQuery('#modalApprove').modal('toggle');
  });

  jQuery('.disapprove-all').on('click', function(){
    jQuery('.total-candidates').text(parseInt(jQuery('.approved-candidates-qty').text()));
    jQuery('#modalDisapprove').modal('toggle');
  });

  jQuery('.unlock-imports').on('click', function(){
    jQuery('#modalUnlockImports').modal('toggle');
  });

  jQuery(document).on("change",".approve_candidate", function (e) {
    showLoading(true);
    var checkbox = jQuery(this);
    if(checkbox.is(':checked')){
      var entry_id = checkbox.closest('tr').attr('data-entry-id');
      jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').addClass('active');
      jQuery('.DTFC_LeftBodyLiner [data-entry-id="'+entry_id+'"]').addClass('active');
      jQuery.ajax({
        type: "POST",
        url: wp.ajax_url,
        dataType: 'json',
        data: { action:'approve_candidate', entry_id: entry_id, nonce: wp.nonce },
        success: function(response){
          if(response.success != true) {
            checkbox.prop('checked', false);
            checkbox.closest('tr').removeClass('active');
            jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').removeClass('active');
            jQuery('.DTFC_LeftBodyLiner [data-entry-id="'+entry_id+'"]').removeClass('active');
          } else {
            jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').find('.approve_candidate_second_part').prop('checked', false);
            jQuery('.DTFC_LeftBodyLiner [data-entry-id="'+entry_id+'"]').find('.approve_candidate_second_part').prop('checked', false);
            jQuery('.approved-candidates-qty').text(parseInt(jQuery('.approved-candidates-qty').text())+1);
            jQuery('.total-candidates').text(parseInt(jQuery('.approved-candidates-qty').text()));
            jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').attr('data-approved', true);
            jQuery('.DTFC_LeftBodyLiner [data-entry-id="'+entry_id+'"]').attr('data-approved', true);
          }
          showLoading(false);
        }
      });
    } else {
      var entry_id = checkbox.closest('tr').attr('data-entry-id');
      jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').removeClass('active');
      jQuery('.DTFC_LeftBodyLiner [data-entry-id="'+entry_id+'"]').removeClass('active');
      jQuery.ajax({
        type: "POST",
        url: wp.ajax_url,
        dataType: 'json',
        data: { action:'disapprove_candidate', entry_id: entry_id, nonce: wp.nonce },
        success: function(response){
          if(response.success != true) {
            checkbox.prop('checked', true);
            checkbox.closest('tr').addClass('active');
            jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').addClass('active');
            jQuery('.DTFC_LeftBodyLiner [data-entry-id="'+entry_id+'"]').addClass('active');
          } else {
            jQuery('.approved-candidates-qty').text(parseInt(jQuery('.approved-candidates-qty').text())-1);
            if(jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').find('.approve_candidate_second_part').is(':checked')){
              jQuery('.approved-candidates-second-part-qty').text(parseInt(jQuery('.approved-candidates-second-part-qty').text())-1);
            }
            jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').find('.approve_candidate_second_part').prop('checked', false);
            jQuery('.DTFC_LeftBodyLiner [data-entry-id="'+entry_id+'"]').find('.approve_candidate_second_part').prop('checked', false);
            jQuery('.total-candidates').text(parseInt(jQuery('.approved-candidates-qty').text()));
            jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').attr('data-approved', false);
            jQuery('.DTFC_LeftBodyLiner [data-entry-id="'+entry_id+'"]').attr('data-approved', false);
            jQuery('.approve-all').removeClass('d-none');
            jQuery('.disapprove-all').addClass('d-none');
          }
          showLoading(false);
        }
      });
    }
  });

  jQuery(document).on("change",".approve_candidate_second_part", function (e) {
    showLoading(true);
    var checkbox = jQuery(this);
    if(checkbox.is(':checked')){
      var entry_id = checkbox.closest('tr').attr('data-entry-id');
      var approved = checkbox.closest('tr').attr('data-approved');
      if(jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').find('.approve_candidate').is(':checked') == false){
        jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').addClass('active');
        jQuery('.DTFC_LeftBodyLiner [data-entry-id="'+entry_id+'"]').addClass('active');
      }
      jQuery.ajax({
        type: "POST",
        url: wp.ajax_url,
        dataType: 'json',
        data: { action:'approve_candidate_second_part', entry_id: entry_id, approved: approved, nonce: wp.nonce },
        success: function(response){
          if(response.success != true) {
            checkbox.prop('checked', false);
            checkbox.closest('tr').removeClass('active');
            if(jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').find('.approve_candidate').is(':checked') == false){
              jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').removeClass('active');
              jQuery('.DTFC_LeftBodyLiner [data-entry-id="'+entry_id+'"]').removeClass('active');
            }
          } else {
            jQuery('.approved-candidates-second-part-qty').text(parseInt(jQuery('.approved-candidates-second-part-qty').text())+1);
            if(jQuery('.DTFC_LeftBodyLiner [data-entry-id="'+entry_id+'"]').find('.approve_candidate').is(':checked') == false){
              jQuery('.approved-candidates-qty').text(parseInt(jQuery('.approved-candidates-qty').text())+1);
            }
            jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').find('.approve_candidate').prop('checked', true);
            jQuery('.DTFC_LeftBodyLiner [data-entry-id="'+entry_id+'"]').find('.approve_candidate').prop('checked', true);
            jQuery('.total-candidates').text(parseInt(jQuery('.approved-candidates-qty').text() + parseInt(jQuery('.approved-candidates-second-part-qty').text())));
            jQuery('.dataTables_scroll [data-entry-id="'+entry_id+'"]').attr('data-approved', true);
            jQuery('.DTFC_LeftBodyLiner [data-entry-id="'+entry_id+'"]').attr('data-approved', true);
          }
          showLoading(false);
        }
      });
    } else {
      var entry_id = checkbox.closest('tr').attr('data-entry-id');
      var approved = checkbox.closest('tr').attr('data-approved');
      jQuery('[data-entry-id="'+entry_id+'"]').find('.approve_candidate').prop('checked', true);
      jQuery.ajax({
        type: "POST",
        url: wp.ajax_url,
        dataType: 'json',
        data: { action:'disapprove_candidate_second_part', entry_id: entry_id, approved: approved, nonce: wp.nonce },
        success: function(response){
          if(response.success != true) {
            checkbox.prop('checked', true);
            checkbox.closest('tr').addClass('active');
          } else {
            jQuery('.approved-candidates-second-part-qty').text(parseInt(jQuery('.approved-candidates-second-part-qty').text())-1);
            jQuery('.total-candidates').text(parseInt(jQuery('.approved-candidates-qty').text() + parseInt(jQuery('.approved-candidates-second-part-qty').text())));
          }
          showLoading(false);
        }
      });
    }
  });

  jQuery('#modalApprove .approve-button .btn').on('click', function(){
    var form_filters = jQuery('#modalFilter');
    var form_search = jQuery('#form-busca');
    var filters = parseFilters(form_filters, form_search);

    jQuery.ajax({
      type: "POST",
      url: wp.ajax_url,
      dataType: 'json',
      data: { action:'approve_all_candidates', filters: filters, nonce: wp.nonce },
      success: function(response){
        if(response.success == true) {
          jQuery('.approve_candidate').closest('tr').attr('data-approved', true);
          jQuery('.approve_candidate').prop('checked', true);
          jQuery('.approve_candidate').closest('tr').addClass('active');
          jQuery('.approved-candidates-qty').text(parseInt(jQuery('.all-candidates-qty').text()));
          jQuery('.total-candidates').text(parseInt(jQuery('.approved-candidates-qty').text()));
          jQuery('.approve-all').addClass('d-none');
          jQuery('.disapprove-all').removeClass('d-none');
          showLoading(false);
        }
      },
      beforeSend: function() {
        jQuery('#modalApprove .close-modal').trigger('click');
        showLoading(true);
      }
    });
  });

  jQuery('#modalDisapprove .approve-button .btn').on('click', function(){
    var form_filters = jQuery('#modalFilter');
    var form_search = jQuery('#form-busca');
    var filters = parseFilters(form_filters, form_search);

    jQuery.ajax({
      type: "POST",
      url: wp.ajax_url,
      dataType: 'json',
      data: { action:'disapprove_all_candidates', filters: filters, nonce: wp.nonce },
      success: function(response){
        if(response.success == true) {
          jQuery('.approve_candidate').closest('tr').attr('data-approved', false);
          jQuery('.approve_candidate').prop('checked', false);
          jQuery('.approve_candidate').closest('tr').removeClass('active');
          jQuery('.approve_candidate_second_part').prop('checked', false);
          jQuery('.approved-candidates-qty').text(0);
          jQuery('.total-candidates').text(parseInt(jQuery('.approved-candidates-qty').text()));
          jQuery('.approve-all').removeClass('d-none');
          jQuery('.disapprove-all').addClass('d-none');
          showLoading(false);
        }
      },
      beforeSend: function() {
        jQuery('#modalDisapprove .close-modal').trigger('click');
        showLoading(true);
      }
    });
  });

  jQuery('#modalApproveSecondPart .approve-button .btn').on('click', function(){
    var form_filters = jQuery('#modalFilter');
    var form_search = jQuery('#form-busca');
    var filters = parseFilters(form_filters, form_search);

    jQuery.ajax({
      type: "POST",
      url: wp.ajax_url,
      dataType: 'json',
      data: { action:'approve_all_candidates_second_part', filters: filters, nonce: wp.nonce },
      success: function(response){
        if(response.success == true) {
          jQuery('.approve_candidate').closest('tr').attr('data-approved', true);
          jQuery('.approve_candidate').prop('checked', true);
          jQuery('.approve_candidate').closest('tr').addClass('active');
          jQuery('.approve_candidate_second_part').prop('checked', true);
          jQuery('.approved-candidates-second-part-qty').text(parseInt(jQuery('.all-candidates-second-part-qty').text()));
          jQuery('.approved-candidates-qty').text(parseInt(jQuery('.all-candidates-qty').text()));
          jQuery('.total-candidates').text(parseInt(jQuery('.approved-candidates-qty').text()) + parseInt(jQuery('.approved-candidates-second-part-qty').text()));
          jQuery('.approve-all-second-part').addClass('d-none');
          jQuery('.disapprove-all').removeClass('d-none');
          showLoading(false);
        }
      },
      beforeSend: function() {
        jQuery('#modalApproveSecondPart .close-modal').trigger('click');
        showLoading(true);
      }
    });
  });

  jQuery('#modalDisapproveSecondPart .approve-button .btn').on('click', function(){
    var form_filters = jQuery('#modalFilter');
    var form_search = jQuery('#form-busca');
    var filters = parseFilters(form_filters, form_search);

    jQuery.ajax({
      type: "POST",
      url: wp.ajax_url,
      dataType: 'json',
      data: { action:'disapprove_all_candidates_second_part', filters: filters, nonce: wp.nonce },
      success: function(response){
        if(response.success == true) {
          jQuery('.approve_candidate').closest('tr').attr('data-approved', false);
          jQuery('.approve_candidate').prop('checked', false);
          jQuery('.approve_candidate').closest('tr').removeClass('active');
          jQuery('.approve_candidate_second_part').prop('checked', false);
          jQuery('.approved-candidates-second-part-qty').text(0);
          jQuery('.approved-candidates-qty').text(0);
          jQuery('.total-candidates').text(parseInt(jQuery('.approved-candidates-qty').text()) + parseInt(jQuery('.approved-candidates-second-part-qty').text()));
          jQuery('.approve-all-second-part').removeClass('d-none');
          jQuery('.disapprove-all').addClass('d-none');
          showLoading(false);
        }
      },
      beforeSend: function() {
        jQuery('#modalDisapproveSecondPart .close-modal').trigger('click');
        showLoading(true);
      }
    });
  });

  jQuery('#modalUnlockImports .unlock-imports-button .btn').on('click', function(){
    var form_filters = jQuery('#modalFilter');
    var form_search = jQuery('#form-busca');
    var filters = parseFilters(form_filters, form_search);

    jQuery.ajax({
      type: "POST",
      url: wp.ajax_url,
      dataType: 'json',
      data: { action:'unlock_imports_candidates', filters: filters, nonce: wp.nonce },
      success: function(response){
        if(response.success == true) {
          showLoading(false);
        }
      },
      beforeSend: function() {
        jQuery('#modalUnlockImports .close-modal').trigger('click');
        showLoading(true);
      }
    });
  });


  jQuery.fn.dataTable.moment('DD/MM/YYYY');

  if(jQuery('.dashboard-table tbody tr').length > 0){
    var tableOptions = {
      "language": {
        "decimal": ",",
        "thousands": "."
      },
      "pageLength": 25,
      "searching": false,
      "paging": true,
      "info": true,
      "lengthMenu": [[25, 10, 50, 100, 200, -1], [25, 10, 50, 100, 200, "Todos"]],
      "language": {
          "url": "//cdn.datatables.net/plug-ins/1.11.1/i18n/pt_br.json"
      },
      "fixedColumns":   {
          "leftColumns": "2"
      },
      "scrollCollapse": true,
      "scrollX":        true
    };
  } else {
    var tableOptions = {
      "language": {
        "decimal": ",",
        "thousands": "."
      },
      "pageLength": 25,
      "searching": false,
      "paging": true,
      "info": true,
      "lengthMenu": [[25, 10, 50, 100, 200, -1], [25, 10, 50, 100, 200, "Todos"]],
      "language": {
          "url": "//cdn.datatables.net/plug-ins/1.11.1/i18n/pt_br.json"
      }
    };
  }
  
  let table = jQuery('.dashboard-table').DataTable(tableOptions);

  let filterColumn = jQuery('.table__columns .filter-column');
  filterColumn.select2({
      placeholder: "Filtre as colunas ("+eval(parseInt(jQuery('.table__columns .filter-column > option:not(:selected)').length)-1)+")"
  });

  filterColumn.on('change', function() {
      if(jQuery(this).val() && jQuery(this).val().length) {
          jQuery(this).next('.select2-container').find('.select2-search--inline textarea.select2-search__field').attr('placeholder', 'Filtre as colunas ('+eval(parseInt(jQuery('.table__columns .filter-column > option:not(:selected)').length)-1)+')');
      }
  });
  
  filterColumn.on('select2:select', function(e){
      toggleColumn(e.params.data.element.dataset.column);
  });

  filterColumn.on('select2:unselect', function(e){
      toggleColumn(e.params.data.element.dataset.column);
  });

  jQuery('.exportTable').on('click', function () {
      fnExcelReport();
  })
  
  jQuery('.filters__selects .select-unidade').select2({
      placeholder: "SELECIONE AS UNIDADES",
      minimumResultsForSearch: Infinity,
      width: 'resolve'
  });

  jQuery('.filters__selects .select-unidade').on('change', function() {
      if(jQuery(this).val() && jQuery(this).val().length) {
          jQuery(this).next('.select2-container').find('.select2-search--inline textarea.select2-search__field').attr('placeholder', 'SELECIONE AS UNIDADES');
      }
  });
  jQuery('.filters__selects .select-unidade > option').prop("selected",true);

  jQuery('.filters__selects .select-curso').select2({
      placeholder: "SELECIONE OS CURSOS",
      minimumResultsForSearch: Infinity,
      width: 'resolve'
  });

  jQuery(document).on("change",".filters__selects .select-curso", function (e) {
      if(jQuery(this).val() && jQuery(this).val().length) {
          jQuery(this).next('.select2-container').find('.select2-search--inline textarea.select2-search__field').attr('placeholder', 'SELECIONE OS CURSOS');
      }
  });
  jQuery('.filters__selects .select-curso > option').prop("selected",true);

  jQuery('.money-input').mask("#.##0,00", {reverse: true});


  function toggleColumn(columnPosition) {
    var table = new jQuery.fn.dataTable.Api( '.dashboard-table' );

    let position = columnPosition;
    
    // Get the column API object
    let column = table.column( position );

    // Toggle the visibility
    column.visible( ! column.visible() );
  }
  
  jQuery('#modalFilter #apply__filters, #form-busca #busca-btn').on('click', function() {
    initFilters();
  });

  jQuery('#modalFilter #apply__filters, #form-busca #busca-box').on('keypress', function(e) {
    if(e.which == 13) {
      initFilters();
    }
  });

  jQuery('#unidade, #curso').on('change', function() {
    initFilters();
  });

  jQuery('.filter-remove').on('click', function() {
    var form_filters = jQuery('#modalFilter');
    var form_search = jQuery('#form-busca');

    if(jQuery(form_filters).find("input[name='"+jQuery(this).attr('data-filter')+"']").is(':checkbox') || jQuery(form_filters).find("input[name='"+jQuery(this).attr('data-filter')+"']").is(':radio')){
      jQuery(form_filters).find("input[name='"+jQuery(this).attr('data-filter')+"']:checked").each(function() {
        jQuery(this).prop('checked', false);
      });
    } else if(jQuery(form_filters).find("select[name='"+jQuery(this).attr('data-filter')+"']").is('select')){
      jQuery(form_filters).find("select[name='"+jQuery(this).attr('data-filter')+"'] option").each(function() {
        jQuery(this).prop('selected', false);
      });
      jQuery(form_filters).find("select[name='"+jQuery(this).attr('data-filter')+"']").each(function() {
        jQuery(this).select2('destroy').val("").select2({
          placeholder: "Escolha",
          allowClear: true,
          width: '100%',
          theme: "classic",
          multiple: true
        });
      });
    } else {
      jQuery(form_filters).find("input[name='"+jQuery(this).attr('data-filter')+"']").each(function() {
        jQuery(this).val('');
      });
    }

    initFilters();
  });
});

function initFilters() {
  var form_filters = jQuery('#modalFilter');
  var form_search = jQuery('#form-busca');
  filterDashboard(form_filters, form_search);
}

function parseFilters(form_filters, form_search) {
  var aprovado = '';
  var aprovado_second_part = '';
  var data_cadastro = [];
  var idade = [];
  var etnia = [];
  var escolaridade = [];
  var deficiencia = [];
  var cronograma_vacinal = [];
  var servico_social = [];
  var faixa_renda = [];
  var encaminhamento_social = [];
  var nome_servico = [];
  var curso = [];
  var unidade = [];
  var busca = '';
  
  busca = jQuery(form_search).find("#busca-box").val();

  if(jQuery(form_filters).find("input[name='aprovado']:checked").length > 1){
    aprovado = 'todos';
  } else {
    jQuery(form_filters).find("input[name='aprovado']:checked").each(function() {
        aprovado = jQuery(this).val();
    });
  }

  if(jQuery(form_filters).find("input[name='aprovado_second_part']:checked").length > 1){
    aprovado_second_part = 'todos';
  } else {
    jQuery(form_filters).find("input[name='aprovado_second_part']:checked").each(function() {
        aprovado_second_part = jQuery(this).val();
    });
  }

  jQuery(form_filters).find("input[name='data_cadastro']").each(function() {
      data_cadastro.push(jQuery(this).val());
  });

  jQuery(form_filters).find("input[name='idade']").each(function() {
      idade.push(jQuery(this).val());
  });

  jQuery(form_filters).find("input[name='etnia']:checked").each(function() {
      etnia.push(jQuery(this).val());
  });

  jQuery(form_filters).find("input[name='escolaridade']:checked").each(function() {
      escolaridade.push(jQuery(this).val());
  });

  jQuery(form_filters).find("input[name='deficiencia']:checked").each(function() {
      deficiencia.push(jQuery(this).val());
  });

  jQuery(form_filters).find("input[name='encaminhamento_social']:checked").each(function() {
      encaminhamento_social.push(jQuery(this).val());
  });

  jQuery(form_filters).find("input[name='servico_social']:checked").each(function() {
    servico_social.push(jQuery(this).val());
});

jQuery(form_filters).find("input[name='nome_servico']:checked").each(function() {
  nome_servico.push(jQuery(this).val());
});

  jQuery("#unidade option:not(:selected)").each(function() {
      unidade.push(jQuery(this).val());
  });

  jQuery("#curso option:not(:selected)").each(function() {
      curso.push(jQuery(this).val());
  });

  jQuery(form_filters).find("input[name='cronograma_vacinal']:checked").each(function() {
      cronograma_vacinal.push(jQuery(this).val());
  });

  jQuery(form_filters).find("input[name='faixa_renda']:checked").each(function() {
      faixa_renda.push(jQuery(this).val());
  });

  return {
    'busca': busca,
    'aprovado': aprovado,
    'aprovado_second_part': aprovado_second_part,
    'data_cadastro': data_cadastro,
    'idade': idade,
    'etnia': etnia,
    'escolaridade': escolaridade,
    'deficiencia': deficiencia,
    'cronograma_vacinal': cronograma_vacinal,
    'servico_social': servico_social,
    'faixa_renda': faixa_renda,
    'encaminhamento_social': encaminhamento_social,
    'nome_servico': nome_servico,
    'curso': curso,
    'unidade': unidade
  };
}

function filterDashboard(form_filters, form_search) {

  var filters = parseFilters(form_filters, form_search);

  var filtersData = {
    action: 'filter_dashboard',
    nonce: wp.nonce,
    filters: filters,
    excel: false,
  }

  jQuery.ajax({
    url: wp.ajax_url,
    type: 'POST',
    data: filtersData,
    dataType: 'json',
    success: function(response){
      if(response.data.candidates) {

        var data = response.data;

        setFiltersSelections(form_filters, filters);

        fillTable(data.candidates, filters);

        jQuery('.approved-candidates-qty').text(data.approved_candidates);
        jQuery('.approved-candidates-second-part-qty').text(data.approved_candidates_second_part);
        jQuery('.all-candidates-qty').text(data.candidates.length);

        if(data.approved_candidates <= data.candidates.length && data.candidates.length != 0){
          jQuery('.approve-all').addClass('d-none');
          jQuery('.disapprove-all').removeClass('d-none');
        } else {
          jQuery('.approve-all').removeClass('d-none');
          jQuery('.disapprove-all').addClass('d-none');
        }

      }

      showLoading(false);
    },
    beforeSend: function() {
      jQuery('#modalFilter .close-modal').trigger('click');
      showLoading(true);
    }
  });
}

function dataFormatada(date){
  let formmated = date.split('-');
  return formmated[2]+'/'+formmated[1]+'/'+formmated[0];
}

function setFiltersSelections(form_filters, filters){
  var filters_qtd = 0;

  jQuery('.filter-data_cadastro').addClass('d-none').find('.filter-value').html('');
  jQuery('.filter-idade').addClass('d-none').find('.filter-value').html('');
  jQuery('.filter-aprovado').addClass('d-none').find('.filter-value').html('');
  jQuery('.filter-aprovado_second_part').addClass('d-none').find('.filter-value').html('');
  jQuery('.filter-etnia').addClass('d-none').find('.filter-value').html('');
  jQuery('.filter-cronograma_vacinal').addClass('d-none').find('.filter-value').html('');
  jQuery('.filter-servico_social').addClass('d-none').find('.filter-value').html('');
  jQuery('.filter-encaminhamento_social').addClass('d-none').find('.filter-value').html('');
  jQuery('.filter-nome_servico').addClass('d-none').find('.filter-value').html('');
  jQuery('.filter-faixa_renda').addClass('d-none').find('.filter-value').html('');
  jQuery('.filter-escolaridade').addClass('d-none').find('.filter-value').html('');
  jQuery('.filter-deficiencia').addClass('d-none').find('.filter-value').html('');

  if(filters.data_cadastro[0] && !filters.data_cadastro[1]){
    filters_qtd++;
    jQuery('.filter-data_cadastro').removeClass('d-none').find('.filter-value').html('a partir de '+dataFormatada(filters.data_cadastro[0]));
  } else if(filters.data_cadastro[0] && filters.data_cadastro[1]){
    filters_qtd++;
    jQuery('.filter-data_cadastro').removeClass('d-none').find('.filter-value').html(dataFormatada(filters.data_cadastro[0]) +' a '+ dataFormatada(filters.data_cadastro[1]));
  } else if(!filters.data_cadastro[0] && filters.data_cadastro[1]){
    filters_qtd++;
    jQuery('.filter-data_cadastro').removeClass('d-none').find('.filter-value').html('até '+ dataFormatada(filters.data_cadastro[1]));
  }

  if(filters.idade[0] && !filters.idade[1]){
    filters_qtd++;
    jQuery('.filter-idade').removeClass('d-none').find('.filter-value').html('a partir de '+filters.idade[0] +' anos');
  } else if(filters.idade[0] && filters.idade[1]){
    filters_qtd++;
    jQuery('.filter-idade').removeClass('d-none').find('.filter-value').html(filters.idade[0] +' anos a '+ filters.idade[1] + ' anos');
  } else if(!filters.idade[0] && filters.idade[1]){
    filters_qtd++;
    jQuery('.filter-idade').removeClass('d-none').find('.filter-value').html('até '+ filters.idade[1] + ' anos');
  }

  if(filters.aprovado == 'todos'){
    filters_qtd++;
    jQuery('.filter-aprovado').removeClass('d-none').find('.filter-value').html('Sim e Não');
  } else if(filters.aprovado == 'true' || filters.aprovado == 'false'){
    filters_qtd++;
    jQuery('.filter-aprovado').removeClass('d-none').find('.filter-value').html(filters.aprovado == 'true' ? 'Sim' : 'Não');
  }

  if(filters.aprovado_second_part == 'todos'){
    filters_qtd++;
    jQuery('.filter-aprovado_second_part').removeClass('d-none').find('.filter-value').html('Sim e Não');
  } else if(filters.aprovado_second_part == 'true' || filters.aprovado_second_part == 'false'){
    filters_qtd++;
    jQuery('.filter-aprovado_second_part').removeClass('d-none').find('.filter-value').html(filters.aprovado_second_part == 'true' ? 'Sim' : 'Não');
  }

  if(filters.etnia.length > 0){
    filters_qtd++;
    var etnia = [];
    jQuery(form_filters).find("input[name='etnia']:checked").each(function() {
      if(filters.etnia.includes(jQuery(this).val())){
        etnia.push(jQuery(this).next().text());
      }
    });
    jQuery('.filter-etnia').removeClass('d-none').find('.filter-value').html(etnia.join(', '));
  }

  if(filters.escolaridade.length > 0){
    filters_qtd++;
    var escolaridade = [];
    jQuery(form_filters).find("input[name='escolaridade']:checked").each(function() {
      if(filters.escolaridade.includes(jQuery(this).val())){
        escolaridade.push(jQuery(this).next().text());
      }
    });
    jQuery('.filter-escolaridade').removeClass('d-none').find('.filter-value').html(escolaridade.join(', '));
  }

  if(filters.deficiencia.length > 0){
    filters_qtd++;
    var deficiencia = [];
    jQuery(form_filters).find("input[name='deficiencia']:checked").each(function() {
      if(filters.deficiencia.includes(jQuery(this).val())){
        deficiencia.push(jQuery(this).next().text());
      }
    });
    jQuery('.filter-deficiencia').removeClass('d-none').find('.filter-value').html(deficiencia.join(', '));
  }

  if(filters.servico_social.length > 0){
    filters_qtd++;
    var servico_social = [];
    jQuery(form_filters).find("input[name='servico_social']:checked").each(function() {
      if(filters.servico_social.includes(jQuery(this).val())){
        servico_social.push(jQuery(this).next().text());
      }
    });
    jQuery('.filter-servico_social').removeClass('d-none').find('.filter-value').html(servico_social.join(', '));
  }

  if(filters.encaminhamento_social.length > 0){
    filters_qtd++;
    var encaminhamento_social = [];
    jQuery(form_filters).find("input[name='encaminhamento_social']:checked").each(function() {
      if(filters.encaminhamento_social.includes(jQuery(this).val())){
        encaminhamento_social.push(jQuery(this).next().text());
      }
    });
    jQuery('.filter-encaminhamento_social').removeClass('d-none').find('.filter-value').html(encaminhamento_social.join(', '));
  }

  if(filters.nome_servico.length > 0){
    filters_qtd++;
    var nome_servico = [];
    jQuery(form_filters).find("input[name='nome_servico']:checked").each(function() {
      if(filters.nome_servico.includes(jQuery(this).val())){
        nome_servico.push(jQuery(this).next().text());
      }
    });
    jQuery('.filter-nome_servico').removeClass('d-none').find('.filter-value').html(nome_servico.join(', '));
  }

  if(filters.cronograma_vacinal.length > 0){
    filters_qtd++;
    var cronograma_vacinal = [];
    jQuery(form_filters).find("input[name='cronograma_vacinal']:checked").each(function() {
      if(filters.cronograma_vacinal.includes(jQuery(this).val())){
        cronograma_vacinal.push(jQuery(this).next().text());
      }
    });
    jQuery('.filter-cronograma_vacinal').removeClass('d-none').find('.filter-value').html(cronograma_vacinal.join(', '));
  }

  if(filters.faixa_renda.length > 0){
    filters_qtd++;
    var faixa_renda = [];
    jQuery(form_filters).find("input[name='faixa_renda']:checked").each(function() {
      if(filters.faixa_renda.includes(jQuery(this).val())){
        faixa_renda.push(jQuery(this).next().text());
      }
    });
    jQuery('.filter-faixa_renda').removeClass('d-none').find('.filter-value').html(faixa_renda.join(', '));
  }

  if(filters_qtd == 0){
    jQuery('.filtros').addClass('d-none');
  } else {
    jQuery('.filtros').removeClass('d-none');
  }

}

function fillTable(candidates, filters) {
  
  jQuery('.dashboard-table').DataTable().destroy();

  jQuery('.dashboard-table tbody').remove();

  var html = '<tbody>';
  var cursos = new Array();
  for(var i in candidates) {
    var candidate = candidates[i];

    html += '<tr '+(candidate.aprovado ? 'class="active"' : '')+' data-entry-id="'+candidate.id+'" data-approved="'+(candidate.aprovado ? 'true' : 'false') +'">';
    // <tr class="<?= ($candidate['aprovado']) ? 'active' : '' ?>">

      for(var j in candidate) {
        var field = candidate[j];

        if(j == 'id' || j == 'importado' || j == 'aprovado_para_importar' || j == 'professor_que_aprovou' || j == 'data_aprovacao') {
          continue;
        }

        if(j=='curso'){
          cursos.indexOf(field) === -1 ? cursos.push(field) : '';
        }

        if(j == 'aprovado') {
          html += '<td>';
            html += '<label class="toggle" style="font-size:12px">';
              html += '<input type="checkbox" class="approve_candidate" '+ (candidate.aprovado ? 'checked' : '')+' />';
              html += '<span class="font-weight-bold" data-on="Sim" data-off="Não"></span>';
            html += '</label>';
          html += '</td>';
        } else if(j == 'aprovado_second_part') {
          html += '<td>';
            html += '<label class="toggle" style="font-size:12px">';
              html += '<input type="checkbox" class="approve_candidate_second_part" '+ (candidate.aprovado_second_part ? 'checked' : '')+' />';
              html += '<span class="font-weight-bold" data-on="Sim" data-off="Não"></span>';
            html += '</label>';
          html += '</td>';
        } else {
          html += '<td>'+field+'</td>'
        }
      }

    html += '</tr>';
  }
  html += '</tbody>';

  if(filters['curso'].length == 0){
    cursos.sort();
    jQuery(".filters__selects .select-curso option").remove();
    for(var i in cursos) {
      jQuery(".filters__selects .select-curso").append('<option value="'+cursos[i]+'">'+cursos[i]+'</option>');
    }
    jQuery('.filters__selects .select-curso').select2('destroy').val("").select2({
        placeholder: "SELECIONE OS CURSOS",
        minimumResultsForSearch: Infinity,
        width: 'resolve'
    });
    jQuery('.filters__selects .select-curso > option').prop("selected",true);
  }

  jQuery('.dashboard-table').append(html);

  if(candidates.length > 0){
    var tableOptions = {
      "language": {
        "decimal": ",",
        "thousands": "."
      },
      "pageLength": 25,
      "searching": false,
      "paging": true,
      "info": true,
      "lengthMenu": [[25, 10, 50, 100, 200, -1], [25, 10, 50, 100, 200, "Todos"]],
      "language": {
          "url": "//cdn.datatables.net/plug-ins/1.11.1/i18n/pt_br.json"
      },
      "fixedColumns": {
          "leftColumns": 2
      },
      "scrollCollapse": true,
      "scrollX":        true
    };
  } else {
    var tableOptions = {
      "language": {
        "decimal": ",",
        "thousands": "."
      },
      "pageLength": 25,
      "searching": false,
      "paging": true,
      "info": true,
      "lengthMenu": [[25, 10, 50, 100, 200, -1], [25, 10, 50, 100, 200, "Todos"]],
      "language": {
          "url": "//cdn.datatables.net/plug-ins/1.11.1/i18n/pt_br.json"
      }
    };
  }

  let table = jQuery('.dashboard-table').DataTable(tableOptions);
  
  jQuery('.table__columns .filter-column > option').removeAttr("selected");
}

function showLoading(status, mensagem = 'Carregando...') {
  jQuery('#loading span').html(mensagem);

  if (status) {
    jQuery('input').prop("disabled", true);
    jQuery('button').prop("disabled", true);
    return jQuery('#loading').show().fadeIn();
  }

  jQuery('input').prop("disabled", false);
  jQuery('button').prop("disabled", false);
  return jQuery('#loading').hide().fadeOut();
}

function fnExcelReport()
{
  var form_filters = jQuery('#modalFilter');
  var form_search = jQuery('#form-busca');
  var filters = parseFilters(form_filters, form_search);

  var filtersData = {
    action: 'filter_dashboard',
    nonce: wp.nonce,
    filters: filters,
    excel: true,
  }

  jQuery.ajax({
    url: wp.ajax_url,
    type: 'POST',
    data: filtersData,
    dataType: 'json',
    success: function(response){
      if(response.data.candidates) {
        var data = response.data;

        if(data.candidates[0]){
          var fileName = 'Candidatos';

          const jsonKeys = Object.keys(data.candidates[0]);

          let objectMaxLength = []; 
          for (let i = 0; i < data.candidates.length; i++) {
            let value = data.candidates[i];
            for (let j = 0; j < jsonKeys.length; j++) {
              if (typeof value[jsonKeys[j]] == "number") {
                objectMaxLength[j] = 10;
              } else {

                const l = value[jsonKeys[j]] ? value[jsonKeys[j]].length : 0;

                objectMaxLength[j] =
                  objectMaxLength[j] >= l
                    ? objectMaxLength[j]
                    : l;
              }
            }

            let key = jsonKeys;
            for (let j = 0; j < key.length; j++) {
              objectMaxLength[j] =
                objectMaxLength[j] >= key[j].length
                  ? objectMaxLength[j]
                  : key[j].length;
            }
            objectMaxLength[0] = 10;
          }

          const wscols = objectMaxLength.map(w => { return { width: w} });

          var workSheet = XLSX.utils.json_to_sheet(data.candidates);
          workSheet["!cols"] = wscols;

          var wb = XLSX.utils.book_new();
          XLSX.utils.book_append_sheet(wb, workSheet, fileName);

          var wbout = XLSX.write(wb, {
            bookType: 'xlsx',
            bookSST: true,
            type: 'binary'
          });
          saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'candidatos.xlsx');
        }
      }
      showLoading(false);
    },
    beforeSend: function() {
      showLoading(true);
    }
  });
}

function s2ab(s) {
  var buf = new ArrayBuffer(s.length);
  var view = new Uint8Array(buf);
  for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
  return buf;
}
