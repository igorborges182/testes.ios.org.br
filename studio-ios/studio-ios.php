<?php
/*
Plugin Name: Funcionalidades Studio Visual
Plugin URI: https://studiovisual.com.br
Description: Plugin de incremento de funcionalidades ao sistema.
Author: Studio Visual
Version: 1.0.4
Text Domain: studio-ios
Author URI: https://studiovisual.com.br/
*/

// TODO: move those includes to dashboard-template
include( plugin_dir_path( __FILE__ ) . 'templates/db_functions.php') ;
include( plugin_dir_path( __FILE__ ) . 'app/database/ApprovedCandidates.php');
include( plugin_dir_path( __FILE__ ) . 'app/Controllers/Dashboard.php');
include( plugin_dir_path( __FILE__ ) . 'app/filters.php');
include( plugin_dir_path( __FILE__ ) . 'app/Controllers/EndpointBase.php');
include( plugin_dir_path( __FILE__ ) . 'app/Controllers/Endpoint.php');
include( plugin_dir_path( __FILE__ ) . 'app/Controllers/Elementor_IOS_Product_Widget_Register.php') ;

require_once plugin_dir_path( __FILE__ ) . "libs/Mobile_Detect.php";

register_activation_hook( __FILE__, 'dashboardTables' );

function dashboardTables() {
    // call function table from app/database/ApprovedCandidates.php
    tables();
}


// add the template for teachers dashboard
/**
 * Add "Dashboard Aprovação" template to page attirbute template section.
 */
add_filter( 'theme_page_templates', 'studio_admin_template_dashboard', 10, 4 );
function studio_admin_template_dashboard( $post_templates, $wp_theme, $post, $post_type ) {

    // Add Dashboard Aprovação template named template-dashboard.php to select dropdown 
    $post_templates['template-dashboard.php'] = __('Dashboard Aprovação');

    return $post_templates;
}

