<?php 

namespace app\Controllers;

class Dashboard {

    public $approved_candidates = 0;
    public $approved_candidates_second_part = 0;
    public $form_id;
    public $original_start_date = '-30 days';
    public $page_size = 1500;


    public function get_dashboard_data($filter_data = null, $excel = false, $start_date_filter = null) {
        $args = [
            'post_type' => 'page',
            'fields' => 'ids',
            'nopaging' => true,
            'meta_key' => '_wp_page_template',
            'meta_value' => 'template-dashboard.php'
        ];
        $pages = get_posts( $args );
        $this->form_id = get_field('formulario', $pages[0]);

        // padrão de data ultimos 30 ou 60 dias
        // $start_date = date( 'Y-m-01', strtotime('-1 month') );
        if(isset($start_date_filter)){
            if(strlen($start_date_filter) > 0){
                $start_date = date('Y-m-d', strtotime($start_date_filter));
                $search_criteria['start_date'] = $start_date;
            }
        } else {
            $start_date = date('Y-m-d', strtotime($this->original_start_date));
            $search_criteria['start_date'] = $start_date;
        }
        $end_date = date('Y-m-d', time());
        $search_criteria['end_date'] = $end_date;


        if(current_user_can("professor")){
            $user_unidades = get_user_meta(get_current_user_id(), 'unidade');
            $search_criteria['field_filters'][] = ['key' => '1175', 'value' => $user_unidades[0], 'operator' => 'in'];
        }

        if(!empty($filter_data)) {
            // remove from $filter_data filters parameters that not filtered on get_entries function
            if(!empty($filter_data['idade'])) {
                $_filter_data['idade'] = $filter_data['idade'];
                unset($filter_data['idade']);
            }

            $search_criteria['field_filters'] = $this->get_filter_criteria($filter_data);

            if(isset($_filter_data)){
                $filter_data = array_merge($filter_data, $_filter_data);
            }

            if(!empty($filter_data['page_size'])) {
                $this->page_size = $filter_data['page_size'];
            }

        }

        $results = \GFAPI::get_entries($this->form_id, $search_criteria, null, ['page_size' => $this->page_size]);

        if(json_decode($excel) == true){
            // foreach results and get all of the content result formated
            $filtered_candidates = $this->get_formatted_candidates($results, $filter_data, $excel);
        } else {
            $filtered_candidates = $this->get_formatted_candidates($results, $filter_data);
        }
        if (strpos($_SERVER[ 'REQUEST_URI' ], '/wp-json/') !== false) {
            return ['candidates' => $filtered_candidates, 'total_candidates' => count($filtered_candidates)];
        } else {
            return ['candidates' => $filtered_candidates, 'total_candidates' => count($filtered_candidates), 'approved_candidates' => $this->approved_candidates, 'approved_candidates_second_part' => $this->approved_candidates_second_part];
        }
    }

