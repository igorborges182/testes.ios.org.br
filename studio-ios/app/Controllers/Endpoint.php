<?php

namespace app\Controllers;

class Endpoint extends EndpointBase
{
    public $method;
    public $response;

    public function __construct($method)
    {
        $this->method = $method;
        $this->response = array(
            'Status' => false,
            'StatusCode' => 0,
            'StatusMessage' => 'Default'
        );
    }

    private $status_codes = array(
        'success' => true,
        'failure' => 0,
        'missing_param' => 150,
    );

    public function init(\WP_REST_Request $request)
    {
        try {
            if (!method_exists($this, $this->method)) {
                throw new Exception('No method exists', 404);
            }
            $data = $this->{$this->method}($request);
            $this->response['Status'] = $this->status_codes['success'];
            $this->response['StatusCode'] = 200;
            $this->response['StatusMessage'] = 'success';
            $this->response['Data'] = $data;
        } catch (Exception $e) {
            $this->response['Status'] = false;
            $this->response['StatusCode'] = $e->getCode();
            $this->response['StatusMessage'] = $e->getMessage();
        }

        return $this->response;
    }

    public function aprovados($request)
    {
        try {
            if($request->get_header('Token') != "da73346cb8eabfa55deef47df9e9c024"){
                wp_send_json_error(htmlentities('Token inválido.'));
            }

            $args = [
                'post_type' => 'page',
                'fields' => 'ids',
                'nopaging' => true,
                'meta_key' => '_wp_page_template',
                'meta_value' => 'template-dashboard.php'
            ];
            $pages = get_posts( $args );
            $form_id = get_field('formulario', $pages[0]);

            $page_size = (isset($_GET['page_size'])) ? $_GET['page_size'] : 10;
            $page = (isset($_GET['page']) && $_GET['page'] >= 1) ? $_GET['page'] : 1;

            $candidates = [];
            $candidatos_aprovados = get_entrys_approved_and_unlocked_candidates($page_size, ($page - 1) * $page_size);

            $i = 0;
            foreach ($candidatos_aprovados['results'] as $key => $candidato) {
                $candidate = \GFAPI::get_entry( $candidato->entry_id );
                foreach ($candidate as $key_field => $value) {

                    if(is_numeric($key_field)){
                        $field = \GFFormsModel::get_field( $form_id, $key_field );
                        if($field['type'] == 'select' || $field['type'] == 'checkbox' || $field['type'] == 'radio'){
                            if($this->stripAccents(str_replace('/', '', str_replace(' ', '_', $field['label']))) != "QUAL_ANO" && $this->stripAccents(str_replace('/', '', str_replace(' ', '_', $field['label']))) != "SERIE" && $this->stripAccents(str_replace('/', '', str_replace(' ', '_', $field['adminLabel']))) != "QUAL_ANO" && $this->stripAccents(str_replace('/', '', str_replace(' ', '_', $field['adminLabel']))) != "SERIE"){
                                if(!empty($field['adminLabel'])){
                                    $candidates[$i][$this->stripAccents(str_replace('/', '', str_replace(' ', '_', $field['adminLabel'])))] = $this->stripAccents($this->gfapi_array_field($key_field, $candidate[$key_field], 'choices', 'value', 'value').' - '.$this->gfapi_array_field($key_field, $candidate[$key_field], 'choices', 'value', 'text'));
                                } else {
                                    $candidates[$i][$this->stripAccents(str_replace('/', '', str_replace(' ', '_', $field['label'])))] = $this->stripAccents($this->gfapi_array_field($key_field, $candidate[$key_field], 'choices', 'value', 'value').' - '.$this->gfapi_array_field($key_field, $candidate[$key_field], 'choices', 'value', 'text'));
                                }
                            } else {
                                if(!empty($field['adminLabel'])){
                                    $candidates[$i][$this->stripAccents(str_replace('/', '', str_replace(' ', '_', $field['adminLabel'])))] = $this->stripAccents($this->gfapi_array_field($key_field, $candidate[$key_field], 'choices', 'value', 'text'));
                                } else {
                                    $candidates[$i][$this->stripAccents(str_replace('/', '', str_replace(' ', '_', $field['label'])))] = $this->stripAccents($this->gfapi_array_field($key_field, $candidate[$key_field], 'choices', 'value', 'text'));
                                }
                            }
                        } else if($field['type'] == 'text' || $field['type'] == 'number' || $field['type'] == 'fileupload' || $field['type'] == 'section'){
                            if(!empty($field['adminLabel'])){
                                $candidates[$i][$this->stripAccents(str_replace('/', '', str_replace(' ', '_', $field['adminLabel'])))] = $this->stripAccents($candidate[$key_field]);
                            } else {
                                $candidates[$i][$this->stripAccents(str_replace('/', '', str_replace(' ', '_', $field['label'])))] = $this->stripAccents($candidate[$key_field]);
                            }
                        } else {
                            if(!empty($field['adminLabel'])){
                                $candidates[$i][$this->stripAccents(str_replace('/', '', str_replace(' ', '_', $field['adminLabel'])))] = $this->stripAccents($candidate[$key_field]);
                            } else {
                                if($field['label'] != ""){
                                    $candidates[$i][$this->stripAccents(str_replace('/', '', str_replace(' ', '_', $field['label'])))] = $this->stripAccents($candidate[$key_field]);
                                }
                            }
                        }
                    } else {
                        $candidates[$i][$this->stripAccents(str_replace('/', '', str_replace(' ', '_', $key_field)))] = $this->stripAccents($value);
                    }


                }
                $i++;
            }

            $total_pages = ceil( (int)$candidatos_aprovados['total'] / $page_size );

            $final_data['candidates'] = $candidates;
            $final_data['total_candidates'] = (int)$candidatos_aprovados['total'];
            $final_data['page'] = (int)$page;
            $final_data['page_size'] = (int)$page_size;
            $final_data['total_pages'] = (int)$total_pages;
                        
            wp_send_json_success($final_data, null, JSON_UNESCAPED_SLASHES);

        } catch (\Exception $e) {
            wp_send_json_error('Exceção capturada: ',  $e->getMessage(), "\n");
        }
    }

    public function importados($request)
    {
        try {
            if($request->get_header('Token') != "da73346cb8eabfa55deef47df9e9c024"){
                wp_send_json_error('Token inválido.');
            }

            $candidates = $_POST['candidatos'];

            foreach ($candidates as $key => $candidate) {
                update_entry_approved(array('imported' => '1'), $candidate);
            }
            
            wp_send_json_success($candidates, null, JSON_UNESCAPED_SLASHES);

        } catch (\Exception $e) {
            wp_send_json_error('Exceção capturada: ',  $e->getMessage(), "\n");
        }
    }

    private function gfapi_array_field($field_id, $value_search, $key_search, $field_search, $return_key)
    {
        $args = [
            'post_type' => 'page',
            'fields' => 'ids',
            'nopaging' => true,
            'meta_key' => '_wp_page_template',
            'meta_value' => 'template-dashboard.php'
        ];
        $pages = get_posts( $args );
        $form_id = get_field('formulario', $pages[0]);

        $fields = \GFAPI::get_field($form_id, $field_id);
        $key = array_search($value_search, array_column($fields[$key_search], $field_search));
        return $fields[$key_search][$key][$return_key];
    }

    public function stripAccents($str) {
        return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    }

}