//Load template from specific page and enqueue css and js
add_filter( 'page_template', 'studio_front_template_dashboard' );
function studio_front_template_dashboard( $page_template ){

    if ( get_page_template_slug() == 'template-dashboard.php' ) {
        $page_template = dirname( __FILE__ ) . '/templates/template-dashboard.php';
        wp_enqueue_style('dashboard-aprovacao', plugins_url('/studio-ios/assets/css/dashboard.css'));

        wp_enqueue_script( 'dashboard-aprovacao', plugins_url('/studio-ios/assets/js/dashboard.js'), ['jquery'], '1.0.0', true );
        wp_localize_script('dashboard-aprovacao', 'wp', ['ajax_url' => admin_url('admin-ajax.php'),'nonce' => wp_create_nonce('ajax-nonce')]);

        // Select 2 css e js
        wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
        wp_enqueue_script( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['jquery'], '1.0.0', true );

        // Modal css and js
        wp_enqueue_style('modal', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css');
        wp_enqueue_script( 'modal', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js', ['jquery'], '1.0.0', true );

        // datatable css and js
        wp_enqueue_style('datatable', 'https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css');
        wp_enqueue_script( 'datatable', 'https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js', ['jquery'], '1.0.0', true );

        // datatable fixed columns
        wp_enqueue_script( 'datatable-fixedColumns', 'https://cdn.datatables.net/fixedcolumns/3.3.3/js/dataTables.fixedColumns.min.js', ['jquery'], '1.0.0', true );

        wp_enqueue_script( 'exportTable', plugins_url('/studio-ios/assets/js/jquery.tabletocsv.js'), ['jquery'], '1.0.0', true );

        // Google Font
        wp_enqueue_style('googl-font', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&display=swap');

        // jQuery Mask
        wp_enqueue_script( 'jQueryMask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', ['jquery'], '1.0.0', true );

        // Moment.JS
        wp_enqueue_script( 'Moment.JS', plugins_url('/studio-ios/assets/js/moment.js'), ['jquery'], '1.0.0', true );

        // DateTimeMoment
        wp_enqueue_script( 'DateTimeMoment', plugins_url('/studio-ios/assets/js/datetime-moment.js'), ['jquery'], '1.0.0', true );

        // sheetjs
        wp_enqueue_script( 'sheetjs', plugins_url('/studio-ios/assets/js/xlsx.full.min.js'), ['jquery'], '1.0.0', true );

        // filesaver
        wp_enqueue_script( 'filesaver', plugins_url('/studio-ios/assets/js/FileSaver.min.js'), ['jquery'], '1.0.0', true );

    }
    return $page_template;
}

/**
 * loadTextdomain Load languages
 *
 * @return void
 */
function loadTextdomain(): void {
    load_plugin_textdomain('studio-ios', false, 'studio-ios' . '/'); 
}

function init(): void {
    loadTextdomain();
}
add_action('init', 'init');

// Dependencias
function scripts_courses_js() {
    wp_enqueue_script( 'script-courses', plugins_url('/studio-ios/assets/js/script.js'), ['jquery'], '1.0.0', true );
    wp_enqueue_style('styles', plugins_url('/studio-ios/assets/css/style.css'));
    
    // Dependencies Owl Carrousel
    wp_enqueue_script( 'owl-carrousel-js', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', ['jquery'], '1.0.0', true );
    wp_enqueue_style('owl-carrousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css');
    wp_enqueue_style('theme-carrousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css');

    // jQuery Mask
    wp_enqueue_script( 'jQueryMask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', ['jquery'], '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'scripts_courses_js' );


function childtheme_custom_login() {
    // enqueue css for wp-login/wp-admin page
    wp_enqueue_style('styles', plugins_url('/studio-ios/assets/css/login.css'));

    $logo_id = get_theme_mod( 'custom_logo' );
    $image = wp_get_attachment_image_src( $logo_id );

    if(!empty($image[0])) {
        // get the logo from admin and override the wordpress logo
        echo '
        <style type="text/css">                                                                   
            .login h1 a { 
                background-image:url('.$image[0].') !important; 
            }                            
        </style>';
    }

}
add_action('login_head', 'childtheme_custom_login');


function gettext_filter($translation, $orig, $domain) {
    switch($orig) {
        case 'Username or Email Address':
            $translation = "Email";
            break;
        case 'Password':
            $translation = 'Senha';
            break;
        case 'Log In':
            $translation = 'Entrar';
            break;
    }
    return $translation;
}
add_filter('gettext', 'gettext_filter', 10, 3);

if ( ! function_exists( 'local_taxonomy' ) ) {
    // Register Custom Taxonomy
    function local_taxonomy() {
        $labels = array(
            'name'                       => _x( 'Unidades', 'Taxonomy General Name', 'studio-ios' ),
            'singular_name'              => _x( 'Unidade', 'Taxonomy Singular Name', 'studio-ios' ),
            'menu_name'                  => __( 'Unidades', 'studio-ios' ),
            'all_items'                  => __( 'Todas as unidades', 'studio-ios' ),
            'parent_item'                => __( 'Parent Item', 'studio-ios' ),
            'parent_item_colon'          => __( 'Parent Item:', 'studio-ios' ),
            'new_item_name'              => __( 'Nova unidade', 'studio-ios' ),
            'add_new_item'               => __( 'Adicionar nova unidade', 'studio-ios' ),
            'edit_item'                  => __( 'Editar unidade', 'studio-ios' ),
            'update_item'                => __( 'Atualizar unidade', 'studio-ios' ),
            'view_item'                  => __( 'Visualizar unidade', 'studio-ios' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'studio-ios' ),
            'add_or_remove_items'        => __( 'Adicionar ou Remover unidade', 'studio-ios' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'studio-ios' ),
            'popular_items'              => __( 'Popular Items', 'studio-ios' ),
            'search_items'               => __( 'Procurar unidade', 'studio-ios' ),
            'not_found'                  => __( 'Não encontrando', 'studio-ios' ),
            'no_terms'                   => __( 'No items', 'studio-ios' ),
            'items_list'                 => __( 'Lista de unidades', 'studio-ios' ),
            'items_list_navigation'      => __( 'Items list navigation', 'studio-ios' ),
        );

        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => false,
        );

        register_taxonomy( 'local', array( 'cursos' ), $args );
    }
    
    add_action( 'init', 'local_taxonomy', 0 );
}


if ( ! function_exists('course_post_type') ) {
    // Register Custom Post Type
    function course_post_type() {
        $labels = array(
            'name'                  => _x( 'Cursos', 'Post Type General Name', 'studio-ios' ),
            'singular_name'         => _x( 'Curso', 'Post Type Singular Name', 'studio-ios' ),
            'menu_name'             => __( 'Cursos', 'studio-ios' ),
            'name_admin_bar'        => __( 'Cursos', 'studio-ios' ),
            'archives'              => __( 'Item Archives', 'studio-ios' ),
            'attributes'            => __( 'Item Attributes', 'studio-ios' ),
            'parent_item_colon'     => __( 'Parent Item:', 'studio-ios' ),
            'all_items'             => __( 'Todos os Cursos', 'studio-ios' ),
            'add_new_item'          => __( 'Adicionar novo curso', 'studio-ios' ),
            'add_new'               => __( 'Adicionar novo', 'studio-ios' ),
            'new_item'              => __( 'Novo curso', 'studio-ios' ),
            'edit_item'             => __( 'Editar curso', 'studio-ios' ),
            'update_item'           => __( 'Atualizar Curso', 'studio-ios' ),
            'view_item'             => __( 'Visualizar curso', 'studio-ios' ),
            'view_items'            => __( 'Visualizar cursos', 'studio-ios' ),
            'search_items'          => __( 'Procurar curso', 'studio-ios' ),
            'not_found'             => __( 'Não encontrado', 'studio-ios' ),
            'not_found_in_trash'    => __( 'Não encontrado na lixeira', 'studio-ios' ),
            'featured_image'        => __( 'Imagem destacada', 'studio-ios' ),
            'set_featured_image'    => __( 'Definir imagem destacada', 'studio-ios' ),
            'remove_featured_image' => __( 'Remove imagem destacada', 'studio-ios' ),
            'use_featured_image'    => __( 'Usar como imagem destacada', 'studio-ios' ),
            'insert_into_item'      => __( 'Insert into item', 'studio-ios' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'studio-ios' ),
            'items_list'            => __( 'Lista de cursos', 'studio-ios' ),
            'items_list_navigation' => __( 'Items list navigation', 'studio-ios' ),
            'filter_items_list'     => __( 'Filter items list', 'studio-ios' ),
        );

        $args = array(
            'label'                 => __( 'Curso', 'studio-ios' ),
            'description'           => __( 'Cursos', 'studio-ios' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'custom-fields' ),
            'taxonomies'            => array( 'local' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 20,
            'menu_icon'             => 'dashicons-book',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
        );

        register_post_type( 'course', $args );
    }

    add_action( 'init', 'course_post_type', 0 );
}


function input_courses( $input, $field, $value, $lead_id, $form_id ) {
    if ( $field['id'] == 21 ){
        // Cursos
        $args = [
            'post_type' => 'course',
            'posts_per_page' => -1,
            'meta_key' => 'disponibilidade',
            'meta_value' => 1,
            'orderby' => 'title',
            'order' => 'ASC'
        ];
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            $input = '<select name="input_21" id="input_21" class="medium gfield_select" aria-required="true" aria-invalid="false">';
            $input .= '<option value="" '. (isset($value) ? "selected=selected" : "") .' class="gf_placeholder">'.__("Selecione...", "studio-ios" ).'</option>';
                    foreach ($query->posts as $curso) {
                        if(isset(get_field('variacao', $curso->ID)[0]['unidades'])){
                            $input .= '<option value="'.$curso->ID.'" '. ($value == $curso->ID ? "selected=selected" : "") .'>'.$curso->post_title.'</option>';
                        }
                    }
            $input .= '</select>';
        } else {
            $input = '<h3 class="indisponivel">Não há cursos disponíveis.</h3>';
        }

        wp_reset_postdata();
 
    }

    return $input;
}
add_filter( 'gform_field_input', 'input_courses', 10, 5 );

add_action( 'wp_ajax_nopriv_lista_unidades', 'ajax_lista_unidades' );
add_action( 'wp_ajax_lista_unidades', 'ajax_lista_unidades' );
function ajax_lista_unidades() {
    $args = [
        'post_type' => 'course',
        'posts_per_page' => -1,
        'meta_key' => 'disponibilidade',
        'meta_value' => 1,
        'p' => $_POST['curso'],
        'orderby' => 'title',
        'order' => 'ASC'
    ];
    $query = new WP_Query( $args );

    $unidades = array();

    $input = "";

    if(isset(get_field('variacao', $query->posts[0]->ID)[0]['unidades'])){
        if ( $query->have_posts() ) {
            $input .= '<option value="" selected="selected" class="gf_placeholder">'.__("Selecione...", "studio-ios" ).'</option>';
            foreach ($query->posts as $post) {
                foreach(get_field('variacao', $post->ID) as $variacao){
                    //Obtem string com locais dos cursos
                    foreach($variacao['unidades'] as $unidade){
                        if(!in_array($unidade, $unidades)){
                            $unidades[] = $unidade;
                        }
                    }
                }
            }
            sort($unidades);

            foreach ($unidades as $unidade) {
                $unidade_term = get_term( $unidade );
                $input .= '<option value="'.$unidade_term->term_id.'">'.$unidade_term->name.'</option>';
            }
        }
    }

    echo $input;
    
    wp_reset_postdata();

    die();
}

add_action( 'wp_ajax_nopriv_lista_horarios', 'ajax_lista_horarios' );
add_action( 'wp_ajax_lista_horarios', 'ajax_lista_horarios' );
function ajax_lista_horarios() {

    $forms = GFAPI::get_forms();

    $rules_courses = array();
    foreach ($forms as $key => $form) {
        if($form['id'] === 13){
            $fields = array();
            foreach ( $form['fields'] as $field ) {
                if ( $field['id'] == 140 || $field['id'] == 1184 ) {
                    foreach ( $field['conditionalLogic']['rules'] as $rule ) {
                        if(!in_array($rule['value'], $rules_courses)){
                            $rules_courses[] = $rule['value'];
                        }
                    }
                }
            }
        }
    }
    if(in_array($_POST['unidade'], $rules_courses)){
        echo 'curso_indisponivel';
        die();
    }
    $args = [
        'post_type' => 'course',
        'posts_per_page' => -1,
        'meta_key' => 'disponibilidade',
        'meta_value' => 1,
        'p' => $_POST['curso'],
        'orderby' => 'title',
        'order' => 'ASC'
    ];
    $query = new WP_Query( $args );

    $unidades = array();

    if ( $query->have_posts() ) {

        foreach ($query->posts as $post) {


            foreach(get_field('variacao', $post->ID) as $variacao){
                foreach($variacao['unidades'] as $unidade){
                    if($unidade == intval($_POST['unidade'])){
                        $input = '<input name="input_140" id="input_'.mt_rand().'_140" type="hidden" value="" class="medium" aria-required="true" aria-invalid="false">';
                        foreach($variacao['periodo'] as $periodo){
                            if($periodo == "Manhã - Período 1 (das 8hs as 12hs)"){
                                $periodo = '<p><strong>' . __( 'Manhã - Período 1', 'studio-ios' ) . '</strong> - 08:00 ' . __( 'às', 'studio-ios' ) . ' 12:00</p>';
                                $data_periodo = __( 'Manhã - Período 1', 'studio-ios' );
                            } else if($periodo == "Manhã - Período 2 (das 8:30hs as 12:30hs)"){
                                $periodo = '<p><strong>' . __( 'Manhã - Período 2', 'studio-ios' ) . '</strong> - 08:30 ' . __( 'às', 'studio-ios' ) . ' 12:30</p>';
                                $data_periodo = __( 'Manhã - Período 2', 'studio-ios' );
                            } else if ($periodo == "Tarde - Período 1 (das 13hs as 17hs)"){
                                $periodo = '<p><strong>' . __( 'Tarde - Período 1', 'studio-ios' ) . '</strong> - 13:00 ' . __( 'às', 'studio-ios' ) . ' 17:00</p>';
                                $data_periodo = __( 'Tarde - Período 1', 'studio-ios' );
                            } else if ($periodo == "Tarde - Período 2 (das 13:30hs as 17:30hs)"){
                                $periodo = '<p><strong>' . __( 'Tarde - Período 2', 'studio-ios' ) . '</strong> - 13:30 ' . __( 'às', 'studio-ios' ) . ' 17:30</p>';
                                $data_periodo = __( 'Tarde - Período 2', 'studio-ios' );
                            } else if($periodo == "Noite"){
                                $periodo = '<p><strong>' . __( 'Noite', 'studio-ios' ) . '</strong> - 18:00 ' . __( 'às', 'studio-ios' ) . ' 22:00</p>';
                                $data_periodo = __( 'Noite', 'studio-ios' );
                            }

                            $input2 .= '<div data-course-title="'.$post->post_title.'" class="course" data-periodo="'.$data_periodo.'" data-course-id="'.$post->ID.'" data-locais="'.$data_terms.'">';
                            $input2 .= '<h3>'.$post->post_title.'</h3>';
                            $unidade_term = get_term( intval($_POST['unidade']) );
                            $input2 .= '<p><strong>' . __( 'Unidade', 'studio-ios' ) . '</strong> - ' . $unidade_term->name .'</p>';
                            $input2 .= $periodo;
                            $input2 .= '<p><strong>' . __( 'Idade', 'studio-ios' ) . '</strong> - ' . $variacao['idade'] .'</p>';
                            $input2 .= '<p><strong>' . __( 'Aulas', 'studio-ios' ) . '</strong> - '. $variacao['modalidade'] .'</p>';
                            $input2 .= '</div>';
                        }
                    } else {
                        $input = false;
                    }
                }
            }
        }
    }

    echo $input.$input2;

    wp_reset_postdata();

    die();
}

function listcourses() {
    $args = array(
        'post_type' => 'course',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_key' => 'disponibilidade',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
    );
    $the_query = new WP_Query( $args );
    $boxes = '';
    if ( $the_query->have_posts() ){
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            if(get_field('disponibilidade') == true || (get_field('disponibilidade') == false && array_search(get_the_ID(), get_field('ios_config_page_group', 'option')['cursos_indisponiveis']) === false)){
                $boxes .= '
                    <div class="bxcourses d-flex">
                        <div class="line header">
                            <img src="'.get_field('icone_do_curso').'" alt="Icone do Curso '.get_the_title().'"" />
                            <h2>'.get_the_title().'</h2>
                        </div>

                        <div class="line ementa">
                            '.get_field('ementa').'
                        </div>';

                if(get_field('disponibilidade') == true){
                    $boxes .= '
                        <div class="line infos">
                            <div class="col-1">
                                <h4>' . __( 'Carga horária', 'studio-ios' ) . '</h4>
                                <div class="colunas">                            
                                    <img src="'.plugins_url('/studio-ios/assets/images/clock.png').'" alt="Relógio" />
                                    <p>'.get_field('carga_horaria').'</p>
                                </div>
                            </div>

                            <div class="col-2">
                                <h4>' .__( 'Duração total', 'studio-ios' ) . '</h4>
                                <div class="colunas">
                                    <img src="'.plugins_url('/studio-ios/assets/images/hourglass.png').'" alt="Relógio de areia" />
                                    <p>'.get_field('duracao_total').'</p>
                                </div>
                            </div>

                            <div class="col-3">
                                <h4>' .__( 'Períodos', 'studio-ios' ) . '</h4>';
                                $periodos = get_field('periodos');
                                $disponible = get_field('disponibilidade');

                                if ($disponible != 1) {
                                    $boxes .= '
                                        <div class="colunas" style="width:100%">
                                            <img src="'.plugins_url('/studio-ios/assets/images/calendar-slash.png').'" alt="Indisponível neste semestre" />
                                            <p>' .__( 'Indisponível neste semestre', 'studio-ios' ) . '</p>
                                        </div>
                                    ';
                                }
                                
                                if($disponible == 1) {
                                    foreach ($periodos as $periodo) {
                                        if($periodo == "Manhã - Período 1 (das 8hs as 12hs)"){
                                            $boxes .='
                                            <div class="colunas">
                                                <img src="'.plugins_url('/studio-ios/assets/images/sunset.png').'" alt="Icone de Sol da Manhã" />
                                                <p>'.$periodo.'</p>
                                            </div>';
                                        }elseif($periodo == "Manhã - Período 2 (das 8:30hs as 12:30hs)"){
                                            $boxes .='
                                            <div class="colunas">
                                                <img src="'.plugins_url('/studio-ios/assets/images/sunset.png').'" alt="Icone de Sol da Manhã" />
                                                <p>'.$periodo.'</p>
                                            </div>';
                                        }elseif ($periodo == 'Tarde - Período 1 (das 13hs as 17hs)') {
                                            $boxes .='
                                            <div class="colunas">
                                                <img src="'.plugins_url('/studio-ios/assets/images/sun.png').'" alt="Icone de Sol da Tarde" />
                                                <p>'.$periodo.'</p>
                                            </div>';
                                        }elseif ($periodo == 'Tarde - Período 2 (das 13:30hs as 17:30hs)') {
                                            $boxes .='
                                            <div class="colunas">
                                                <img src="'.plugins_url('/studio-ios/assets/images/sun.png').'" alt="Icone de Sol da Tarde" />
                                                <p>'.$periodo.'</p>
                                            </div>';
                                        }else {
                                            $boxes .='
                                            <div class="colunas">
                                                <img src="'.plugins_url('/studio-ios/assets/images/moonset.png').'" alt="Icone de Sol da Tarde" />
                                                <p>'.$periodo.'</p>
                                            </div>';
                                        }
                                    }
                                }
                            $boxes .='
                            </div>
                        </div>';
                        
                        $terms = get_the_terms( get_the_ID(), 'local' );
                        $boxes .= '
                        <div class="line classroom">
                            <h4>' .__( 'Unidades', 'studio-ios' ) . '</h4>
                            <div class="more-cols">';
                                foreach ($terms as $index => $term) {
                                    $boxes .= '
                                    <div class="cols">
                                        <span class="title">'.$term->name.'</span>
                                        <address>'.$term->description.'</address>
                                    </div>
                                    ';
                                }
                                
                            $boxes .='
                            </div>
                        </div>
                    </div>';
                } else {
                    $boxes .= '<div class="formulario_captura_lead">';
                    $boxes .= '<h3>' .get_field('ios_config_page_group', 'option')['msg_indisponivel'].'</h3>';
                    $boxes .= '<p><strong>'.get_field('ios_config_page_group', 'option')['msg_aviseme']. '</strong></p>';
                    $boxes .= do_shortcode('[gravityform id="'.get_field('ios_config_page_group', 'option')['formulario'].'" title="false" description="false" ajax="true" field_values="curso='.get_the_title().'"]');
                    $boxes .= '</div>';
                    $boxes .= '</div>';
                }
            }
        }
    }
    wp_reset_postdata();
    return $boxes;
}
add_shortcode('boxcursos', 'listcourses');

/////
// Criando página no tema de op~ções
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'    => 'Configurações do Tema',
        'menu_title'    => 'Configurações do Tema',
        'menu_slug'     => 'ios_config_page',
        'capability'    => 'edit_posts',
        'redirect'      => false,
        'update_button' => __('Salvar', 'acf'),
        'updated_message' => __("Alterações Salvas", 'acf'),
    ));
}

/////
// Criando campos na página de configurações do tema
function acf_ios_config_page() {
    if( function_exists('acf_add_local_field_group') ):


        $forms = GFAPI::get_forms();
        $forms_choices = [];

        foreach ($forms as $form) {
            $forms_choices += array($form['id'] => $form['title']);
        }

        $cursos = new WP_Query([
          'post_type' => 'course',
          'post_status' => 'publish',
          'posts_per_page' => -1,
          'order'    => 'ASC'
        ]);
        $cursos_choices = [];

        foreach ($cursos->posts as $curso) {
            $cursos_choices += array($curso->ID => $curso->post_title);
        }
        asort($cursos_choices);
        
        acf_add_local_field_group(array(
            'key'      => 'ios_config_page_fields',
            'name'     => 'ios_config_page_fields',
            'title'    => 'Configurações Captura de Lead',
            'fields'   => array (
                    array (
                        'key' => 'ios_config_page_group',
                        'name' => 'ios_config_page_group',
                        'title' => 'Configurações Captura de Lead',
                        'type' => 'group',
                        'layout' => 'block',
                        'sub_fields' => array (
                            array (
                                'key' => 'formulario',
                                'label' => 'Formulário de Captura de Leads',
                                'instructions' => 'Selecione o formulário que será exibido para captura de leads.',
                                'name' => 'formulario',
                                'type' => 'select',
                                'choices' => $forms_choices,
                                'multiple' => 0,
                                'ui' => 1,
                                'required' => true,
                                'wrapper' => array (
                                    'width' => '30%'
                                )
                            ),
                            array (
                                'key' => 'cursos_indisponiveis',
                                'label' => 'Selecione os cursos que não serão listados',
                                'instructions' => 'Selecione os cursos que não serão listados caso estejam indisponíveis. O curso que <b>não</b> estiver selecionado abaixo exibirá o formulário de captação de Lead quando indisponível.',
                                'name' => 'cursos_indisponiveis',
                                'type' => 'select',
                                'choices' => $cursos_choices,
                                'multiple' => 1,
                                'ui' => 0,
                                'wrapper' => array (
                                    'width' => '70%'
                                )
                            ),
                            array (
                                'key' => 'msg_indisponivel',
                                'label' => 'Título Indisponível',
                                'instructions' => 'Escreva o título que vai aparecer mostrando que o curso está indisponível.',
                                'name' => 'msg_indisponivel',
                                'type' => 'text',
                                'default_value' => 'Indisponível no momento',
                                'required' => true
                            ),
                            array (
                                'key' => 'msg_aviseme',
                                'label' => 'Mensagem Avise-me',
                                'instructions' => 'Escreva a mensagem de avise-me quando o curso estiver disponível.',
                                'name' => 'msg_aviseme',
                                'type' => 'text',
                                'default_value' => 'Avise-me quando estiver disponível',
                                'required' => true
                            )
                        ),
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'ios_config_page',
                        ),
                    ),
                ),
        ));

    endif;
}
add_action( 'acf/init', 'acf_ios_config_page' );


