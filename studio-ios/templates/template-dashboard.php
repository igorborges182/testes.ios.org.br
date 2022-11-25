<?php
/**
 * The template for displaying dashboard of candidates to teachers approval.
 *
 * Doc link https://docs.gravityforms.com/api-functions/
 *
 */

get_header();

$dashboard_controller = new app\Controllers\Dashboard;

$dashboard_data = $dashboard_controller->get_dashboard_data();
$dashboard_filters = $dashboard_controller->get_dashboard_filters();

$detect = new Mobile_Detect;

?>

<?php if (!$detect->isMobile()) { ?><!-- não mostrar em mobile -->

<!-- Modal -->
<div id="modalFilter" class="modal modal-lg">
    <div class="modal-header">
        <h3 class="modal-title d-flex align-items-center" id="labelFilter">
            <span class="icon">
                <img src="<?= plugin_dir_url( __DIR__ ); ?>assets/images/baseline-filter_list.svg" alt="icone filtros">
            </span>
            Filtros
        </h3>
    </div>
    <div class="modal-body">
        <div class="filters_dashboard">
            <div class="row">
                <div class="filter_option flex justify-content-between col">
                    <h4 class="filter_label filter_title">Data de cadastro</h4>
                    <div class="btn-group d-flex" data-toggle="buttons">
                        <div class="form-group d-flex number">
                            <label for="data_de">
                                <input type="date" name="data_cadastro" class="filter_number filter_button" id="data_cadastro_de" placeholder="De:  " min="2000-01-01" max="<?php echo date("Y"); ?>-12-01">
                            </label>
                        </div>
                        <div class="form-group d-flex number">
                            <label for="data_ate">
                                <input type="date" name="data_cadastro" class="filter_number filter_button" id="data_cadastro_ate" placeholder="Até:  " min="2000-01-01" max="<?php echo date("Y"); ?>-<?php echo date("m"); ?>-<?php echo date("d"); ?>">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="filter_option flex justify-content-between col">
                    <h4 class="filter_label filter_title">Aprovado 1ª Fase</h4>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn filter_button btn_aprovado">
                            <input type="checkbox" name="aprovado" class="filter_aprovado filter_button_input" id="aprovado_sim" value="true">
                            <span class="filter_name">Sim</span>
                            <span class="filter_bg"></span>
                        </label>
                        <label class="btn filter_button btn_aprovado">
                            <input type="checkbox" name="aprovado" class="filter_aprovado filter_button_input" id="aprovado_nao" value="false">
                            <span class="filter_name">Não</span>
                            <span class="filter_bg"></span>
                        </label>
                    </div>
                </div>

                <div class="filter_option flex justify-content-between col">
                    <h4 class="filter_label filter_title">Aprovado 2ª Fase</h4>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn filter_button btn_aprovado_second_part">
                            <input type="checkbox" name="aprovado_second_part" class="filter_aprovado_second_part filter_button_input" id="aprovado_second_part_sim" value="true">
                            <span class="filter_name">Sim</span>
                            <span class="filter_bg"></span>
                        </label>
                        <label class="btn filter_button btn_aprovado_second_part">
                            <input type="checkbox" name="aprovado_second_part" class="filter_aprovado_second_part filter_button_input" id="aprovado_second_part_nao" value="false">
                            <span class="filter_name">Não</span>
                            <span class="filter_bg"></span>
                        </label>
                    </div>
                </div>

                <div class="filter_option flex justify-content-between col">
                    <h4 class="filter_label filter_title">Idade</h4>
                    <div class="btn-group d-flex" data-toggle="buttons">
                        <div class="form-group d-flex number">
                            <label for="idade_de">
                                <input type="number" min="1" max="100" name="idade" class="filter_number filter_button" id="idade_de" placeholder="De:">
                            </label>
                        </div>
                        <div class="form-group d-flex number">
                            <label for="idade_ate">
                                <input type="number" min="1" max="100" name="idade" class="filter_number filter_button" id="idade_ate" placeholder="Até:">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="filter_option flex justify-content-between col">
                    <h4 class="filter_label filter_title">Etnia</h4>
                    <div class="btn-group" data-toggle="buttons">
                        <?php foreach($dashboard_filters['etnias'] as $value => $label): ?>
                            <label class="btn filter_button btn_etnia">
                                <input type="checkbox" name="etnia" class="filter_etnia filter_button_input" value="<?= $value ?>">
                                <span class="filter_name"><?= $label ?></span>
                                <span class="filter_bg"></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="filter_option flex justify-content-between col">
                    <h4 class="filter_label filter_title">Escolaridade</h4>
                    <div class="btn-group" data-toggle="buttons">
                        <?php foreach($dashboard_filters['escolaridades'] as $value => $label): ?>
                            <label class="btn filter_button btn_escolaridade">
                                <input type="checkbox" name="escolaridade" class="filter_escolaridade filter_button_input" value="<?= $value ?>">
                                <span class="filter_name"><?= $label ?></span>
                                <span class="filter_bg"></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="filter_option flex justify-content-between col">
                    <h4 class="filter_label filter_title">Deficiencia</h4>
                    <div class="btn-group" data-toggle="buttons">
                        <?php foreach($dashboard_filters['deficiencias'] as $value => $label): ?>
                            <label class="btn filter_button btn_deficiencia">
                                <input type="checkbox" name="deficiencia" class="filter_deficiencia filter_button_input" value="<?= $value ?>">
                                <span class="filter_name"><?= $label ?></span>
                                <span class="filter_bg"></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="filter_option flex justify-content-between col">
                    <h4 class="filter_label filter_title">Faixa de Renda</h4>
                    <div class="btn-group" data-toggle="buttons">
                        <?php foreach($dashboard_filters['faixa_renda'] as $value => $label): ?>
                            <label class="btn filter_button btn_faixa_renda">
                                <input type="checkbox" name="faixa_renda" class="filter_faixa_renda filter_button_input" value="<?= $value ?>">
                                <span class="filter_name"><?= $label ?></span>
                                <span class="filter_bg"></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="filter_option flex justify-content-between last col">
                    <h4 class="filter_label filter_title">Cronograma Vacinal</h4>
                    <div class="btn-group last" data-toggle="buttons">
                        <?php foreach($dashboard_filters['cronograma_vacinal'] as $value => $label): ?>
                            <label class="btn filter_button btn_cronograma_vacinal">
                                <input type="checkbox" name="cronograma_vacinal" class="filter_cronograma_vacinal filter_button_input" value="<?= $value ?>">
                                <span class="filter_name"><?= $label ?></span>
                                <span class="filter_bg"></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="filter_option flex justify-content-between last col">
                    <h4 class="filter_label filter_title">Você foi indicado por algum serviço de assistência social e/ou saúde?</h4>
                    <div class="btn-group last" data-toggle="buttons">
                        <?php foreach($dashboard_filters['servico_social'] as $value => $label): ?>
                            <label class="btn filter_button btn_servico_social">
                                <input type="checkbox" name="servico_social" class="filter_servico_social filter_button_input" value="<?= $value ?>">
                                <span class="filter_name"><?= $label ?></span>
                                <span class="filter_bg"></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="filter_option flex justify-content-between last col">
                    <h4 class="filter_label filter_title">Qual serviço de Assistência Social e/ou Saúde te indicou para o IOS?</h4>
                    <div class="btn-group last" data-toggle="buttons">
                        <?php foreach($dashboard_filters['encaminhamento_social'] as $value => $label): ?>
                            <label class="btn filter_button btn_encaminhamento_social">
                                <input type="checkbox" name="encaminhamento_social" class="filter_encaminhamento_social filter_button_input" value="<?= $value ?>">
                                <span class="filter_name"><?= $label ?></span>
                                <span class="filter_bg"></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="filters__buttons text-right">
                <input type="submit" class="btn bg-warning" id="apply__filters" value="Aplicar filtros">
            </div>
        </div>
    </div>
</div>
<!-- END MODAL -->

<!-- Modal Unlock Imports -->
<div id="modalUnlockImports" class="modal modal-sm">
    <div class="modal-header">
        <h3 class="modal-title d-flex align-items-center" id="labelFilter">
            Liberar importação
        </h3>
    </div>
    <div class="modal-body border-0 p-0">
        <p>Deseja realmente liberar a importação de todos os candidatos não liberados?</span></p>
        <div class="unlock-imports-button confirmation-button text-right">
            <button class="btn bg-warning" id="">Liberar</button>
        </div>
    </div>
</div>
<!-- END Modal Unlock Imports  -->

<!-- Modal Approve -->
<div id="modalApprove" class="modal modal-sm">
    <div class="modal-header">
        <h3 class="modal-title d-flex align-items-center" id="labelFilter">
            Aprovar 1ª Fase todos
        </h3>
    </div>
    <div class="modal-body border-0 p-0">
        <p>Deseja realmente aprovar todos os <span class="font-weight-bold total-candidates"><?= count($dashboard_data['candidates']) ?></span> candidatos na 1ª fase?</p>
        <div class="approve-button confirmation-button text-right">
            <button class="btn bg-warning" id="">Aprovar</button>
        </div>
    </div>
</div>
<!-- END Modal Approve  -->

<!-- Modal Approve 2 Part -->
<div id="modalApproveSecondPart" class="modal modal-sm">
    <div class="modal-header">
        <h3 class="modal-title d-flex align-items-center" id="labelFilter">
            Aprovar 2ª Fase todos
        </h3>
    </div>
    <div class="modal-body border-0 p-0">
        <p>Deseja realmente aprovar todos os <span class="font-weight-bold total-candidates"><?= count($dashboard_data['candidates']) ?></span> candidatos na 2ª fase?</p>
        <div class="approve-button confirmation-button text-right">
            <button class="btn bg-warning" id="">Aprovar</button>
        </div>
    </div>
</div>
<!-- END Modal Approve 2 Part -->

<!-- Modal Approve -->
<div id="modalDisapprove" class="modal modal-sm">
    <div class="modal-header">
        <h3 class="modal-title d-flex align-items-center" id="labelFilter">
            Desaprovar Todos
        </h3>
    </div>
    <div class="modal-body border-0 p-0">
        <p>Deseja realmente desaprovar todos os <span class="font-weight-bold total-candidates"><?= $dashboard_data['approved_candidates'] ?></span> candidatos?</p>
        <div class="approve-button confirmation-button text-right">
            <button class="btn bg-warning" id="">Desaprovar</button>
        </div>
    </div>
</div>
<!-- END Modal Approve  disapprove-->

<?php } ?> <!-- não mostrar em mobile -->

