<?php

namespace app\Controllers;

class EndpointBase extends \WP_REST_Controller {
    public function register_routes() {
        register_rest_route('api', '/aprovados', array(
                array(
                    'methods'  => 'GET',
                    'callback' => array(new Endpoint('aprovados'), 'init'),
                )
            )
        );
        register_rest_route('api', '/aprovados/importados', array(
                array(
                    'methods'  => 'POST',
                    'callback' => array(new Endpoint('importados'), 'init'),
                )
            )
        );
    }
    // Register our REST Server
    public function hook_rest_server() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }
    public function my_customize_rest_cors() {
        remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
        remove_filter('rest_post_dispatch', 'rest_send_allow_header');
    }
}

$EndpointBase = new EndpointBase();
$EndpointBase->hook_rest_server();