    private function get_filter_criteria($filters) {
        // $cart = $filter_data['cart'];

        $search_criteria = [];
        $search_criteria['field_filters'] = array();

        // if have date filter
        // $start_date = date('Y-m-d', strtotime('-120 days'));
        // $end_date = date('Y-m-d', time());
        // $search_criteria['start_date'] = $start_date;
        // $search_criteria['end_date'] = $end_date;

        if (!empty($filters['etnia'])) {
            $search_criteria['field_filters'][] = ['key' => '28', 'value' => $filters['etnia'], 'operator' => 'in'];
        }

        if (!empty($filters['escolaridade'])) {
            $search_criteria['field_filters'][] = ['key' => '58', 'value' => $filters['escolaridade'], 'operator' => 'in'];
        }

        if (!empty($filters['deficiencia'])) {
            $search_criteria['field_filters'][] = ['key' => '121', 'value' => $filters['deficiencia'], 'operator' => 'in'];
        }

        if (!empty($filters['curso'])) {
            $cursos = array();
            $periodos = array();
            $periodos_get = array(" - Manhã - Período 1 (das 8hs as 12hs)" => "", " - Manhã - Período 2 (das 8:30hs as 12:30hs)" => "", " - Tarde - Período 1 (das 13hs as 17hs)" => "", " - Tarde - Período 2 (das 13:30hs as 17:30hs" => "", " - Noite" => "");
            foreach ($filters['curso'] as $key => $curso){
                if(strpos($curso, 'Manhã - Período 1 (das 8hs as 12hs)') !== false) {
                    $fields = \GFAPI::get_field($this->form_id, '21');
                    $key = array_search(strtr($curso, $periodos_get), array_column($fields['choices'], 'text'));
                    $cursos[] = $fields['choices'][$key]['value'];
                    $periodos[] = 'Manhã - Período 1 (das 8hs as 12hs)';
                } else if(strpos($curso, 'Manhã - Período 2 (das 8:30hs as 12:30hs)') !== false) {
                    $fields = \GFAPI::get_field($this->form_id, '21');
                    $key = array_search(strtr($curso, $periodos_get), array_column($fields['choices'], 'text'));
                    $cursos[] = $fields['choices'][$key]['value'];
                    $periodos[] = 'Manhã - Período 2 (das 8:30hs as 12:30hs)';
                } else if(strpos($curso, 'Tarde - Período 1 (das 13hs as 17hs)') !== false) {
                    $fields = \GFAPI::get_field($this->form_id, '21');
                    $key = array_search(strtr($curso, $periodos_get), array_column($fields['choices'], 'text'));
                    $cursos[] = $fields['choices'][$key]['value'];
                    $periodos[] = 'Tarde - Período 1 (das 13hs as 17hs)';
                } else if(strpos($curso, 'Tarde - Período 2 (das 13:30hs as 17:30hs') !== false) {
                    $fields = \GFAPI::get_field($this->form_id, '21');
                    $key = array_search(strtr($curso, $periodos_get), array_column($fields['choices'], 'text'));
                    $cursos[] = $fields['choices'][$key]['value'];
                    $periodos[] = 'Tarde - Período 2 (das 13:30hs as 17:30hs';
                } else if(strpos($curso, 'Noite') !== false) {
                    $fields = \GFAPI::get_field($this->form_id, '21');
                    $key = array_search(strtr($curso, $periodos_get), array_column($fields['choices'], 'text'));
                    $cursos[] = $fields['choices'][$key]['value'];
                    $periodos[] = 'Noite';
                }
            }
            $search_criteria['field_filters'][] = ['key' => '21', 'value' => array_unique($cursos), 'operator' => 'in'];
            $search_criteria['field_filters'][] = ['key' => '140', 'value' => $periodos, 'operator' => 'in'];
        }

        if (!empty($filters['unidade'])) {
            $search_criteria['field_filters'][] = ['key' => '1175', 'value' => $filters['unidade'], 'operator' => 'in'];
        }

        if (empty($filters['unidade'])) {
            if(current_user_can("professor")){
                $user_unidades = get_user_meta(get_current_user_id(), 'unidade');
                $search_criteria['field_filters'][] = ['key' => '1175', 'value' => $user_unidades[0], 'operator' => 'in'];
            }
        }

        if (!empty($filters['busca'])) {
            $search_criteria['field_filters'][] = [ 'key' => 0, 'value' => $filters['busca'], 'operator' => 'contains' ];
        }

        if (!empty($filters['id'])) {
            $search_criteria['field_filters'][] = [ 'key' => 'id', 'value' => $filters['id'] ];
        }

        if (!empty($filters['cronograma_vacinal'])) {
            $search_criteria['field_filters'][] = ['key' => '1179', 'value' => $filters['cronograma_vacinal'], 'operator' => 'in'];
        }

        if (!empty($filters['servico_social'])) {
            $search_criteria['field_filters'][] = ['key' => '1194', 'value' => $filters['servico_social'], 'operator' => 'in'];
            }

        if (!empty($filters['encaminhamento_social'])) {
            $search_criteria['field_filters'][] = ['key' => '1193', 'value' => $filters['encaminhamento_social'], 'operator' => 'in'];
        }

        if (!empty($filters['nome_servico'])) {
            $search_criteria['field_filters'][] = ['key' => '1195', 'value' => $filters['nome_servico'], 'operator' => 'in'];
        }

        if (!empty($filters['faixa_renda'])) {
            $search_criteria['field_filters'][] = ['key' => '1178', 'value' => $filters['faixa_renda'], 'operator' => 'in'];
        }

        return $search_criteria['field_filters'];
    }

