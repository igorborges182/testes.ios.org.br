<?php

namespace app\Controllers;

use \Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
* Elementor IOS_Product Widget.
*
* @since 1.0.0
*/
class Elementor_IOS_Product_Widget extends Widget_Base {

	/**
	* Get widget name.
	*
	* Retrieve IOS_Product widget name.
	*
	* @since 1.0.0
	* @access public
	*
	* @return string Widget name.
	*/
	public function get_name() {
		return 'ios_product';
	}

	/**
	* Get widget title.
	*
	* Retrieve IOS_Product widget title.
	*
	* @since 1.0.0
	* @access public
	*
	* @return string Widget title.
	*/
	public function get_title() {
		return __( 'IOS Product', 'elementor' );
	}

	/**
	* Get widget icon.
	*
	* Retrieve IOS_Product widget icon.
	*
	* @since 1.0.0
	* @access public
	*
	* @return string Widget icon.
	*/
	public function get_icon() {
		return 'fa fa-code';
	}

	/**
	* Get widget categories.
	*
	* Retrieve the list of categories the IOS_Product widget belongs to.
	*
	* @since 1.0.0
	* @access public
	*
	* @return array Widget categories.
	*/
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	* Get sizes.
	*
	* Retrieve an array of sizes for the widget.
	*
	* @since 1.0.0
	* @access public
	* @static
	*
	* @return array An array containing sizes.
	*/
	public static function get_sizes() {
		return [
			'xs' => __( 'Extra Small', 'elementor' ),
			'sm' => __( 'Small', 'elementor' ),
			'md' => __( 'Medium', 'elementor' ),
			'lg' => __( 'Large', 'elementor' ),
			'xl' => __( 'Extra Large', 'elementor' ),
		];
	}

	/**
	* Register IOS_Product widget controls.
	*
	* Adds different input fields to allow the user to change and customize the widget settings.
	*
	* @since 1.0.0
	* @access protected
	*/
	protected function _register_controls() {

		$this->start_controls_section(
			'section_config',
			[
				'label' => __( 'Configurações', 'elementor' ),
			]
		);

		$products = [];
		$products_type_input = [];
		$product_variations = [];
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1
		);
		$products_get = new \WP_Query( $args );
		foreach ($products_get->posts as $product) {
			if( 'yes' === get_post_meta( $product->ID, '_' . 'alg_wc_product_open_pricing_enabled', true ) ){
				$products_type_input += array((string)$product->ID);
			}
			$products += array($product->ID => $product->post_title);

			$product_woo = wc_get_product($product->ID);
			if ($product_woo->is_type('variable')){
				$product_variations[$product->ID] = $product_woo->get_children();
			}
		}

		$this->add_control(
			'produto',
			[
				'label' => __( 'Produto', 'elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'options' => $products,
				'placeholder' => __( 'Selecione o produto.', 'elementor' )
			]
		);

		foreach ($product_variations as $product_id => $variations) {
			$variation_options = [];
			foreach ($variations as $variation) {
				$products_get = get_post($variation);
				$variation_options += array($products_get->ID => $products_get->post_excerpt);
			}
			if(!empty($variation_options)){
				$this->add_control(
					'variacao_'.$product_id,
					[
						'label' => __( 'Variação', 'elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => $variation_options,
						'placeholder' => __( 'Selecione a variação do produto.', 'elementor' ),
						'default' => key($variation_options),
						'condition' => [
							'produto' => (string)$product_id,
						],
					]
				);
			}
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'Button', 'elementor' ),
			]
		);

		$this->add_control(
			'button_type',
			[
				'label' => __( 'Type', 'elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'Default', 'elementor' ),
					'info' => __( 'Info', 'elementor' ),
					'success' => __( 'Success', 'elementor' ),
					'warning' => __( 'Warning', 'elementor' ),
					'danger' => __( 'Danger', 'elementor' ),
				],
				'prefix_class' => 'elementor-button-',
			]
		);