function carrouselcourses(){
    $args = array(
        'post_type' => 'course',
        'posts_per_page' => -1,
        'meta_key' => 'disponibilidade',
        'meta_value' => 1,
    );
    $the_query = new WP_Query( $args );
    $carrousel = '<div class="container-owl">';
    $carrousel .= '<div class="owl-carousel">';
    if ( $the_query->have_posts() ){
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            $carrousel .= '
                <div class="card">
                    <img src="'.get_field('icone_do_curso').'" alt="Icone do Curso '.get_the_title().'"" />
                    <h3>'.get_the_title().'</h3>'
                    .get_field('objetivos_gerais_').
                    '<a href="/para-voce/#cursos">' . __( 'Veja locais disponíveis', 'studio-ios') . '&#10140;</a>
                </div>
            ';
        }
    }
    $carrousel .= '</div></div>';
    wp_reset_postdata();
    return $carrousel;
}
add_shortcode('owlcourses', 'carrouselcourses');

/**
* Gravity Wiz // Calculate Number of Days Between Two Gravity Form Date Fields
*
* Allows you to calculated the number of days between two Gravity Form date fields and populate that number into a
* field on your Gravity Form.
*
* @version   1.1
* @author    David Smith <david@gravitywiz.com>
* @license   GPL-2.0+
* @link      http://gravitywiz.com/calculate-number-of-days-between-two-dates/
* @copyright 2013 Gravity Wiz
*/
class GWDayCount {

