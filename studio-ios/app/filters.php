<?php 

//Atualiza o carrinho no checkout
$filter_dashboard = function () {
    try {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce')) {
            wp_send_json_error('Acesso negado.');
        }

        $dashboard_controller = new app\Controllers\Dashboard;

        $filters = $_POST['filters'];

        $filtro_existe = 0;
        foreach ($filters as $key => $value) {
            if($value != "" && $value != null && !is_array($value)) {
                $filtro_existe++;
            } else if(is_array($value)) {
                foreach ($value as $key => $value_array) {
                    if($value_array != "" && $value_array != null) {
                        $filtro_existe++;
                    }
                }
            }
        }
        if($filtro_existe >= 1){
            $data_filter = '';
            $dashboard_data = $dashboard_controller->get_dashboard_data($filters, $_POST['excel'], $data_filter);
        } else {
            $data_filter = $dashboard_controller->original_start_date;
            $dashboard_data = $dashboard_controller->get_dashboard_data($filters, $_POST['excel'], $data_filter);
        }


        wp_send_json_success($dashboard_data);
    } catch (\Exception $e) {
        wp_send_json_error('Exceção capturada: ',  $e->getMessage(), "\n");
    }
};

//Adiciona ajax para atualizar carrinho no checkout
add_action('wp_ajax_nopriv_filter_dashboard', $filter_dashboard);
add_action('wp_ajax_filter_dashboard', $filter_dashboard);


/////
//Aprovando e desaprovando candidato e todos candidatos
$approve_candidate = function () {
    try {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce')) {
            wp_send_json_error('Acesso negado.');
        }

        $data['entry_id'] = $_POST['entry_id'];

        $data_filter = '';
        $filters['id'] = $data['entry_id'];
        $dashboard_controller = new app\Controllers\Dashboard;
        $results = $dashboard_controller->get_dashboard_data($filters, $_POST['excel'], $data_filter);

        $data['form_id'] = $dashboard_controller->form_id;
        $data['imported'] = 0;
        $data['is_approved_for_import'] = 0;
        $data['approved_date'] = date("Y-m-d H:i:s");
        $data['approved_second_part'] = null;
        $data['approved_second_part_date'] = null;
        $data['teacher_import_id'] = get_current_user_id();

        insert_entries_approved($data);

        $dashboard_controller = new app\Controllers\Dashboard;
        $dashboard_controller->approved_candidates = $dashboard_controller->approved_candidates + 1;

        $candidato = $results['candidates'][0];
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail($results['candidates'][0]['email'], "IOS - Você foi aprovado na 1ª fase do processo de inscrição!", email_template($candidato), $headers);

        wp_send_json_success($results);
    } catch (\Exception $e) {
        wp_send_json_error('Exceção capturada: ',  $e->getMessage(), "\n");
    }
};
add_action( 'wp_ajax_nopriv_approve_candidate', $approve_candidate );
add_action( 'wp_ajax_approve_candidate', $approve_candidate );

$disapprove_candidate = function () {
    try {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce')) {
            wp_send_json_error('Acesso negado.');
        }

        $entry_id = $_POST['entry_id'];

        $return = delete_entry_approved($entry_id);

        $dashboard_controller = new app\Controllers\Dashboard;
        $dashboard_controller->approved_candidates = $dashboard_controller->approved_candidates - 1;

        wp_send_json_success($return);
    } catch (\Exception $e) {
        wp_send_json_error('Exceção capturada: ',  $e->getMessage(), "\n");
    }
};
add_action( 'wp_ajax_nopriv_disapprove_candidate', $disapprove_candidate );
add_action( 'wp_ajax_disapprove_candidate', $disapprove_candidate );

$approve_candidate_second_part = function () {
    try {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce')) {
            wp_send_json_error('Acesso negado.');
        }

        $data['entry_id'] = $_POST['entry_id'];

        $data_filter = '';
        $filters['id'] = $data['entry_id'];
        $dashboard_controller = new app\Controllers\Dashboard;
        $results = $dashboard_controller->get_dashboard_data($filters, $_POST['excel'], $data_filter);

        if($_POST['approved'] == 'false'){
            $data['form_id'] = $dashboard_controller->form_id;
            $data['imported'] = 0;
            $data['is_approved_for_import'] = 0;
            $data['approved_date'] = date("Y-m-d H:i:s");
            $data['teacher_import_id'] = get_current_user_id();
            $data['approved_second_part'] = 1;
            $data['approved_second_part_date'] = date("Y-m-d H:i:s");
            $data['teacher_import_second_part_id'] = get_current_user_id();
            insert_entries_approved($data);
        } else {
            $data['approved_second_part'] = 1;
            $data['approved_second_part_date'] = date("Y-m-d H:i:s");
            $data['teacher_import_second_part_id'] = get_current_user_id();
            update_entry_approved($data, $_POST['entry_id']);
        }

        $dashboard_controller = new app\Controllers\Dashboard;
        $dashboard_controller->approved_candidates_second_part = $dashboard_controller->approved_candidates_second_part + 1;

        wp_send_json_success($data);
    } catch (\Exception $e) {
        wp_send_json_error('Exceção capturada: ',  $e->getMessage(), "\n");
    }
};
add_action( 'wp_ajax_nopriv_approve_candidate_second_part', $approve_candidate_second_part );
add_action( 'wp_ajax_approve_candidate_second_part', $approve_candidate_second_part );