    private function gfapi_array_field($field_id, $value_search, $key_search, $field_search, $return_key)
    {
        $fields = \GFAPI::get_field($this->form_id, $field_id);
        $key = array_search($value_search, array_column($fields[$key_search], $field_search));
        return $fields[$key_search][$key][$return_key];
    }

    public function get_dashboard_filters() {
        $filters = [];

        $filters['courses'] = $this->get_courses_list();
        $filters['unities'] = $this->get_unidades();
        $filters['etnias'] = $this->get_etnias();
        $filters['escolaridades'] = $this->get_escolaridades();
        $filters['deficiencias'] = $this->get_deficiencias();
        $filters['cronograma_vacinal'] = $this->get_cronograma_vacinal();
        $filters['faixa_renda'] = $this->get_faixa_renda();
        $filters['servico_social'] = $this->get_servico_social();
        $filters['encaminhamento_social'] = $this->get_encaminhamento_social();
        $filters['nome_servico'] = $this->get_nome_servico();

        return $filters;
    }


    private function get_servico_social()
    {
        $field_data = \GFAPI::get_field($this->form_id, '1194');
        $choices = $field_data->choices;
        
        $etnia = [];

        foreach($choices as $choice) {
            $etnia[$choice['value']] = $choice['text'];
        }

        return $etnia;
    }

    private function get_encaminhamento_social()
    {
        $field_data = \GFAPI::get_field($this->form_id, '1193');
        $choices = $field_data->choices;
        
        $encaminhamento_social = [];

        foreach($choices as $choice) {
            $encaminhamento_social[$choice['value']] = $choice['text'];
        }

        return $encaminhamento_social;
    }

    private function get_nome_servico()
    {
        $field_data = \GFAPI::get_field($this->form_id, '1195');
        $choices = $field_data->choices;
        
        $nome_servico = [];

    if (is_array($nome_servico) || is_object($nome_servico))
    {
        foreach($choices as $choice) {
            $nome_servico[$choice['value']] = $choice['text'];
        }
    }
        return $nome_servico;
    }

    private function get_cronograma_vacinal()
    {
        $field_data = \GFAPI::get_field($this->form_id, '1179');
        $choices = $field_data->choices;
        
        $etnia = [];

        foreach($choices as $choice) {
            $etnia[$choice['value']] = $choice['text'];
        }

        return $etnia;
    }

    private function get_faixa_renda()
    {
        $field_data = \GFAPI::get_field($this->form_id, '1178');
        $choices = $field_data->choices;
        
        $etnia = [];

        foreach($choices as $choice) {
            $etnia[$choice['value']] = $choice['text'];
        }

        return $etnia;
    }

    private function get_etnias()
    {
        $field_data = \GFAPI::get_field($this->form_id, '28');
        $choices = $field_data->choices;
        
        $etnia = [];

        foreach($choices as $choice) {
            $etnia[$choice['value']] = $choice['text'];
        }

        return $etnia;
    }

    private function get_escolaridades()
    {
        $field_data = \GFAPI::get_field($this->form_id, '58');
        $choices = $field_data->choices;
        
        $escolaridades = [];

        foreach($choices as $choice) {
            $escolaridades[$choice['value']] = $choice['text'];
        }

        return $escolaridades;
    }

    private function get_deficiencias()
    {
        $field_data = \GFAPI::get_field($this->form_id, '121');
        $choices = $field_data->choices;
        
        $deficiencias = [];

        foreach($choices as $choice) {
            $deficiencias[$choice['value']] = $choice['text'];
        }

        return $deficiencias;
    }

    private function get_unidades()
    {
        if(current_user_can("professor")){
            $user_unidades = get_user_meta(get_current_user_id(), 'unidade');

            $choices = \GFFormsModel::get_field($this->form_id, '1175')['choices'];
            
            $unidades = [];

            foreach($choices as $choice) {
                if(array_search($choice['value'], $user_unidades[0]) !== false){
                    $unidades[$choice['value']] = $choice['text'];
                }
            }
        } else if(current_user_can("administrator")){
            $choices = \GFFormsModel::get_field($this->form_id, '1175')['choices'];
            
            $unidades = [];

            foreach($choices as $choice) {
                $unidades[$choice['value']] = $choice['text'];
            }
        }

        return $unidades;
    }