<!-- Loading -->
<div id="loading">
    <img src="<?php echo plugins_url('/studio-ios/assets/images/loading.svg'); ?>">
</div>
<!-- END Loading  -->

<main class="dashboard" role="main">
    <section>
        <div class="container">
            <div class="dashboard__header d-flex justify-content-between">
                <div class="dashboard__header-logo">
                    <img class="logo" src="<?= plugins_url('/studio-ios/assets/images/logo-ios-roxo.png'); ?>"
                         alt="Logo IOS">
                </div>
                <div class="dashboard__header-user user">
                    <div class="user__infos text-right">
                        <?php if(!empty(wp_get_current_user()->ID)): ?>
                            <p class="info user__name text-uppercase font-weight-bold"><?= wp_get_current_user()->display_name; ?></p>
                            <p class="info user__title text-capitalize"><?= wp_get_current_user()->roles[key(wp_get_current_user()->roles)]; ?></p>
                            <p class="info user__logout font-weight-bold"><a href="<?= wp_logout_url(); ?>">Sair</a></p>
                        <?php else: ?>
                            <p class="info user__logout font-weight-bold"><a href="<?= wp_login_url(); ?>" style="color: #0194D3">Entrar</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if ($detect->isMobile()) { ?><!-- mostrar em mobile -->
        <section>
            <div class="list__header" style="background-image: url('<?php echo $dashboard_controller->get_img_fundo_dashboard(); ?>');">
                <h1 class="list__header-title"><?php echo $dashboard_controller->get_titulo_dashboard(); ?></h1>
            </div>
            <div class="container">
                <div class="content">
                    <img class="desktop" src="<?= plugins_url('/studio-ios/assets/images/desktop.png'); ?>"
                         alt="Desktop">
                    <h2 class="only_mobile">Para melhor visualização acesse pelo computador</h2>
                    <p class="only_mobile_msg">Para poder visualizar e aprovar candidatos é necessário o acesso da lista pelo computador</p>
                    <a href="<?= wp_login_url(); ?>" class="btn voltar_login" id="voltar_login">Voltar para o login</a>
                    <a href="<?= home_url(); ?>" class="btn ir_site" id="ir_site">Ir para o site IOS</a>
                </div>
            </div>
        </section>

    <?php } else { ?><!-- não mostrar em mobile -->

        <section>
            <div class="container">
                <div class="list__header" style="background-image: url('<?php echo $dashboard_controller->get_img_fundo_dashboard(); ?>');">
                    <h1 class="list__header-title"><?php echo $dashboard_controller->get_titulo_dashboard(); ?></h1>
                    <div class="list__header-infos d-flex justify-content-between">
                        <div class="info color-white">
                            <p><?php echo $dashboard_controller->get_descricao_dashboard(); ?></p>
                            <span class="candidates all-candidates">Candidatos (<span
                                        class="all-candidates-qty font-weight-bold"><?= count($dashboard_data['candidates']) ?></span>)</span>
                            <span class="candidates approved-candidates">Aprovados 1ª Fase (<span
                                        class="approved-candidates-qty font-weight-bold"><?= $dashboard_data['approved_candidates'] ?></span>)</span>
                            <span class="candidates approved-candidates-second-part">Aprovados 2ª Fase (<span
                                        class="approved-candidates-second-part-qty font-weight-bold"><?= $dashboard_data['approved_candidates_second_part'] ?></span>)</span>
                        </div>
                        <div class="busca">
                            <div id="form-busca" class="d-flex">
                                <input id="busca-box" class="busca__input" name="busca" type="text"
                                       placeholder="Faça sua busca"/>
                                <input id="busca-btn" class="busca__btn" value="Buscar" type="submit"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filters d-flex justify-content-between">
                    <div class="filters__selects">
                        <span class="filters__span">Selecione:</span>
                        <?php $unities = $dashboard_filters['unities']; ?>
                        <select class="form-select select-unidade" id="unidade" aria-label="Escolha uma unidade" name="unidade[]" multiple="multiple" style="width: 235px">
                            <option></option>
                            <?php foreach($unities as $value => $label): ?>
                                <option value="<?= $value ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>

        
                        <?php $courses = $dashboard_filters['courses']; ?>
                        <span class="filters__spacing">|</span>
                        <select class="form-select select-curso" id="curso" aria-label="Escolha um curso" name="curso[]" multiple="multiple" style="min-width: 220px">
                            <option></option>
                            <?php foreach($courses as $k => $value): ?>
                                <option value="<?= $value ?>"><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="filters-buttons d-flex justify-content-between">
                    <div class="filters__buttons">
                        <span class="filters__span">Ações:</span>
                        <button class="btn bg-warning filterButton" id="filterButton"><em class="fas fa-sort-amount-down"></em> Filtrar
                        </button>
                        <button class="btn bg-primary unlock-imports"><em class="fas fa-thumbs-up"></em> Liberar importação</button>
                        <button class="btn bg-success approve-all <?php if(count($dashboard_data['candidates']) == $dashboard_data['approved_candidates'] && count($dashboard_data['candidates']) != 0){ echo "d-none"; } ?>"><em class="fas fa-check"></em> Aprovar 1ª Fase todos</button>
                        <button class="btn bg-danger disapprove-all <?php if(count($dashboard_data['candidates']) > $dashboard_data['approved_candidates'] || count($dashboard_data['candidates']) == 0){ echo "d-none"; } ?>"><em class="fas fa-times"></em> Desaprovar todos</button>
                        <button class="btn bg-info exportTable"><em class="fas fa-download"></em> Exportar</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="table__section">
            <div class="container">
                <div class="row">
                    <div class="table__filters col d-flex justify-content-between">
                        <div class="table__filter-selected d-flex filtros d-none">
                            <!-- TODO: corrigir filtros ativos -->
                            <span>Filtros:</span>
                            <span class="selected-filter d-none filter-data_cadastro">
                                <span class="filter-name">Data de Cadastro: </span>
                                <span class="filter-value font-weight-bold"></span>
                                <button class="filter-remove" data-filter="data_cadastro"><em class="fas fa-times-circle"></em></button>
                            </span>
                            <span class="selected-filter d-none filter-idade">
                                <span class="filter-name">Idade: </span>
                                <span class="filter-value font-weight-bold"></span>
                                <button class="filter-remove" data-filter="idade"><em class="fas fa-times-circle"></em></button>
                            </span>
                            <span class="selected-filter d-none filter-aprovado">
                                <span class="filter-name">Aprovado 1ª Fase: </span>
                                <span class="filter-value font-weight-bold"></span>
                                <button class="filter-remove" data-filter="aprovado"><em class="fas fa-times-circle"></em></button>
                            </span>
                            <span class="selected-filter d-none filter-aprovado_second_part">
                                <span class="filter-name">Aprovado 2ª Fase: </span>
                                <span class="filter-value font-weight-bold"></span>
                                <button class="filter-remove" data-filter="aprovado_second_part"><em class="fas fa-times-circle"></em></button>
                            </span>
                            <span class="selected-filter d-none filter-etnia">
                                <span class="filter-name">Etnia: </span>
                                <span class="filter-value font-weight-bold"></span>
                                <button class="filter-remove" data-filter="etnia"><em class="fas fa-times-circle"></em></button>
                            </span>
                            <span class="selected-filter d-none filter-escolaridade">
                                <span class="filter-name">Escolaridade: </span>
                                <span class="filter-value font-weight-bold"></span>
                                <button class="filter-remove" data-filter="escolaridade"><em class="fas fa-times-circle"></em></button>
                            </span>
                            <span class="selected-filter d-none filter-deficiencia">
                                <span class="filter-name">Deficiencia: </span>
                                <span class="filter-value font-weight-bold"></span>
                                <button class="filter-remove" data-filter="deficiencia"><em class="fas fa-times-circle"></em></button>
                            </span>
                            <span class="selected-filter d-none filter-faixa_renda">
                                <span class="filter-name">Faixa de Renda: </span>
                                <span class="filter-value font-weight-bold"></span>
                                <button class="filter-remove" data-filter="faixa_renda"><em class="fas fa-times-circle"></em></button>
                            </span>
                            <span class="selected-filter d-none filter-cronograma_vacinal">
                                <span class="filter-name">Cronograma Vacinal: </span>
                                <span class="filter-value font-weight-bold"></span>
                                <button class="filter-remove" data-filter="cronograma_vacinal"><em class="fas fa-times-circle"></em></button>
                            </span>
                            <span class="selected-filter d-none filter-servico_social">
                                <span class="filter-name">Você foi indicado por algum serviço de assistência social e/ou saúde?: </span>
                                <span class="filter-value font-weight-bold"></span>
                                <button class="filter-remove" data-filter="servico_social"><em class="fas fa-times-circle"></em></button>
                            </span>
                            <span class="selected-filter d-none filter-encaminhamento_social">
                                <span class="filter-name">Qual serviço de Assistência Social e/ou Saúde te indicou para o IOS?: </span>
                                <span class="filter-value font-weight-bold"></span>
                                <button class="filter-remove" data-filter="encaminhamento_social"><em class="fas fa-times-circle"></em></button>
                            </span>
                            <span class="selected-filter d-none filter-nome_servico">
                                <span class="filter-name">Qual o nome do serviço? </span>
                                <span class="filter-value font-weight-bold"></span>
                                <button class="filter-remove" data-filter="nome_servico"><em class="fas fa-times-circle"></em></button>
                            </span>
                            <span class="selected-filter d-none filter-renda_familiar">
                                <span class="filter-name">Renda Familiar </span>
                                <span class="filter-value font-weight-bold"></span>
                                <button class="filter-remove" data-filter="renda_familiar"><em class="fas fa-times-circle"></em></button>
                            </span>
                        </div>

                        <div class="table__columns filter_columns">
                            <select class="form-select filter-column" aria-label="Todas colunas" name="columns[]" multiple="multiple">
                                <option></option>
                                <option class="toggle-vis" data-column="2">Data de inscrição</option>
                                <option class="toggle-vis" data-column="3">Curso</option>
                                <option class="toggle-vis" data-column="4">Unidade</option>
                                <option class="toggle-vis" data-column="5">Candidato</option>
                                <option class="toggle-vis" data-column="6">Data de Nascimento</option>
                                <option class="toggle-vis" data-column="7">Nome Social</option>
                                <option class="toggle-vis" data-column="8">Gênero</option>
                                <option class="toggle-vis" data-column="9">Idade</option>
                                <option class="toggle-vis" data-column="10">Etnia</option>
                                <option class="toggle-vis" data-column="11">E-mail</option>
                                <option class="toggle-vis" data-column="12">Escolaridade</option>
                                <option class="toggle-vis" data-column="13">Série</option>
                                <option class="toggle-vis" data-column="14">Ano</option>
                                <option class="toggle-vis" data-column="15">Andamento</option>
                                <option class="toggle-vis" data-column="16">Pública/Privada</option>
                                <option class="toggle-vis" data-column="17">Universidade/Faculdade</option>
                                <option class="toggle-vis" data-column="18">Bolsista</option>
                                <option class="toggle-vis" data-column="19">Tel. Celular</option>
                                <option class="toggle-vis" data-column="20">CEP</option>
                                <option class="toggle-vis" data-column="21">Endereço</option>
                                <option class="toggle-vis" data-column="22">Bairro</option>
                                <option class="toggle-vis" data-column="23">Município</option>
                                <option class="toggle-vis" data-column="24">UF</option>
                                <option class="toggle-vis" data-column="25">Qtd. Residentes</option>
                                <option class="toggle-vis" data-column="26">Deficiente</option>
                                <option class="toggle-vis" data-column="27">Tipo da Deficiência</option>
                                <option class="toggle-vis" data-column="28">Qual serviço de Assistência Social e/ou Saúde te indicou para o IOS?</option>
                                <option class="toggle-vis" data-column="29">Faixa de Renda Familiar</option>
                                <option class="toggle-vis" data-column="30">Cronograma vacinal contra COVID</option>
                                <option class="toggle-vis" data-column="31">Você foi indicado por algum serviço de assistência social e/ou saúde?</option>
                                <option class="toggle-vis" data-column="32">Qual o nome do serviço?</option>
                                <option class="toggle-vis" data-column="33">Renda Familiar</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="dashboard-content" class="">
                    <div class="dashboard-container students-table">
                        <table class="dashboard-table" id="dashboard-table">
                            <thead>
                                <tr>
                                    <th class="fixed-column" style="padding-left: 22px">Aprovado 1ª Fase</th>
                                    <th class="fixed-column-second_part" style="padding-left: 22px">Aprovado 2ª Fase</th>
                                    <th>Data de inscrição</th>
                                    <th>Curso</th>
                                    <th>Unidade</th>
                                    <th>Candidato</th>
                                    <th>Data de Nascimento</th>
                                    <th>Nome Social</th>
                                    <th>Gênero</th>
                                    <th>Idade</th>
                                    <th>Etnia</th>
                                    <th>E-mail</th>
                                    <th>Escolaridade</th>
                                    <th>Série</th>
                                    <th>Ano</th>
                                    <th>Andamento</th>
                                    <th>Pública/Privada</th>
                                    <th>Universidade/Faculdade</th>
                                    <th>Bolsista</th>
                                    <th>Tel. Celular</th>
                                    <th>CEP</th>
                                    <th>Endereço</th>
                                    <th>Bairro</th>
                                    <th>Município</th>
                                    <th>UF</th>
                                    <th>Qtd. Residentes</th>
                                    <th>Deficiente</th>
                                    <th>Tipo da Deficiência</th>
                                    <th>Você foi indicado por algum serviço de assistência social e/ou saúde?</th>
                                    <th>Qual serviço de Assistência Social e/ou Saúde te indicou para o IOS?</th>
                                    <th>Qual o nome do serviço?</th>
                                    <th>Faixa de Renda Familiar</th>
                                    <th>Cronograma vacinal contra COVID</th>
                                    <th>Renda Familiar</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                            <?php foreach ($dashboard_data['candidates'] as $key => $candidate): ?>
                                <tr class="<?= ($candidate['aprovado'] || $candidate['aprovado_second_part']) ? 'active' : '' ?>" data-entry-id="<?= $candidate['id'] ?>" data-approved="<?= ($candidate['aprovado']) ? 'true' : 'false' ?>">
                                    <td class="fixed-column">
                                        <label class="toggle" style="font-size:12px">
                                            <input class="approve_candidate" type="checkbox" <?= ($candidate['aprovado']) ? 'checked' : '' ?> />
                                            <span class="font-weight-bold" data-on="Sim" data-off="Não"></span>
                                        </label>
                                    </td><? # Aprovado 1ª Fase/Não aprovado 1ª Fase ?>
                                    <td class="fixed-column-second_part">
                                        <label class="toggle" style="font-size:12px">
                                            <input class="approve_candidate_second_part" type="checkbox" <?= ($candidate['aprovado_second_part']) ? 'checked' : '' ?> />
                                            <span class="font-weight-bold" data-on="Sim" data-off="Não"></span>
                                        </label>
                                    </td><? # Aprovado 2ª Fase/Não aprovado 2ª Fase ?>
                                    <td><?= $candidate['data_inscricao'] ?></td><? # Data de inscrição ?>
                                    <td><?= $candidate['curso'] ?></td><? # Curso ?>
                                    <td><?= $candidate['unidade'] ?></td><? # Unidade ?>
                                    <td><?= $candidate['candidato'] ?></td><? # Candidato ?>
                                    <td><?= $candidate['data_nascimento'] ?></td><? # Data de Nascimento ?>
                                    <td><?= $candidate['nome_social'] ?></td><? # Nome Social ?>
                                    <td><?= $candidate['genero'] ?></td> <? # genero ?>
                                    <td><?= $candidate['idade'] ?></td> <? # idade ?>
                                    <td><?= $candidate['etnia'] ?></td><? # etnia ?>
                                    <td><?= $candidate['email'] ?></td> <? # e-mail ?>
                                    <td><?= $candidate['grau_escolaridade'] ?></td> <? # Grau escolaridade ?>
                                    <td><?= $candidate['serie'] ?></td> <? # Série ?>
                                    <td><?= $candidate['ano'] ?></td> <? # Ano ?>
                                    <td><?= $candidate['andamento'] ?></td> <? # Andamento ?>
                                    <td><?= $candidate['instituicao_publica_privada'] ?></td> <? # Instituicao Pública/privada ?>
                                    <td><?= $candidate['universidade'] ?></td> <? # universidade/faculdade ?>
                                    <td><?= $candidate['valor_bolsa'] ?></td> <? # Valor Bolsa - Bolsista ?>
                                    <td><?= $candidate['tel_celular'] ?></td> <? # tel celular ?>
                                    <td><?= $candidate['cep'] ?></td> <? # CEP ?>
                                    <td><?= $candidate['endereco'] ?></td> <? # Endereço ?>
                                    <td><?= $candidate['bairro'] ?></td> <? # Bairro ?>
                                    <td><?= $candidate['municipio'] ?></td> <? # Municipio ?>
                                    <td><?= $candidate['uf'] ?></td> <? # UF ?>
                                    <td><?= $candidate['qtd_residentes'] ?></td> <? # Qtd. Residentes ?>
                                    <td><?= $candidate['deficiente'] ?></td> <? # Deficiente ?>
                                    <td><?= $candidate['tipo_deficiencia'] ?></td> <? # Tipo da deficiencia (implode(',')) ?>
                                    <td><?= $candidate['servico_social'] ?></td> <? # Você foi indicado por algum serviço de assistência social e/ou saúde? ?>
                                    <td><?= $candidate['encaminhamento_social'] ?></td> <? # Qual serviço de Assistência Social e/ou Saúde te indicou para o IOS? ?>
                                    <td><?= $candidate['nome_servico'] ?></td> <? # Qual o nome do serviço? ?>
                                    <td><?= $candidate['faixa_renda_familiar'] ?></td> <? # Faixa de Renda Familiar ?>
                                    <td><?= $candidate['vacinacao_covid'] ?></td> <? # Cronograma vacinal contra COVID ?>
                                    <td><?= $candidate['renda_familiar'] ?></td> <? # Renda Familiar ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table__filters row d-flex justify-content-start">
                    <div class="filters__buttons">
                        <span class="filters__span">Ações:</span>
                        <button class="btn bg-warning filterButton"><em class="fas fa-sort-amount-down"></em> Filtrar</button>
                        <button class="btn bg-primary unlock-imports"><em class="fas fa-thumbs-up"></em> Liberar importação</button>
                        <button class="btn bg-success approve-all <?php if(count($dashboard_data['candidates']) == $dashboard_data['approved_candidates'] && count($dashboard_data['candidates']) != 0){ echo "d-none"; } ?>"><em class="fas fa-check"></em> Aprovar 1ª Fase todos</button>
                        <button class="btn bg-danger disapprove-all <?php if(count($dashboard_data['candidates']) > $dashboard_data['approved_candidates'] || count($dashboard_data['candidates']) == 0){ echo "d-none"; } ?>"><em class="fas fa-times"></em> Desaprovar todos</button>
                        <button class="btn bg-info exportTable"><em class="fas fa-download"></em> Exportar</button>
                    </div>
                </div>
            </div>
        </section>

    <?php } ?><!-- não mostrar em mobile -->

    <?php get_footer(); ?>