    private static $script_output;

    function __construct( $args ) {
        extract( wp_parse_args( $args, 
            [
                'form_id'          => false,
                'start_field_id'   => false,
                'end_field_id'     => false,
                'count_field_id'   => false,
                'include_end_date' => true,
            ]
        ));

        $this->form_id        = $form_id;
        $this->start_field_id = $start_field_id;
        $this->end_field_id   = $end_field_id;
        $this->count_field_id = $count_field_id;
        $this->count_adjust   = $include_end_date ? 1 : 0;

        add_filter( "gform_pre_render_{$form_id}", [&$this, 'load_form_script']);
        add_action( "gform_pre_submission_{$form_id}", [&$this, 'override_submitted_value']);

    }

    function load_form_script( $form ) {
        $this->form = $form;
        add_filter( 'gform_init_scripts_footer', 
            [
                &$this, 
                'add_init_script'
            ]
        );

        if( self::$script_output )
            return $form;

        ?>

        <script type="text/javascript">

        (function($){

            window.gwdc = function( options ) {

                this.options = options;
                this.startDateInput = $( '#input_' + this.options.formId + '_' + this.options.startFieldId );
                this.endDateInput = $( '#input_' + this.options.formId + '_' + this.options.endFieldId );
                this.countInput = $( '#input_' + this.options.formId + '_' + this.options.countFieldId );

                this.init = function() {

                    var gwdc = this;

                    // add data for "format" for parsing date
                    gwdc.startDateInput.data( 'format', this.options.startDateFormat );
                    gwdc.endDateInput.data( 'format', this.options.endDateFormat );

                    gwdc.populateDayCount();

                    gwdc.startDateInput.change( function() {
                        gwdc.populateDayCount();
                    } );

                    gwdc.endDateInput.change( function() {
                        gwdc.populateDayCount();
                    } );

                    $( '#ui-datepicker-div' ).hide();

                }

                this.getDayCount = function() {

                    var startDate = this.parseDate( this.startDateInput.val(), this.startDateInput.data('format') )
                    var endDate = this.parseDate( this.endDateInput.val(), this.endDateInput.data('format') );
                    var dayCount = 0;

                    if( !this.isValidDate( startDate ) || !this.isValidDate( endDate ) )
                        return '';

                    if( startDate > endDate ) {
                        return 0;
                    } else {

                        var diff = endDate - startDate;
                        dayCount = diff / ( 60 * 60 * 24 * 1000 ); // secs * mins * hours * milliseconds
                        dayCount = Math.round( dayCount ) + this.options.countAdjust;

                        return dayCount;
                    }

                }

                this.parseDate = function( value, format ) {

                    if( !value )
                        return false;

                    format = format.split('_');
                    var dateFormat = format[0];
                    var separators = { slash: '/', dash: '-', dot: '.' };
                    var separator = format.length > 1 ? separators[format[1]] : separators.slash;
                    var dateArr = value.split(separator);

                    return new Date( dateArr[2], dateArr[1] - 1, dateArr[0] ); 
                }

                this.populateDayCount = function() {
                    this.countInput.val( this.getDayCount() ).change();
                }

                this.isValidDate = function( date ) {
                    return !isNaN( Date.parse( date ) );
                }

                this.init();

            }

        })(jQuery);

        </script>

        <?php
            self::$script_output = true;
            return $form;
    }