$disapprove_candidate_second_part = function () {
    try {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce')) {
            wp_send_json_error('Acesso negado.');
        }

        $entry_id = $_POST['entry_id'];

        $results = \GFAPI::get_entry($data['entry_id']);

        $data['imported'] = 0;
        $data['is_approved_for_import'] = 0;
        $data['approved_second_part'] = null;
        $data['approved_second_part_date'] = null;
        $data['teacher_import_second_part_id'] = null;

        update_entry_approved($data, $_POST['entry_id']);

        $dashboard_controller = new app\Controllers\Dashboard;
        $dashboard_controller->approved_candidates = $dashboard_controller->approved_candidates - 1;

        wp_send_json_success($return);
    } catch (\Exception $e) {
        wp_send_json_error('Exceção capturada: ',  $e->getMessage(), "\n");
    }
};
add_action( 'wp_ajax_nopriv_disapprove_candidate_second_part', $disapprove_candidate_second_part );
add_action( 'wp_ajax_disapprove_candidate_second_part', $disapprove_candidate_second_part );

$approve_all_candidates = function () {
    try {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce')) {
            wp_send_json_error('Acesso negado.');
        }

        $dashboard_controller = new app\Controllers\Dashboard;

        $filters = $_POST['filters'];

        $filtro_existe = 0;
        foreach ($filters as $key => $value) {
            if($value != "" && $value != null && !is_array($value)) {
                $filtro_existe++;
            } else if(is_array($value)) {
                foreach ($value as $key => $value_array) {
                    if($value_array != "" && $value_array != null) {
                        $filtro_existe++;
                    }
                }
            }
        }
        if($filtro_existe >= 1){
            $data_filter = '';
        } else {
            $data_filter = $dashboard_controller->original_start_date;
        }
        $filters['aprovado'] = false;

        $dashboard_data = $dashboard_controller->get_dashboard_data($filters, $_POST['excel'], $data_filter);


        foreach ($dashboard_data['candidates'] as $key => $candidate) {
            $data = [];
            $data['entry_id'] = $candidate['id'];
            $data['form_id'] = $dashboard_controller->form_id;
            $data['post_id'] = $candidate['post_id'];
            $data['imported'] = 0;
            $data['is_approved_for_import'] = 0;
            $data['approved_date'] = date("Y-m-d H:i:s");
            $data['teacher_import_id'] = get_current_user_id();
            $data['approved_second_part'] = null;
            $data['approved_second_part_date'] = null;
            $data['teacher_import_second_part_id'] = null;
            insert_entries_approved($data);

            $candidato = $candidate;
            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail($candidate['email'], "IOS - Você foi aprovado na 1ª fase do processo de inscrição!", email_template($candidato), $headers);
        }

        $dashboard_controller = new app\Controllers\Dashboard;
        $dashboard_controller->approved_candidates = count($dashboard_data['candidates']);

        wp_send_json_success();
    } catch (\Exception $e) {
        wp_send_json_error('Exceção capturada: ',  $e->getMessage(), "\n");
    }
};
add_action( 'wp_ajax_nopriv_approve_all_candidates', $approve_all_candidates );
add_action( 'wp_ajax_approve_all_candidates', $approve_all_candidates );

$disapprove_all_candidates = function () {
    try {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce')) {
            wp_send_json_error('Acesso negado.');
        }

        $dashboard_controller = new app\Controllers\Dashboard;

        $filters = $_POST['filters'];

        $filtro_existe = 0;
        foreach ($filters as $key => $value) {
            if($value != "" && $value != null && !is_array($value)) {
                $filtro_existe++;
            } else if(is_array($value)) {
                foreach ($value as $key => $value_array) {
                    if($value_array != "" && $value_array != null) {
                        $filtro_existe++;
                    }
                }
            }
        }
        if($filtro_existe >= 1){
            $data_filter = '';
        } else {
            $data_filter = $dashboard_controller->original_start_date;
        }
        $filters['aprovado'] = true;

        $dashboard_data = $dashboard_controller->get_dashboard_data($filters, $_POST['excel'], $data_filter);

        foreach ($dashboard_data['candidates'] as $key => $candidate) {
            delete_entry_approved($candidate['id']);
        }

        $dashboard_controller = new app\Controllers\Dashboard;
        $dashboard_controller->approved_candidates = 0;

        wp_send_json_success();
    } catch (\Exception $e) {
        wp_send_json_error('Exceção capturada: ',  $e->getMessage(), "\n");
    }
};
add_action( 'wp_ajax_nopriv_disapprove_all_candidates', $disapprove_all_candidates );
add_action( 'wp_ajax_disapprove_all_candidates', $disapprove_all_candidates );