    public function get_titulo_dashboard()
    {
        if(get_field('titulo')){
            return get_field('titulo');
        } else {
            return 'Lista de candidatos';
        }
    }

    public function get_descricao_dashboard()
    {
        if(get_field('descricao')){
            return get_field('descricao');
        } else {
            return '<strong>Filtre</strong> seus inscritos e <strong>aprove os candidatos</strong> clicando no <strong>botão</strong> da coluna <strong>Aprovado</strong>';
        }
    }
    public function get_img_fundo_dashboard()
    {
        if(get_field('img_fundo')){
            return get_field('img_fundo');
        } else {
            return '/wp-content/plugins/studio-ios/assets/images/dashboard-bg.png';
        }
    }

    private function get_courses_list() {
        $courses_list = [];

        $start_date = date('Y-m-d', strtotime($this->original_start_date));
        $search_criteria['start_date'] = $start_date;
        $end_date = date('Y-m-d', time());
        $search_criteria['end_date'] = $end_date;

        $results = \GFAPI::get_entries($this->form_id, $search_criteria, null, ['page_size' => 1000]);

        // The Loop
        foreach ($results as $key => $candidate) {
            $curso_get = get_post($candidate['21']);
            $courses_list[] = $curso_get->post_title . " - " . $candidate['140'];
        }

        $courses = array_unique($courses_list);

        sort($courses);

        return $courses;
    }