    function add_init_script( $return ) {

        $start_field_format = false;
        $end_field_format = false;

        foreach( $this->form['fields'] as &$field ) {

            if( $field['id'] == $this->start_field_id )
                $start_field_format = $field['dateFormat'] ? $field['dateFormat'] : 'mdy';

            if( $field['id'] == $this->end_field_id )
                $end_field_format = $field['dateFormat'] ? $field['dateFormat'] : 'mdy';

        }

        $script = "new gwdc({
                formId:             {$this->form['id']},
                startFieldId:       {$this->start_field_id},
                startDateFormat:    '$start_field_format',
                endFieldId:         {$this->end_field_id},
                endDateFormat:      '$end_field_format',
                countFieldId:       {$this->count_field_id},
                countAdjust:        {$this->count_adjust}
            });";

        $slug = implode( '_', array( 'gw_display_count', $this->start_field_id, $this->end_field_id, $this->count_field_id ) );
        GFFormDisplay::add_init_script( $this->form['id'], $slug, GFFormDisplay::ON_PAGE_RENDER, $script );

        // remove filter so init script is not output on subsequent forms
        remove_filter( 'gform_init_scripts_footer', array( &$this, 'add_init_script' ) );

        return $return;
    }

    function override_submitted_value( $form ) {

        $start_date = false;
        $end_date = false;

        foreach( $form['fields'] as &$field ) {

            if( $field['id'] == $this->start_field_id )
                $start_date = self::parse_field_date( $field );

            if( $field['id'] == $this->end_field_id )
                $end_date = self::parse_field_date( $field );

        }

        if( $start_date > $end_date ) {

            $day_count = 0;

        } else {

            $diff = $end_date - $start_date;
            $day_count = $diff / ( 60 * 60 * 24 );// secs * mins * hours
            $day_count = round( $day_count ) + $this->count_adjust;

        }

        $_POST["input_{$this->count_field_id}"] = $day_count;

    }

    static function parse_field_date( $field ) {
        $date_value = rgpost("input_{$field['id']}");
        $date_format = empty( $field['dateFormat'] ) ? 'mdy' : esc_attr( $field['dateFormat'] );
        $date_info = GFCommon::parse_date( $date_value, $date_format );
        if( empty( $date_info ) )
            return false;
            return strtotime( "{$date_info['year']}-{$date_info['month']}-{$date_info['day']}" );
    }

}

# Configuration
new GWDayCount( 
    [
        'form_id'        => 1,
        'start_field_id' => 29,
        'end_field_id'   => 145,
        'count_field_id' => 146,
        'include_end_date' => false,
    ]
);

// Change menu itens in Woocommerce My Account
add_filter ( 'woocommerce_account_menu_items', function($menu){
    unset($menu['downloads']);
    unset($menu['edit-address']);

    return $menu;
} );

// Woocommerce add shortdescription in card
add_action( 'woocommerce_after_shop_loop_item', function(){    
    the_excerpt();
}, 7 );  

/////
// Woocommetce redirect auto to checkout
add_filter ('woocommerce_add_to_cart_redirect', 'redirect_to_checkout');
function redirect_to_checkout() {
    return wc_get_checkout_url();
}

/////
// altera o preço do produto de preço aberto tirando a virgula
add_filter('woocommerce_add_to_cart_validation', 'custom_price', 99, 2 );
function custom_price( $true, $product ) {
    if( 'yes' === get_post_meta( $product, '_' . 'alg_wc_product_open_pricing_enabled', true ) ){
        list($final_price) = explode(",", $_REQUEST['alg_open_price']);
        $_REQUEST['alg_open_price'] = $final_price;
    }
    return $true; 
}