/////
// Aprova candidatos para importação
$unlock_imports_candidates = function () {
    try {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce')) {
            wp_send_json_error('Acesso negado.');
        }

        $dashboard_controller = new app\Controllers\Dashboard;

        $filters = $_POST['filters'];
        $filters['aprovado_second_part'] = true;
        $filters['imported'] = false;
        $filters['is_approved_for_import'] = false;
        $filters['teacher_import_id'] = get_current_user_id();
        $dashboard_data = $dashboard_controller->get_dashboard_data($filters, false, '');

        foreach ($dashboard_data['candidates'] as $key => $candidate) {
            update_entry_approved(array('is_approved_for_import' => '1'), $candidate['id']);
        }

        wp_send_json_success();
    } catch (\Exception $e) {
        wp_send_json_error('Exceção capturada: ',  $e->getMessage(), "\n");
    }
};
add_action( 'wp_ajax_nopriv_unlock_imports_candidates', $unlock_imports_candidates );
add_action( 'wp_ajax_unlock_imports_candidates', $unlock_imports_candidates );

/////
// Criando campos na página do Dashboard
function acf_dashboard() {
    if( function_exists('acf_add_local_field_group') ):

        $forms = GFAPI::get_forms();
        $choices = [];

        foreach ($forms as $form) {
            $choices += array($form['id'] => $form['title']);
        }

        acf_add_local_field_group(array(
            'key' => 'configuracoes_dashboard',
            'title' => 'Dashboard Configurações',
            'fields' => array (
                array (
                    'key' => 'titulo',
                    'label' => 'Título',
                    'name' => 'titulo',
                    'type' => 'text',
                    'default_value' => 'Lista de candidatos'
                ),
                array (
                    'key' => 'descricao',
                    'label' => 'Descrição',
                    'name' => 'descricao',
                    'type' => 'wysiwyg',
                    'default_value' => '<strong>Filtre</strong> seus inscritos e <strong>aprove os candidatos</strong> clicando no <strong>botão</strong> da coluna <strong>Aprovado</strong>'
                ),
                array (
                    'key' => 'img_fundo',
                    'label' => 'Imagem de Fundo',
                    'name' => 'img_fundo',
                    'type' => 'image',
                    'return_format' => 'url'
                ),
                array (
                    'key' => 'formulario',
                    'label' => 'Formulário',
                    'name' => 'formulario',
                    'type' => 'select',
                    'choices' => $choices,
                    'multiple' => 0,
                    'ui' => 1
                )
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'page_template',
                        'operator' => '==',
                        'value' => 'template-dashboard.php',
                    ),
                ),
            ),
        ));

    endif;
}
add_action( 'acf/init', 'acf_dashboard' );

/////
// Criando role de professor
add_role('professor', 'Professor', array('read' => true));

/////
// Criando campo de unidade no perfil do usuário
function acf_unidades() {
    if( function_exists('acf_add_local_field_group') ):

        $unidades = GFFormsModel::get_field( 13, 1175 )['choices'];
        $choices = [];
        foreach ($unidades as $unidade) {
            $choices += array($unidade['value'] => $unidade['text']);
        }

        acf_add_local_field_group(array(
            'key' => 'dados_ios',
            'title' => 'Dados do IOS',
            'fields' => array (
                array (
                    'key' => 'unidade',
                    'label' => 'Unidades',
                    'name' => 'unidade',
                    'type' => 'select',
                    'choices' => $choices,
                    'multiple' => 1,
                    'ui' => 1
                )
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'user_form',
                        'operator' => '==',
                        'value' => 'all',
                    ),
                ),
            ),
        ));

    endif;
}
add_action( 'acf/init', 'acf_unidades' );

function email_template($candidato){
    defined( 'ABSPATH' ) || exit;

    $msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
    </head>
    <body style="background-color: #FFF; padding-top:30px; padding-bottom:30px">

    <div>
    <style>body{ background-color: #FFF; padding-top:30px; padding-bottom:30px}</style>
    </div>

    <table width="600" align="center" cellpadding="0" cellspacing="0" border="0" style="font-family: Arial, Arial;color:#000; text-align:center; background-color: #f7f7f7 !important; padding: 40px 0;">
            <tbody>
            <tr>
                <td>
                    <a href="'.site_url().'" target="_blank" style="text-decoration:none; color:black;">
                        <img src="https://ios.intest.com.br/wp-content/plugins/studio-ios/assets/images/logo-ios-roxo.png" alt="" style="margin-top: 3px; width: 120px; display: block; margin: 0 auto; height: auto;">
                    </a>
                    <br>
                    <div style="font-size: 17px; font-weight: bold; ">Instituto da Oportunidade Social<br>Você foi aprovado na 1ª fase do processo de inscrição!</div>
                    <br>
                </td>
            </tr>
            <tr>
                <td>
                    <center>
                    <div style="text-align: center; color: #757575; font-size: 14px; width: 380px; line-height: 19px;">
                        Olá '.$candidato['candidato'].'! <BR> Você foi aprovado na primeira fase do processo de inscrição do Instituto da Oportunidade Social
                    </center>
                </td>
            </tr>
        </tbody>
    </table>


    </body>
    </html>';

    return $msg;
}