		$this->add_control(
			'text',
			[
				'label' => __( 'Text', 'elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Click here', 'elementor' ),
				'placeholder' => __( 'Click here', 'elementor' ),
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
		);

		$this->add_control(
			'size',
			[
				'label' => __( 'Size', 'elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_sizes(),
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'elementor' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'button_css_id',
			[
				'label' => __( 'Button ID', 'elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'title' => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor' ),
				'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'elementor' ),
				'separator' => 'before',

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_input',
			[
				'label' => __( 'Input', 'elementor' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'produto',
							'operator' => 'in',
							'value' => $products_type_input
						]
					]
				],
			]
		);

		$this->add_control(
			'placeholder',
			[
				'label' => __( 'Placeholder', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'R$',
			]
		);

		$this->add_control(
			'input_size',
			[
				'label' => __( 'Size', 'elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_sizes(),
				'style_transfer' => true,
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Column Width', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'elementor-pro' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'25' => '25%',
					'20' => '20%',
				],
				'default' => '100',
			]
		);

		$this->add_control(
			'css_classes',
			[
				'label' => __( 'CSS Classes', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => '',
				'title' => __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'elementor-pro' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Button', 'elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'elementor' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form',
			[
				'label' => __( 'Form', 'elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'conditions' => [
					'terms' => [
						[
							'name' => 'produto',
							'operator' => 'in',
							'value' => $products_type_input
						]
					]
				],
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label' => __( 'Space', 'elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 8,
				],
				'size_units' => [ 'px', 'vh', 'em' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 600,
					],
					'em' => [
						'min' => 0.1,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-spacer-inner' => 'height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_field_style',
			[
				'label' => __( 'Field', 'elementor-pro' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field, {{WRAPPER}} .elementor-field-subgroup label',
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label' => __( 'Border Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper::before' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_width',
			[
				'label' => __( 'Border Width', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'field_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	* Render IOS_Product widget output on the frontend.
	*
	* Written in PHP and used to generate the final HTML.
	*
	* @since 1.0.0
	* @access protected
	*/
	protected function render() {

		$settings = $this->get_settings_for_display();

		if(isset($settings['produto']) && strlen($settings['produto']) > 0){
			if( 'yes' === get_post_meta( $settings['produto'], '_' . 'alg_wc_product_open_pricing_enabled', true ) ){
				$product = wc_get_product($settings['produto']);

				$min_price = get_post_meta( $settings['produto'], '_' . 'alg_wc_product_open_pricing_min_price', true );
				$max_price = get_post_meta( $settings['produto'], '_' . 'alg_wc_product_open_pricing_max_price', true );

				if(strlen($min_price) > 0 && strlen($max_price) == 0){
					echo '<p style="font-size: 10px; text-align: center;">Valor mínimo R$ <span class="alg_open_price-min-value">'.$min_price.'</span>,00</p>';
				} else if(strlen($min_price) > 0  && strlen($max_price) > 0){
					echo '<p style="font-size: 10px; text-align: center;">Valor mínimo R$ <span class="alg_open_price-min-value">'.$min_price.'</span>,00 e máximo R$ <span class="alg_open_price-max-value">'.$max_price.'</span>,00</p>';
				}

				echo '<form class="elementor-form alg_open_price_form" action="/produto/'.get_post_field( 'post_name', $product->get_ID() ).'/" method="post" enctype="multipart/form-data">';
				echo '<input type="hidden" name="post_id" value="'.$product->get_ID().'">';
				echo '<input type="hidden" name="queried_id" value="'.$product->get_ID().'">';
				echo '<div class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-name elementor-col-'.$settings['width'].' elementor-field-required">';
				echo '<input name="alg_open_price" id="alg_open_price" class="elementor-field elementor-size-'.$settings['input_size'].' elementor-field-textual" placeholder="'.$settings['placeholder'].'" required="required" aria-required="true" type="text" data-product_id="'.$product->get_ID().'">';
				echo '<input type="hidden" id="quantity" class="qty" name="quantity" value="1">';
				echo '</div>';
				echo '<div class="elementor-spacer">';
				echo '<div class="elementor-spacer-inner"></div>';
				echo '</div>';
				echo '<div class="elementor-button-wrapper">';
				echo '<button id="'.$settings['button_css_id'].'" class="elementor-button elementor-size-'.$settings['size'].' elementor-animation-'.$settings['hover_animation'].'" role="button" aria-invalid="false" type="submit" name="add-to-cart" value="'.$product->get_ID().'" style="font-weight: 300; text-transform: initial; text-decoration: none;"> ';
				echo '<span class="elementor-button-content-wrapper">';
				echo '<span class="elementor-button-text">'.$settings['text'].'</span>';
				echo '</span>';
				echo '</button>';
				echo '</div>';
				echo '</form>';
			} else {
				$product = wc_get_product($settings['produto']);

				if(isset($settings['variacao_'.$product->get_ID()]) && strlen($settings['variacao_'.$product->get_ID()]) > 0){
					if ($product->is_type('variable')){
						$variacao = wc_get_product($settings['variacao_'.$product->get_ID()]);
						$url = '/checkout/?add-to-cart='.$product->get_ID().'&variation_id='.$variacao->get_ID();
					} else {
						$url = '/checkout/?add-to-cart='.$product->get_ID();
					}
				} else {
					$url = '/checkout/?add-to-cart='.$product->get_ID();
				}

				echo '<div class="elementor-button-wrapper">';
				echo '<a href="'.$url.'" id="'.$settings['button_css_id'].'" class="elementor-button elementor-size-'.$settings['size'].' elementor-animation-'.$settings['hover_animation'].'" role="button" style="font-weight: 300; text-transform: initial; text-decoration: none;">';
				echo '<span class="elementor-button-content-wrapper">';
				echo '<span class="elementor-button-text">'.$settings['text'].'</span>';
				echo '</span>';
				echo '</a>';
				echo '</div>';
			}
		}

	}

}