/////
// mantem apenas 1 produto no checkout
add_filter( 'woocommerce_add_cart_item_data', 'only_one_item_on_cart' );
function only_one_item_on_cart( $cart_item_data ) {
    WC()->cart->empty_cart();
    return $cart_item_data;
}

/////
// mantem apenas 1 produto no checkout após login
function clear_persistent_cart_after_login( $user_login, $user ) {
    $blog_id = get_current_blog_id();
    // persistent carts created in WC 3.1 and below
    if ( metadata_exists( 'user', $user->ID, '_woocommerce_persistent_cart' ) ) {
        delete_user_meta( $user->ID, '_woocommerce_persistent_cart' );
    }

    // persistent carts created in WC 3.2+
    if ( metadata_exists( 'user', $user->ID, '_woocommerce_persistent_cart_' . $blog_id ) ) {
        delete_user_meta( $user->ID, '_woocommerce_persistent_cart_' . $blog_id );
    }
}
add_action('wp_login', 'clear_persistent_cart_after_login', 10, 2);

/////
// redirects
add_action( 'template_redirect', 'pages_redirects' );
function pages_redirects(){
    if ( is_cart() ) {
        if(WC()->cart->get_cart_contents_count() > 0){
            wp_safe_redirect( wc_get_checkout_url() ); 
        } else {
            wp_safe_redirect( home_url() .'/'.get_field('page_doe', 'option').'/' ); 
        }
        exit();
    }
    if(is_product()){
        wp_safe_redirect( home_url() .'/'.get_field('page_doe', 'option').'/' ); 
        exit();
    }
    if(is_shop()){
        wp_safe_redirect( home_url() .'/'.get_field('page_doe', 'option').'/' );
        exit();
    }
}

/////
// remove msg View Cart no checkout
add_filter( 'wc_add_to_cart_message', 'remove_add_to_cart_message' );
function remove_add_to_cart_message() {
    return;
}

/////
// Criando campos na página de configurações do tema da página DOE
function acf_ios_config_doe_page() {
    if( function_exists('acf_add_local_field_group') ):

        $args = array(
          'post_type' => 'page',
          'posts_per_page' => -1
        );
        $pages = new WP_Query($args);
        $pages_choices = [];


        foreach ($pages->posts as $page) {
            $pages_choices += array($page->post_name => $page->post_title);
        }

        acf_add_local_field_group(array(
            'key'      => 'ios_config_page_fields_doe',
            'name'     => 'ios_config_page_fields_doe',
            'title'    => 'Configurações da página de DOE',
            'fields'   => array (
                array (
                    'key' => 'ios_config_page_group_doe',
                    'name' => 'ios_config_page_group_doe',
                    'title' => 'Configurações da página de DOE',
                    'type' => 'group',
                    'layout' => 'block',
                    'sub_fields' => array (
                        array (
                            'key' => 'page_doe',
                            'label' => 'Página de DOE',
                            'name' => 'page_doe',
                            'instructions' => 'Selecione a página de DOE.',
                            'type' => 'select',
                            'choices' => $pages_choices,
                            'multiple' => 0,
                            'ui' => 1,
                            'required' => true
                        )
                    )
                )
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'ios_config_page',
                    ),
                ),
            ),
        ));

    endif;
}
add_action( 'acf/init', 'acf_ios_config_doe_page' );


/////
// Múltiplos forms do Gravity Forms
function gravity_forms_multiple_forms( $form_string, $form ) {
    if($form['id'] == 11){
        // if form has been submitted, use the submitted ID, otherwise generate a new unique ID
        if ( isset( $_POST['gform_random_id'] ) ) {
            $random_id = absint( $_POST['gform_random_id'] ); // Input var okay.
        } else {
            $random_id = mt_rand();
        }

        // this is where we keep our unique ID
        $hidden_field = "<input type='hidden' name='gform_field_values'";

        // define all occurences of the original form ID that wont hurt the form input
        $strings = array(
            ' gform_wrapper '                                                   => ' gform_wrapper gform_wrapper_original_id_' . $form['id'] . ' ',
            "for='choice_"                                                      => "for='choice_" . $random_id . '_',
            "id='choice_"                                                       => "id='choice_" . $random_id . '_',
            "id='label_"                                                        => "id='label_" . $random_id . '_',
            "'gform_wrapper_" . $form['id'] . "'"                               => "'gform_wrapper_" . $random_id . "'",
            "'gf_" . $form['id'] . "'"                                          => "'gf_" . $random_id . "'",
            "'gform_" . $form['id'] . "'"                                       => "'gform_" . $random_id . "'",
            "'gform_ajax_frame_" . $form['id'] . "'"                            => "'gform_ajax_frame_" . $random_id . "'",
            '#gf_' . $form['id'] . "'"                                          => '#gf_' . $random_id . "'",
            "'gform_fields_" . $form['id'] . "'"                                => "'gform_fields_" . $random_id . "'",
            "id='field_" . $form['id'] . '_'                                    => "id='field_" . $random_id . '_',
            "for='input_" . $form['id'] . '_'                                   => "for='input_" . $random_id . '_',
            "id='input_" . $form['id'] . '_'                                    => "id='input_" . $random_id . '_',
            "'gform_submit_button_" . $form['id'] . "'"                         => "'gform_submit_button_" . $random_id . "'",
            '"gf_submitting_' . $form['id'] . '"'                               => '"gf_submitting_' . $random_id . '"',
            "'gf_submitting_" . $form['id'] . "'"                               => "'gf_submitting_" . $random_id . "'",
            '#gform_ajax_frame_' . $form['id']                                  => '#gform_ajax_frame_' . $random_id,
            '#gform_wrapper_' . $form['id']                                     => '#gform_wrapper_' . $random_id,
            '#gform_' . $form['id']                                             => '#gform_' . $random_id,
            "trigger('gform_post_render', [" . $form['id']                      => "trigger('gform_post_render', [" . $random_id,
            'gformInitSpinner( ' . $form['id'] . ','                            => 'gformInitSpinner( ' . $random_id . ',',
            "trigger('gform_page_loaded', [" . $form['id']                      => "trigger('gform_page_loaded', [" . $random_id,
            "'gform_confirmation_loaded', [" . $form['id'] . ']'                => "'gform_confirmation_loaded', [" . $random_id . ']',
            'gf_apply_rules(' . $form['id'] . ','                               => 'gf_apply_rules(' . $random_id . ',',
            'gform_confirmation_wrapper_' . $form['id']                         => 'gform_confirmation_wrapper_' . $random_id,
            'gforms_confirmation_message_' . $form['id']                        => 'gforms_confirmation_message_' . $random_id,
            'gform_confirmation_message_' . $form['id']                         => 'gform_confirmation_message_' . $random_id,
            'if(formId == ' . $form['id'] . ')'                                 => 'if(formId == ' . $random_id . ')',
            "window['gf_form_conditional_logic'][" . $form['id'] . ']'          => "window['gf_form_conditional_logic'][" . $random_id . ']',
            "trigger('gform_post_conditional_logic', [" . $form['id'] . ','     => "trigger('gform_post_conditional_logic', [" . $random_id . ',',
            'gformShowPasswordStrength("input_' . $form['id'] . '_'             => 'gformShowPasswordStrength("input_' . $random_id . '_',
            "gformInitChosenFields('#input_" . $form['id'] . '_'                => "gformInitChosenFields('#input_" . $random_id . '_',
            "jQuery('#input_" . $form['id'] . '_'                               => "jQuery('#input_" . $random_id . '_',
            'gforms_calendar_icon_input_' . $form['id'] . '_'                   => 'gforms_calendar_icon_input_' . $random_id . '_',
            "id='ginput_base_price_" . $form['id'] . '_'                        => "id='ginput_base_price_" . $random_id . '_',
            "id='ginput_quantity_" . $form['id'] . '_'                          => "id='ginput_quantity_" . $random_id . '_',
            'gfield_price_' . $form['id'] . '_'                                 => 'gfield_price_' . $random_id . '_',
            'gfield_quantity_' . $form['id'] . '_'                              => 'gfield_quantity_' . $random_id . '_',
            'gfield_product_' . $form['id'] . '_'                               => 'gfield_product_' . $random_id . '_',
            'ginput_total_' . $form['id']                                       => 'ginput_total_' . $random_id,
            'GFCalc(' . $form['id'] . ','                                       => 'GFCalc(' . $random_id . ',',
            'gf_global["number_formats"][' . $form['id'] . ']'                  => 'gf_global["number_formats"][' . $random_id . ']',
            'gform_next_button_' . $form['id'] . '_'                            => 'gform_next_button_' . $random_id . '_',
            $hidden_field                                                       => "<input type='hidden' name='gform_random_id' value='" . $random_id . "' />" . $hidden_field,
        );

        // allow addons & plugins to add additional find & replace strings
        $strings = apply_filters( 'gform_multiple_instances_strings', $strings, $form['id'], $random_id );

        // replace all occurences with the new unique ID
        foreach ( $strings as $find => $replace ) {
            $form_string = str_replace( $find, $replace, $form_string );
        }
    }

    return $form_string;
}
add_filter( 'gform_get_form_filter', 'gravity_forms_multiple_forms', 10, 2 );
add_filter( 'gform_confirmation', 'gravity_forms_multiple_forms', 10, 2 );