    private function get_formatted_candidates($results, $filter_data = null, $excel = false) {
        $candidates = [];
        $i = 0;

        foreach ($results as $key => $candidate) {
            $unidade = $this->gfapi_array_field('1175', $candidate['1175'], 'choices', 'value', 'text');
            $curso_get = get_post($candidate['21']);
            $curso = $curso_get->post_title . " - " . $candidate['140'];
            $data_inscricao = $candidate['145'];
            $candidato = $candidate['23'];
            $nome_social = $candidate['24'];
            $genero = $this->gfapi_array_field('39', $candidate['39'], 'choices', 'value', 'text');
            $idade = \DateTime::createFromFormat('Y-m-d', $candidate['29'])->diff(new \DateTime('now'))->y;
            $data_nascimento = \DateTime::createFromFormat('Y-m-d', $candidate['29'])->format('d/m/Y');
            $etnia = $this->gfapi_array_field('28', $candidate['28'], 'choices', 'value', 'text');
            $email = $candidate['34'];
            $qtd_residentes = $candidate['1182'];
            $uf = $this->gfapi_array_field('131', $candidate['131'], 'choices', 'value', 'text');
            $id = $candidate['id'];
            $data_cadastro = \DateTime::createFromFormat('Y-m-d H:i:s', $candidate['date_created']);

            $aprovado = 0;
            $aprovado_second_part = 0;
            $approved = null;
            $aprovado_get = get_entry_approved($id);
            if(!empty($aprovado_get)){
                $aprovado = 1;

                if($aprovado_get[0]->approved_second_part == 1){
                    $aprovado_second_part = 1;
                }
                $approved = $aprovado_get[0]->id;
            }

            // Filter "Aprovado 1ª Fase"
            if( !empty($filter_data['aprovado']) ) {
                if($filter_data['aprovado'] == 'todos' && $filter_data['aprovado'] !== true){
                } else if(json_decode($filter_data['aprovado']) == true && $aprovado == 0){
                    continue;
                } else if(json_decode($filter_data['aprovado']) == false && $aprovado == 1){
                    continue;
                }
            }

            // Filter "Aprovado 2ª Fase"
            if( !empty($filter_data['aprovado_second_part']) ) {
                if($filter_data['aprovado_second_part'] == 'todos' && $filter_data['aprovado_second_part'] !== true){
                } else if(json_decode($filter_data['aprovado_second_part']) == true && $aprovado_second_part == 0){
                    continue;
                } else if(json_decode($filter_data['aprovado_second_part']) == false && $aprovado_second_part == 1){
                    continue;
                }
            }

            // Filter "Importado"
            if( isset($filter_data['imported']) ) {
                if( $filter_data['imported'] == false && $aprovado_get[0]->imported == 1 ){
                    continue;
                } else if ( $filter_data['imported'] == true && $aprovado_get[0]->imported != 1){
                    continue;
                }
            }
            
            // Filter "is_approved_for_import"
            if( isset($filter_data['is_approved_for_import'])  ) {
                if( $aprovado_get[0]->is_approved_for_import != 1 && $filter_data['is_approved_for_import'] == true ){
                    continue;
                } else if( $aprovado_get[0]->is_approved_for_import == 1 && $filter_data['is_approved_for_import'] == false ){
                    continue;
                }
            }
            
            // Filter "teacher_import_id"
            if( isset($filter_data['teacher_import_id']) ) {
                if($aprovado_get[0]->teacher_import_id != $filter_data['teacher_import_id']){
                    continue;
                }
            }

            // Filter "Idade"
            if( !empty($filter_data['idade'][0]) || !empty($filter_data['idade'][1]) ) {
                if( !empty($filter_data['idade'][0]) ) {
                    $idade_min = $filter_data['idade'][0];
                } else {
                    $idade_min = 0;
                }
                if( !empty($filter_data['idade'][1]) ) {
                    $idade_max = $filter_data['idade'][1];
                } else {
                    $idade_max = 99;
                }
                if( 
                    ( $idade_max == 0 && $idade < $idade_min ) ||
                    ( $idade_max != 0 && ( $idade < $idade_min || $idade > $idade_max ) )
                ) {
                    continue;
                }
            } 

            // Filter "Data de Cadastro"
            if( !empty($filter_data['data_cadastro'][0]) || !empty($filter_data['data_cadastro'][1]) ) {
                if( !empty($filter_data['data_cadastro'][0]) ){
                    $data_cadastro_min = \DateTime::createFromFormat('Y-m-d', $filter_data['data_cadastro'][0]);
                } else {
                    $data_cadastro_min = \DateTime::createFromFormat('Y-m-d', date('2000-01-01'));
                }
                if( !empty($filter_data['data_cadastro'][1]) ){
                    $data_cadastro_max = \DateTime::createFromFormat('Y-m-d', $filter_data['data_cadastro'][1]);
                } else {
                    $data_cadastro_max = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
                }
                if($data_cadastro->format('U') > $data_cadastro_min->format('U') && $data_cadastro->format('U') < $data_cadastro_max->format('U')){
                } else {
                    continue;
                }
            }


            if(!empty($aprovado_get)){
                $this->approved_candidates++;
                if($aprovado_get[0]->approved_second_part == 1){
                    $this->approved_candidates_second_part++;
                }
            }


            $grau_escolaridade = $this->gfapi_array_field('58', $candidate['58'], 'choices', 'value', 'text');
            if (!empty($candidate['61'])) $serie = $candidate['58'] == 3 ? $this->gfapi_array_field('61', $candidate['61'], 'choices', 'value', 'text') : '-';  else $serie = "-";
            $andamento = intval($candidate['64']) . ' - ' . $this->gfapi_array_field('64', $candidate['64'], 'choices', 'value', 'text');
            $instituicao_publica_privada = $candidate['66'] . ' - ' . $this->gfapi_array_field('66', $candidate['66'], 'choices', 'value', 'text');
            $ano = $this->gfapi_array_field('62', $candidate['62'], 'choices', 'value', 'text');
            if (!empty($candidate['58'])) $universidade = $candidate['58'] == 3 ? $candidate['65'] : '-';  else $universidade = "-";
            if (!empty($candidate['68'])) $valor_bolsa = $candidate['58'] == 3 ? $this->gfapi_array_field('68', $candidate['68'], 'choices', 'value', 'text') : '-';  else $valor_bolsa = "-";
            $tel_celular = !empty($candidate['47']) ? '(' . $candidate['46'] . ')' . $candidate['47'] : '';
            $cep = substr($candidate['49'], 0, 5) . '-' . substr($candidate['49'], 5, 7);
            $endereco = $candidate['50'] . ', ' . $candidate['52'];
            if (!empty($candidate['53'])) $endereco = $endereco . ' - ' . $candidate['53'];
            $bairro = $candidate['54'];
            $municipio = $candidate['56'];
            $deficiente = $this->gfapi_array_field('41', $candidate['41'], 'choices', 'value', 'text');
            $tipo_deficiencia = $candidate['41'] == 1 ? $candidate['121'] . ' - ' . $this->gfapi_array_field('121', $candidate['121'], 'choices', 'value', 'text') : '-';
            $servico_social = !empty($candidate['1194']) ? $this->gfapi_array_field('1194', $candidate['1194'], 'choices', 'value', 'text') : '-';
            $encaminhamento_social = !empty($candidate['1193']) ? $this->gfapi_array_field('1193', $candidate['1193'], 'choices', 'value', 'text') : '-';
           // $nome_servico = !empty($candidate['1195']) ? $this->gfapi_array_field('1195', $candidate['1195'], 'choices', 'value', 'text') : '-';
            if (!empty($candidate['1194'])) $nome_servico = $candidate['1194'] == 1 ? $candidate['1195'] : '-';  else $nome_servico = "-";
            $faixa_renda_familiar = $this->gfapi_array_field('1178', $candidate['1178'], 'choices', 'value', 'text');
            $vacinacao_covid = $this->gfapi_array_field('1179', $candidate['1179'], 'choices', 'value', 'text');
            $renda_familiar = $candidate['1197'];
            if(!empty($aprovado_get)){
                $importado = $aprovado_get[0]->imported;
                $is_approved_for_import = $aprovado_get[0]->is_approved_for_import;
                $teacher_import_id = $aprovado_get[0]->teacher_import_id;
                $approved_date = $aprovado_get[0]->approved_date;
            } else {
                unset($teacher_import_id);
                unset($approved_date);
            }


            $candidates[] = [
                'id' => $id,
                'aprovado' => (json_decode($excel)==true) ? ($aprovado==1 ? 'Sim' : 'Não') : $aprovado,
                'aprovado_second_part' => (json_decode($excel)==true) ? ($aprovado_second_part==1 ? 'Sim' : 'Não') : $aprovado_second_part,
                'data_inscricao' => $data_inscricao,
                'importado' => (json_decode($excel)==true) ? ($importado==1 ? 'Sim' : 'Não') : '',
                'aprovado_para_importar' => (json_decode($excel)==true) ? ($is_approved_for_import==1 ? 'Sim' : 'Não') : '',
                'professor_que_aprovou' => (json_decode($excel)==true) ? (isset($teacher_import_id) ? get_user_by( 'id', $teacher_import_id )->data->display_name : '-') : '',
                'data_aprovacao' => (json_decode($excel)==true) ? (isset($teacher_import_id) ? $approved_date : '-') : '',
                'curso' => $curso,
                'unidade' => $unidade,
                'candidato' => $candidato,
                'data_nascimento' => $data_nascimento,
                'nome_social' => $nome_social,
                'genero' => $genero,
                'idade' => $idade,
                'etnia' => $etnia,
                'email' => $email,
                'grau_escolaridade' => $grau_escolaridade,
                'serie' => $serie,
                'ano' => $ano,
                'andamento' => $andamento,
                'instituicao_publica_privada' => $instituicao_publica_privada,
                'universidade' => $universidade,
                'valor_bolsa' => $valor_bolsa,
                'tel_celular' => $tel_celular,
                'cep' => $cep,
                'endereco' => $endereco,
                'bairro' => $bairro,
                'municipio' => $municipio,
                'uf' => $uf,
                'qtd_residentes' => $qtd_residentes,
                'deficiente' => $deficiente,
                'tipo_deficiencia' => $tipo_deficiencia,
                'servico_social' => $servico_social,
                'encaminhamento_social' => $encaminhamento_social,
                'nome_servico' => $nome_servico,
                'faixa_renda_familiar' => $faixa_renda_familiar,
                'vacinacao_covid' => $vacinacao_covid,
                'renda_familiar' => $renda_familiar,
            ];
        

        }


        return $candidates;
    }

}