/////
// Criando campos de Totais Recorrentes no checkout
add_action( 'woocommerce_cart_totals_after_order_total', 'display_recurring_totals', 10, 1 );
add_action( 'woocommerce_review_order_after_order_total', 'display_recurring_totals', 10, 1 );
if ( ! function_exists( 'display_recurring_totals' ) ) {
    function display_recurring_totals() {
        if (is_plugin_active('woocommerce-subscriptions/woocommerce-subscriptions.php')){
            if ( WC_Subscriptions_Cart::cart_contains_subscription() ) {

                // We only want shipping for recurring amounts, and they need to be calculated again here
                $carts_with_multiple_payments = 0;

                foreach ( WC()->cart->recurring_carts as $recurring_cart ) {
                    // Cart contains more than one payment
                    if ( 0 != $recurring_cart->next_payment_date ) {
                        $carts_with_multiple_payments++;
                    }
                }

                if ( apply_filters( 'woocommerce_subscriptions_display_recurring_totals', $carts_with_multiple_payments >= 1 ) ) {
                    wc_get_template(
                        'checkout/recurring-totals.php',
                        array(
                            'shipping_methods'             => array(),
                            'recurring_carts'              => WC()->cart->recurring_carts,
                            'carts_with_multiple_payments' => $carts_with_multiple_payments,
                        ),
                        '',
                        plugin_dir_path( WC_Subscriptions::$plugin_file ) . 'templates/'
                    );
                }

            }
        }
    }
}

/////
// Validando no checkout se o CPF do usuário no momento da doação está cadastrado para outro usuario
add_action('woocommerce_checkout_process', 'validate_billing_cpf');
function validate_billing_cpf() {
    if ( !is_user_logged_in() ) {
        $billing_cpf = $_POST['billing_cpf'];
        $billing_cnpj = $_POST['billing_cnpj'];
        $billing_persontype = $_POST['billing_persontype'];
        $billing_email = $_POST['billing_email'];

        if($billing_persontype == 1){
            $param = "CPF";
            $user = new WP_User_Query(
                array(
                    'role' => 'customer',
                    'fields'     => 'all',
                    'meta_query' => array(
                        array(
                            'key'       => 'billing_cpf',
                            'value'     => $billing_cpf,
                            'compare'   => 'LIKE',
                        )
                    ) 
                )
            );
        } else if ($billing_persontype == 2){
            $param = "CNPJ";
            $user = new WP_User_Query(
                array(
                    'role' => 'customer',
                    'fields'     => 'all',
                    'meta_query' => array(
                        array(
                            'key'       => 'billing_cnpj',
                            'value'     => $billing_cnpj,
                            'compare'   => 'LIKE',
                        )
                    ) 
                )
            );
        }

        if($user->get_total() > 0){
            wc_add_notice( __( 'Esse '.$param.' já existe no cadastro de outro usuário.' ), 'error' );
        }
    }

}

/////
// Alterando nome do botão de finalizar compra para DOAR
function custom_checkout_button_text() {
    echo '<script>
    jQuery("body").on("init_checkout payment_method_selected update_checkout updated_checkout", function(){
        jQuery( "#place_order" ).text( "Doar" );
    });
    </script>';
}
add_action('wp_footer', 'custom_checkout_button_text');

/////
// Removendo notices e erros especificos do WooCommerce
add_filter( 'woocommerce_add_to_cart_validation', 'validate_open_price_on_add_to_cart', 100, 2 );
function validate_open_price_on_add_to_cart( $passed, $product_id ) {
    $notices = WC()->session->get('wc_notices', array());

    foreach( $notices['error'] as $key => &$notice){
        if( strpos( $notice['notice'], 'Valor muito baixo' ) !== false || strpos( $notice['notice'], 'Valor muito alto' ) !== false || strpos( $notice['notice'], 'Por favor adicionar um valor!' ) !== false || strpos( $notice['notice'], 'A subscription has been removed from your cart. Due to payment gateway restrictions, different subscription products can not be purchased at the same time.' ) !== false ){
            $added_to_cart_key = $key;
            break;
        }
    }
    foreach( $notices['notice'] as $key => &$notice){
        if( strpos( $notice['notice'], 'A subscription has been removed from your cart. Due to payment gateway restrictions, different subscription products can not be purchased at the same time.' ) !== false || strpos( $notice['notice'], 'Your cart has been emptied of subscription products. Only one subscription product can be purchased at a time.' ) !== false  || strpos( $notice['notice'], 'Não é possível finalizar o pedido enquanto o seu carrinho estiver vazio.' ) !== false  ){
            $added_to_cart_key = $key;
            break;
        }
    }

    unset( $notices['error'][$added_to_cart_key] );
    unset( $notices['notice'][$added_to_cart_key] );

    WC()->session->set('wc_notices', $notices);

    return $passed;
}

/////
// Locate Template de emails
add_filter( 'woocommerce_locate_template', 'woocommerce_locate_template_email', 10, 3 ); 
function woocommerce_locate_template_email( $template, $template_name, $template_path ) {
    $plugin_path  = plugin_dir_path( __FILE__ ); 

    if(strpos($template_name, 'emails/') !== false){
        $template = $plugin_path . 'templates/' . $template_name;
    }

    return $template; 
}

/////
// Corrigindo fields do checkout de acordo com regra de negócio
add_filter('woocommerce_checkout_fields','custom_override_checkout_fields');
function custom_override_checkout_fields($fields){
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_cellphone']);
    $fields['billing']['billing_address_2']['label_class'] = array();
    $fields['billing']['billing_postcode']['maxlenght'] = 9;
    $fields['billing']['billing_phone']['label'] = 'Celular';
    $fields['billing']['billing_phone']['type'] = 'tel';
    $fields['billing']['billing_email']['class'] = array('form-row-last');
    $fields['order']['order_comments']['label'] = 'Deixe uma mensagem para os alunos';
    $fields['order']['order_comments']['placeholder'] = '';

    if ( get_option( 'woocommerce_registration_generate_password' ) != 'no' )
        return $fields;

    $fields['account']['account_password']['class'] = array('form-row-first');
    $fields['account']['account_password-2'] = array(
        'type' => 'password',
        'label' => __( 'Confirme sua senha', 'woocommerce' ),
        'required'          => true,
        'placeholder' => '',
        'class' => array('form-row-last'),
        'label_class' => array('hidden')
    );

    return $fields;
}

/////
// Password Checkout Validation
add_action( 'woocommerce_checkout_process', 'confirm_password_checkout_validation' );
function confirm_password_checkout_validation() {
    if ( ! is_user_logged_in() && ( WC()->checkout->must_create_account || ! empty( $_POST['createaccount'] ) ) ) {
        if ( strcmp( $_POST['account_password'], $_POST['account_password-2'] ) !== 0 )
            wc_add_notice( __( "Senhas diferentes.", "woocommerce" ), 'error' );
    }
}

/////
// Min Password Strenght
add_filter( 'woocommerce_min_password_strength', 'woocommerce_password_filter', 10 );
function woocommerce_password_filter() {
    return 0;
}

/////
// Removendo Password Strength
add_action( 'wp_print_scripts', 'remove_password_strength', 100 );
function remove_password_strength() {
    if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
        wp_dequeue_script( 'wc-password-strength-meter' );
    }
} 

/////
// Corrigindo validações JS no checkout
add_action('wp_footer', function (){
    echo "<script type=\"text/javascript\">jQuery(document).ready(function($){ $(\"input[name='billing_phone']\").mask('(00) 0 0000-0000'); });</script>";
    echo "<script type=\"text/javascript\">jQuery(document).ready(function($){ $(\"input[name='billing_postcode']\").mask('00000-000'); });</script>";
    echo "<script type=\"text/javascript\">jQuery(document.body).on('blur','#billing_postcode',function(){if(jQuery('#'+'billing'+'_postcode').length){var cep=jQuery('#'+'billing'+'_postcode').val().replace('.','').replace('-',''),country='BR',address_1=jQuery('#'+'billing'+'_address_1').val(),override=!0;if(cep!==''&&8===cep.length&&'BR'===country&&override){jQuery('form.checkout, form#order_review').addClass('processing').block({message:null,overlayCSS:{background:'#fff',opacity:0.6}});jQuery.ajax({type:'GET',url:WCCorreiosAutofillAddressParams.url+'&postcode='+cep,dataType:'json',contentType:'application/json',success:function(address){if(address.success){jQuery('#'+'billing'+'_address_1').val(address.data.address).change();if(jQuery('#'+'billing'+'_neighborhood').length){jQuery('#'+'billing'+'_neighborhood').val(address.data.neighborhood).change()}else{jQuery('#'+'billing'+'_address_2').val(address.data.neighborhood).change()}
jQuery('#'+'billing'+'_city').val(address.data.city).change();jQuery('#'+'billing'+'_state option:selected').attr('selected',!1).change();jQuery('#'+'billing'+'_state option[value=\"'+address.data.state+'\"]').attr('selected','selected').change();jQuery('#'+'billing'+'_state').trigger('liszt:updated').trigger('chosen:updated')}
jQuery('form.checkout, form#order_review').removeClass('processing').unblock()}})}}})</script>";
}, 111);

/////
// Redireciona usuário role "professor" pra dashboard quando logado
function login_redirect_based_on_roles($user_login, $user) {
    if( in_array( 'professor', $user->roles ) && $GLOBALS['pagenow'] === 'wp-login.php' ){
        exit( wp_safe_redirect(home_url().'/dashboard') );
    }
}
add_action( 'wp_login', 'login_redirect_based_on_roles', 10, 2);

/////
// Verifica se o usuario não é professor nem admin e manda pra home
function is_correct_user()
{
    if(basename( get_page_template() ) == "template-dashboard.php" && !current_user_can("administrator") && !current_user_can("professor")) {
        wp_redirect( home_url().'/wp-login.php' );
        exit;
    }     
}
add_action( 'wp', 'is_correct_user' );

add_filter( 'gform_field_validation_13_121', function ( $result, $value, $form, $field ) {
    $master = rgpost( 'input_41' );
    if ($master == 2) {
        $result['is_valid'] = true;
    }
  
    return $result;
}, 10, 4 );

//function para bloquear os caracteres

add_filter( 'gform_field_validation_13_23', function( $result, $value, $form, $field ) {
	$pattern = "/^[a-zA-Z ]*$/"; 
	if ( strpos( $field->cssClass, 'letras_espacos' ) !== false && ! preg_match( $pattern, $value ) ) {
		$result['is_valid'] = false;
		$result['message'] = 'Não é permitido usar caracteres especiais';
	}
	return $result;
}, 10, 4 );

//function para juntar os números

add_filter( 'gform_save_field_value_13_47', 'aceita_apenas_numeros', 10, 4 );
function aceita_apenas_numeros( $value, $lead, $field, $form ) {
	GFCommon::log_debug( __METHOD__ . '(): Original value => ' . $value );
	$value = preg_replace("/[^0-9]/", "", $value );
	GFCommon::log_debug( __METHOD__ . '(): Modified value => ' . $value );
	return $value;
}

//function para tirar caracteres especiais

add_filter( 'gform_save_field_value_13_24', 'convertacentos', 10, 4 );
function convertacentos( $value, $lead, $field, $form ) {
	GFCommon::log_debug( __METHOD__ . '(): Original value => ' . $value );
	$value = preg_replace("/[^a-zA-Z0-9]", "", $value );
	GFCommon::log_debug( __METHOD__ . '(): Modified value => ' . $value );
	return $value;
}


