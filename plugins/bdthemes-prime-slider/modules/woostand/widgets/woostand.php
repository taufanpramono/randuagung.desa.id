<?php

namespace PrimeSlider\Modules\Woostand\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use PrimeSlider\Utils;

use PrimeSlider\Traits\Global_Widget_Controls;
use PrimeSlider\Traits\QueryControls\GroupQuery\Group_Control_Query;
use WP_Query;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Woostand extends Widget_Base {
	use Group_Control_Query;
	use Global_Widget_Controls;
	public function get_name() {
		return 'prime-slider-woostand';
	}

	public function get_title() {
		return BDTPS . esc_html__('Woostand', 'bdthemes-prime-slider');
	}

	public function get_icon() {
		return 'bdt-widget-icon ps-wi-woostand';
	}

	public function get_categories() {
		return ['prime-slider'];
	}

	public function get_keywords() {
		return ['prime slider', 'slider', 'woocommerce', 'prime', 'wc slider', 'woostand'];
	}

	public function get_style_depends() {
		return ['ps-woostand'];
	}

	public function get_custom_help_url() {
		return 'https://youtu.be/6Wkk2EMN2ps';
	}

	protected function _register_controls() {
		$this->register_query_section_controls();
	}

	private function register_query_section_controls() {

		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__('Layout', 'bdthemes-prime-slider'),
			]
		);

		$this->add_control(
			'slider_size_ratio',
			[
				'label'       => esc_html__('Size Ratio', 'bdthemes-prime-slider'),
				'type'        => Controls_Manager::IMAGE_DIMENSIONS,
				'description' => 'Slider ratio to width and height, such as 16:9',
				'separator'	  => 'before',
			]
		);

		$this->add_control(
			'slider_min_height',
			[
				'label' => esc_html__('Minimum Height', 'bdthemes-prime-slider'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1024,
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'         => 'thumbnail_size',
				'label'        => esc_html__('Image Size', 'bdthemes-prime-slider') . BDTPS_NC,
				'exclude'      => ['custom'],
				'default'      => 'full',
				'prefix_class' => 'bdt-prime-slider-thumbnail-size-',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'background_image_toggle',
			[
				'label' => __('Background Settings', 'bdthemes-element-pack') . BDTPS_NC,
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __('None', 'bdthemes-element-pack'),
				'label_on' => __('Custom', 'bdthemes-element-pack'),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'background_image_position',
			[
				'label'   => _x('Position', 'bdthemes-prime-slider'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''              => _x('Default', 'bdthemes-prime-slider'),
					'center center' => _x('Center Center', 'bdthemes-prime-slider'),
					'center left'   => _x('Center Left', 'bdthemes-prime-slider'),
					'center right'  => _x('Center Right', 'bdthemes-prime-slider'),
					'top center'    => _x('Top Center', 'bdthemes-prime-slider'),
					'top left'      => _x('Top Left', 'bdthemes-prime-slider'),
					'top right'     => _x('Top Right', 'bdthemes-prime-slider'),
					'bottom center' => _x('Bottom Center', 'bdthemes-prime-slider'),
					'bottom left'   => _x('Bottom Left', 'bdthemes-prime-slider'),
					'bottom right'  => _x('Bottom Right', 'bdthemes-prime-slider'),
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-slideshow-item .bdt-ps-slide-img' => 'background-position: {{VALUE}};',
				],
				'condition' => [
					'background_image_toggle' => 'yes'
				],
				'render_type' => 'ui',
			]
		);

		$this->add_responsive_control(
			'background_image_attachment',
			[
				'label'   => _x('Attachment', 'bdthemes-prime-slider'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''       => _x('Default', 'bdthemes-prime-slider'),
					'scroll' => _x('Scroll', 'bdthemes-prime-slider'),
					'fixed'  => _x('Fixed', 'bdthemes-prime-slider'),
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-slideshow-item .bdt-ps-slide-img' => 'background-attachment: {{VALUE}};',
				],
				'condition' => [
					'background_image_toggle' => 'yes'
				],
				'render_type' => 'ui',
			]
		);

		$this->add_responsive_control(
			'background_image_repeat',
			[
				'label'      => _x('Repeat', 'bdthemes-prime-slider'),
				'type'       => Controls_Manager::SELECT,
				'default'    => '',
				'options'    => [
					''          => _x('Default', 'bdthemes-prime-slider'),
					'no-repeat' => _x('No-repeat', 'bdthemes-prime-slider'),
					'repeat'    => _x('Repeat', 'bdthemes-prime-slider'),
					'repeat-x'  => _x('Repeat-x', 'bdthemes-prime-slider'),
					'repeat-y'  => _x('Repeat-y', 'bdthemes-prime-slider'),
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-slideshow-item .bdt-ps-slide-img' => 'background-repeat: {{VALUE}};',
				],
				'condition' => [
					'background_image_toggle' => 'yes'
				],
				'render_type' => 'ui',
			]
		);

		$this->add_responsive_control(
			'background_image_size',
			[
				'label'      => _x('Size', 'bdthemes-prime-slider'),
				'type'       => Controls_Manager::SELECT,
				'default'    => '',
				'options'    => [
					''        => _x('Default', 'bdthemes-prime-slider'),
					'auto'    => _x('Auto', 'bdthemes-prime-slider'),
					'cover'   => _x('Cover', 'bdthemes-prime-slider'),
					'contain' => _x('Contain', 'bdthemes-prime-slider'),
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-slideshow-item .bdt-ps-slide-img' => 'background-size: {{VALUE}};',
				],
				'condition' => [
					'background_image_toggle' => 'yes'
				],
				'render_type' => 'ui',
			]
		);

		$this->end_popover();

		$this->add_control(
			'show_title',
			[
				'label'   => esc_html__('Show Title', 'bdthemes-prime-slider'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator'	=> 'before',
			]
		);

		$this->add_control(
			'show_category',
			[
				'label'   => esc_html__('Show Category', 'bdthemes-prime-slider'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_price',
			[
				'label'   => __('Price', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_cart',
			[
				'label'   => __('Add to Cart', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_navigation_dots',
			[
				'label'   => esc_html__('Show Dots', 'bdthemes-prime-slider'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_navigation_arrows',
			[
				'label'   => esc_html__('Show Arrows', 'bdthemes-prime-slider'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_navigation_pagination',
			[
				'label'   => esc_html__('Show Pagination', 'bdthemes-prime-slider'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label'   => __('Title HTML Tag', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => prime_slider_title_tags(),
				'condition' => [
					'show_title' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'content_alignment',
			[
				'label'   => esc_html__('Alignment', 'bdthemes-prime-slider'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'bdthemes-prime-slider'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'bdthemes-prime-slider'),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'bdthemes-prime-slider'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_position',
			[
				'label'     => esc_html__('Content Position', 'bdthemes-prime-slider') . BDTPS_NC,
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'     => [
						'title' => esc_html__('Left', 'bdthemes-prime-slider'),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__('Right', 'bdthemes-prime-slider'),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'   => 'right',
				'toggle'    => false,
				'prefix_class' => 'bdt-content-position--',
			]
		);

		$this->end_controls_section();

		//New Query Builder Settings
		$this->start_controls_section(
			'section_post_query_builder',
			[
				'label' => esc_html__('Query', 'bdthemes-prime-slider') . BDTPS_NC,
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->register_query_builder_controls();

		$this->update_control(
			'posts_source',
			[
				'type'      => Controls_Manager::SELECT,
				'default'   => 'product',
				'options' => [
					'product' 			 => esc_html__('Product', 'bdthemes-prime-slider'),
					'manual_selection'   => esc_html__('Manual Selection', 'bdthemes-prime-slider'),
					'current_query'      => esc_html__('Current Query', 'bdthemes-prime-slider'),
					'_related_post_type' => esc_html__('Related', 'bdthemes-prime-slider'),
				],
				'condition' => [
					'is_replaced_deprecated_query' => 'yes',
				]
			]
		);
		$this->update_control(
			'posts_limit',
			[
				'label'     => esc_html__('Limit', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3,
				'condition' => [
					'is_replaced_deprecated_query' => 'yes',
				]
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_query',
			[
				'label' => __('Query (Deprecated)', 'bdthemes-prime-slider'),
				'condition' => [
					'is_replaced_deprecated_query!' => 'yes'
				]
			]
		);

		$this->add_control(
			'source',
			[
				'label'   => _x('Source', 'Posts Query Control', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''        => __('Show All', 'bdthemes-element-pack'),
					'by_name' => __('Manual Selection', 'bdthemes-element-pack'),
				],
				'label_block' => true,
			]
		);


		$product_categories = get_terms('product_cat');

		$options = [];
		foreach ($product_categories as $category) {
			$options[$category->slug] = $category->name;
		}

		$this->add_control(
			'product_categories',
			[
				'label'       => __('Categories', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $options,
				'default'     => [],
				'label_block' => true,
				'multiple'    => true,
				'condition'   => [
					'source'    => 'by_name',
				],
			]
		);

		$this->add_control(
			'exclude_products',
			[
				'label'       => esc_html__('Exclude Product(s)', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::TEXT,
				'placeholder'     => 'product_id',
				'label_block' => true,
				'description' => __('Write product id here, if you want to exclude multiple products so use comma as separator. Such as 1 , 2', ''),
			]
		);

		$this->add_control(
			'posts',
			[
				'label'   => __('Product Limit', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
			]
		);

		$this->add_control(
			'show_product_type',
			[
				'label'   => esc_html__('Show Product', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => [
					'all'      => esc_html__('All Products', 'bdthemes-element-pack'),
					'onsale'   => esc_html__('On Sale', 'bdthemes-element-pack'),
					'featured' => esc_html__('Featured', 'bdthemes-element-pack'),
				],
			]
		);

		$this->add_control(
			'hide_free',
			[
				'label'   => esc_html__('Hide Free', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'hide_out_stock',
			[
				'label'   => esc_html__('Hide Out of Stock', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__('Order by', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date'  => esc_html__('Date', 'bdthemes-element-pack'),
					'price' => esc_html__('Price', 'bdthemes-element-pack'),
					'sales' => esc_html__('Sales', 'bdthemes-element-pack'),
					'rand'  => esc_html__('Random', 'bdthemes-element-pack'),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => esc_html__('Order', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' => esc_html__('Descending', 'bdthemes-element-pack'),
					'ASC'  => esc_html__('Ascending', 'bdthemes-element-pack'),
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_style_animation',
			[
				'label' => esc_html__('Animation', 'bdthemes-prime-slider'),
			]
		);

		$this->add_control(
			'finite',
			[
				'label'   => esc_html__('Loop', 'bdthemes-prime-slider'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'   => esc_html__('Autoplay', 'bdthemes-prime-slider'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'autoplay_interval',
			[
				'label'     => esc_html__('Autoplay Interval (ms)', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 7000,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label'     => esc_html__('Pause on Hover', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'velocity',
			[
				'label'   => __('Animation Speed', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 1,
						'step' => 0.1,
					],
				],
			]
		);

		$this->add_control(
			'kenburns_animation',
			[
				'label'     => esc_html__('Kenburns Animation', 'bdthemes-prime-slider'),
				'separator' => 'before',
				'type'      => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'kenburns_reverse',
			[
				'label'     => esc_html__('Kenburn Reverse', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'kenburns_animation' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		//Style Start

		$this->start_controls_section(
			'section_style_sliders',
			[
				'label'     => esc_html__('Sliders', 'bdthemes-prime-slider'),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'glassmorphism_effect',
			[
				'label' => esc_html__('Glassmorphism', 'bdthemes-element-pack') . BDTPS_NC,
				'type'  => Controls_Manager::SWITCHER,
				'description' => sprintf(__('This feature will not work in the Firefox browser untill you enable browser compatibility so please %1s look here %2s', 'bdthemes-element-pack'), '<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/backdrop-filter#Browser_compatibility" target="_blank">', '</a>'),

			]
		);

		$this->add_control(
			'glassmorphism_blur_level',
			[
				'label'       => __('Blur Level', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => 0,
						'step' => 1,
						'max'  => 50,
					]
				],
				'default'     => [
					'size' => 5
				],
				'selectors'   => [
					'{{WRAPPER}} .bdt-prime-slider-skin-woostand .bdt-ps-slideshow-content-wrapper .bdt-ps-slideshow-inner' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);'
				],
				'condition' => [
					'glassmorphism_effect' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'inner_background_color',
				'selector' => '{{WRAPPER}} .bdt-prime-slider-skin-woostand .bdt-ps-slideshow-content-wrapper .bdt-ps-slideshow-inner',
			]
		);

		$this->start_controls_tabs('slider_item_style');

		$this->start_controls_tab(
			'slider_title_style',
			[
				'label' 	=> __('Title', 'bdthemes-prime-slider'),
				'condition' => [
					'show_title' => ['yes'],
				],
			]
		);

		$this->add_control(
			'show_text_stroke',
			[
				'label'   => esc_html__('Text Stroke', 'bdthemes-prime-slider') . BDTPS_NC,
				'type'    => Controls_Manager::SWITCHER,
				'prefix_class' => 'bdt-text-stroke--',
				'condition' => [
					'show_title' => ['yes'],
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__('Color', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-title a' => 'color: {{VALUE}}; -webkit-text-stroke-color: {{VALUE}};',
				],
				'condition' => [
					'show_title' => ['yes'],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__('Typography', 'bdthemes-prime-slider'),
				'selector' => '{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-title',
				'condition' => [
					'show_title' => ['yes'],
				],
			]
		);

		$this->add_responsive_control(
			'prime_slider_title_spacing',
			[
				'label' => esc_html__('Title Spacing', 'bdthemes-prime-slider'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-title' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_title' => ['yes'],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'slider_category_style',
			[
				'label' 	=> __('Category', 'bdthemes-prime-slider'),
				'condition' => [
					'show_category' => ['yes'],
				],
			]
		);

		$this->add_control(
			'category_heading_normal',
			[
				'label' => __('Normal', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'category_color',
			[
				'label'     => esc_html__('Color', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-category a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'category_background_color',
			[
				'label'     => esc_html__('Background', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-category a' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'category_border',
				'label'       => __('Border', 'bdthemes-element-pack'),
				'selector'    => '{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-category a',
			]
		);

		$this->add_control(
			'category_border_radius',
			[
				'label' 	 => __('Border Radius', 'bdthemes-prime-slider'),
				'type' 		 => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-category a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'category_padding',
			[
				'label'      => __('Padding', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'category_typography',
				'label'    => esc_html__('Typography', 'bdthemes-prime-slider'),
				'selector' => '{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-category a',
			]
		);

		$this->add_responsive_control(
			'prime_slider_category_spacing',
			[
				'label' => esc_html__('Spacing', 'bdthemes-prime-slider'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-category' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_category' => ['yes'],
				],
			]
		);

		$this->add_control(
			'category_heading_hover',
			[
				'label' => __('Hover', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'category_hover_color',
			[
				'label'     => esc_html__('Color', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-category a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'category_hover_background_color',
			[
				'label'     => esc_html__('Background', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-category a:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'category_hover_border_color',
			[
				'label'     => __('Border Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-category a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hide_comma',
			[
				'label'   => esc_html__('Hide Comma', 'bdthemes-prime-slider'),
				'type'    => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_style_price',
			[
				'label'     => __('Price', 'bdthemes-element-pack'),
				'condition' => [
					'show_price' => 'yes',
				],
			]
		);

		$this->add_control(
			'old_price_heading',
			[
				'label' => __('Old Price', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'old_price_color',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-price del, {{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-price .price > span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'old_price_margin',
			[
				'label'      => __('Margin', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-price del, {{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-price .price > span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'old_price_typography',
				'label'    => __('Typography', 'bdthemes-element-pack'),
				'selector' => '{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-price del, {{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-price .price > span',
			]
		);

		$this->add_control(
			'sale_price_heading',
			[
				'label'     => __('Sale Price', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sale_price_color',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-price ins' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'sale_price_margin',
			[
				'label'      => __('Margin', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-price ins' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sale_price_typography',
				'label'    => __('Typography', 'bdthemes-element-pack'),
				'selector' => '{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-price ins',
			]
		);


		$this->add_responsive_control(
			'sale_price_spacing',
			[
				'label'      => __('Spacing', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label'     => __('Add to Cart Button', 'bdthemes-element-pack'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_cart' => 'yes',
				],
			]
		);

		$this->start_controls_tabs('tabs_button_style');

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __('Normal', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background',
			[
				'label'     => __('Background', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_shadow',
				'selector' => '{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'button_border',
				'label'       => __('Border', 'bdthemes-element-pack'),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .button',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'button_radius',
			[
				'label'      => __('Radius', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_padding',
			[
				'label'      => __('Padding', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'label'    => __('Typography', 'bdthemes-element-pack'),
				'selector' => '{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __('Hover', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'button_hover_background',
			[
				'label' => __('Background', 'bdthemes-element-pack'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => __('Border Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_quantity',
			[
				'label' => __('Quantity', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'quantity_button_color',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .input-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'quantity_button_background',
			[
				'label'     => __('Background', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .input-text' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'quantity_button_shadow',
				'selector' => '{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .input-text',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'quantity_button_border',
				'label'       => __('Border', 'bdthemes-element-pack'),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .input-text',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'quantity_button_radius',
			[
				'label'      => __('Radius', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'quantity_button_padding',
			[
				'label'      => __('Padding', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'quantity_button_typography',
				'label'    => __('Typography', 'bdthemes-element-pack'),
				'selector' => '{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-add-to-cart .input-text',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			[
				'label'     => __('Navigation', 'bdthemes-prime-slider'),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'navi_background_color',
				'selector' => '{{WRAPPER}} .bdt-prime-slider-skin-woostand .ps-navigation-wrapper',
			]
		);

		$this->start_controls_tabs('tabs_navigation_style');

		$this->start_controls_tab(
			'tab_navigation_arrows_style',
			[
				'label' 	=> __('Arrows', 'bdthemes-prime-slider'),
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label'     => __('Color', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-previous svg, {{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-next svg' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_navigation_arrows' => ['yes'],
				],
			]
		);

		$this->add_control(
			'arrows_hover_color',
			[
				'label'     => __('Hover Color', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-previous:hover svg, {{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-next:hover svg' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_navigation_arrows' => ['yes'],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_navigation_dots_style',
			[
				'label' 	=> __('Dots', 'bdthemes-prime-slider'),
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label'     => __('Color', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-dotnav-dots li a' => 'background: {{VALUE}}',
				],
				'condition' => [
					'show_navigation_dots' => ['yes'],
				],
			]
		);

		$this->add_control(
			'dots_active_color',
			[
				'label'     => __('Active Color', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-dotnav-dots li.bdt-active a' => 'background: {{VALUE}}',
				],
				'condition' => [
					'show_navigation_dots' => ['yes'],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_navigation_pagination_style',
			[
				'label' 	=> __('Pagination', 'bdthemes-prime-slider'),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'     => __('Color', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-dotnav span' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_navigation_pagination' => ['yes'],
				],
			]
		);

		$this->add_control(
			'pagination_active_color',
			[
				'label'     => __('Active Color', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-dotnav li a' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_navigation_pagination' => ['yes'],
				],
			]
		);

		$this->add_control(
			'pagination_separator_color',
			[
				'label'     => __('Separator Color', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-dotnav span:before' => 'background: {{VALUE}}',
				],
				'condition' => [
					'show_navigation_pagination' => ['yes'],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_additional',
			[
				'label'     => __('Additional', 'bdthemes-prime-slider'),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_padding',
			[
				'label'      => __('Content Inner Padding', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-ps-slideshow-content-wrapper .bdt-ps-slideshow-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'overlay',
			[
				'label'   => esc_html__('Overlay', 'bdthemes-prime-slider'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'background',
				'options' => [
					'none'       => esc_html__('None', 'bdthemes-prime-slider'),
					'background' => esc_html__('Background', 'bdthemes-prime-slider'),
					'blend'      => esc_html__('Blend', 'bdthemes-prime-slider'),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label'     => esc_html__('Overlay Color', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'overlay' => ['background', 'blend']
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-slideshow .bdt-overlay-default' => 'background-color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'blend_type',
			[
				'label'     => esc_html__('Blend Type', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'multiply',
				'options'   => prime_slider_blend_options(),
				'condition' => [
					'overlay' => 'blend',
				],
			]
		);

		$this->end_controls_section();
	}

	//WC-Slider
	public function render_query() {
		$settings = $this->get_settings_for_display();
		$default = $this->getGroupControlQueryArgs();
		$exclude_products = ($settings['exclude_products']) ? explode(',', $settings['exclude_products']) : [];

		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $settings['posts'],
			'no_found_rows'       => true,
			'meta_query'          => [],
			'tax_query'           => ['relation' => 'AND'],
			'order'               => $settings['order'],
			'post__not_in'        => $exclude_products,
		);

		$product_visibility_term_ids = wc_get_product_visibility_term_ids();

		if ('by_name' === $settings['source'] and !empty($settings['product_categories'])) {
			$query_args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $settings['product_categories'],
				'post__not_in'        => $exclude_products,
			);
		}

		if ('yes' == $settings['hide_free']) {
			$query_args['meta_query'][] = array(
				'key'     => '_price',
				'value'   => 0,
				'compare' => '>',
				'type'    => 'DECIMAL',
			);
		}

		if ('yes' == $settings['hide_out_stock']) {
			$query_args['tax_query'][] = array(
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => $product_visibility_term_ids['outofstock'],
					'operator' => 'NOT IN',
				),
			); // WPCS: slow query ok.
		}

		switch ($settings['show_product_type']) {
			case 'featured':
				$query_args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => $product_visibility_term_ids['featured'],
				);
				break;
			case 'onsale':
				$product_ids_on_sale    = wc_get_product_ids_on_sale();
				$product_ids_on_sale[]  = 0;
				$query_args['post__in'] = $product_ids_on_sale;
				break;
		}

		switch ($settings['orderby']) {
			case 'price':
				$query_args['meta_key'] = '_price'; // WPCS: slow query ok.
				$query_args['orderby']  = 'meta_value_num';
				break;
			case 'rand':
				$query_args['orderby'] = 'rand';
				break;
			case 'sales':
				$query_args['meta_key'] = 'total_sales'; // WPCS: slow query ok.
				$query_args['orderby']  = 'meta_value_num';
				break;
			default:
				$query_args['orderby'] = 'date';
		}

		if (
			isset($settings['is_replaced_deprecated_query']) &&
			$settings['is_replaced_deprecated_query'] == 'yes'
		) {
			$wp_query = new WP_Query($default);
		} else {

			$wp_query = new WP_Query($query_args);
		}

		return $wp_query;
	}

	public function render_header($skin_name = 'woostand') {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute('header', 'class', 'bdt-prime-header-skin-' . $skin_name);
		$this->add_render_attribute('slider', 'class', 'bdt-prime-slider-skin-' . $skin_name);

		$ratio = ($settings['slider_size_ratio']['width'] && $settings['slider_size_ratio']['height']) ? $settings['slider_size_ratio']['width'] . ":" . $settings['slider_size_ratio']['height'] : '16:9';

		$this->add_render_attribute(
			[
				'slideshow' => [
					'bdt-slideshow' => [
						wp_json_encode([
							"animation"         => 'fade',
							"ratio"             => $ratio,
							"min-height"        => ($settings["slider_min_height"]["size"]) ? $settings["slider_min_height"]["size"] : 460,
							"autoplay"          => ($settings["autoplay"]) ? true : false,
							"autoplay-interval" => $settings["autoplay_interval"],
							"pause-on-hover"    => ("yes" === $settings["pause_on_hover"]) ? true : false,
							"velocity"          => ($settings["velocity"]["size"]) ? $settings["velocity"]["size"] : 1,
							"finite"            => ($settings["finite"]) ? false : true,
						])
					]
				]
			]
		);

?>
		<div class="bdt-prime-slider">

			<div <?php $this->print_render_attribute_string('slider'); ?>>

				<div class="bdt-position-relative bdt-visible-toggle" <?php $this->print_render_attribute_string('slideshow'); ?>>

					<ul class="bdt-slideshow-items">
					<?php
				}

				public function render_navigation_arrows() {
					$settings = $this->get_settings_for_display();

					?>

						<?php if ($settings['show_navigation_arrows']) : ?>
							<div class="bdt-navigation-arrows">
								<a class="bdt-prime-slider-previous" href="#" bdt-slidenav-previous bdt-slideshow-item="previous"></a>
								<a class="bdt-prime-slider-next" href="#" bdt-slidenav-next bdt-slideshow-item="next"></a>
							</div>


						<?php endif; ?>

					<?php
				}

				public function render_navigation_dots() {
					$settings  = $this->get_active_settings();

					?>

						<?php if ('yes' == $settings['show_navigation_pagination']) : ?>
							<ul class="bdt-ps-dotnav">
								<?php $slide_index = 1;
								$wp_query = $this->render_query();
								while ($wp_query->have_posts()) : $wp_query->the_post();
								?>

									<li bdt-slideshow-item="<?php echo ($slide_index - 1); ?>" data-label="<?php echo str_pad($slide_index, 2, '0', STR_PAD_LEFT); ?>"><a href="#"><?php echo str_pad($slide_index, 2, '0', STR_PAD_LEFT); ?></a>
										<?php $slide_index++; ?>
									</li>

								<?php
								endwhile;
								wp_reset_postdata();
								?>
								<span><?php echo str_pad($slide_index - 1, 2, '0', STR_PAD_LEFT); ?></span>
							</ul>
						<?php endif; ?>

						<?php if ('yes' == $settings['show_navigation_dots']) : ?>
							<ul class="bdt-slideshow-nav bdt-dotnav-dots bdt-dotnav"></ul>
						<?php endif; ?>

					<?php
				}

				public function render_footer() {
					?>

					</ul>

					<div class="ps-navigation-wrapper">
						<?php $this->render_navigation_dots(); ?>
						<?php $this->render_navigation_arrows(); ?>
					</div>

				</div>

			</div>
		<?php
				}

				public function render_item_content() {
					$settings = $this->get_settings_for_display();

					if ('yes' == $settings['hide_comma']) {
						$this->add_render_attribute('comma', 'class', 'bdt-hide-comma', true);
					}

		?>
			<div class="bdt-ps-slideshow-content-wrapper">
				<div class="bdt-ps-slideshow-inner">
					<?php if ($settings['show_category']) : ?>
						<div class="bdt-ps-category">
							<span <?php $this->print_render_attribute_string('comma'); ?>>
								<?php echo wc_get_product_category_list(get_the_ID(), '<span class="hide-comma">,</span> '); ?>
							</span>
						</div>
					<?php endif; ?>

					<?php if ($settings['show_title']) : ?>
						<<?php echo Utils::get_valid_html_tag($settings['title_html_tag']); ?> class="bdt-ps-title">
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</<?php echo Utils::get_valid_html_tag($settings['title_html_tag']); ?>>
					<?php endif; ?>

					<?php if ($settings['show_price']) : ?>
						<div class="bdt-ps-price">
							<span class="wae-product-price"><?php woocommerce_template_single_price(); ?></span>
						</div>
					<?php endif; ?>

					<?php if ($settings['show_cart']) : ?>
						<div class="bdt-ps-add-to-cart-btn">
							<?php if ($settings['show_cart']) : ?>
								<div class="bdt-ps-add-to-cart">
									<?php woocommerce_template_single_add_to_cart(); ?>
								</div>
							<?php endif; ?>

						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php
				}

				public function rendar_item_image() {
					$settings = $this->get_settings_for_display();

					$placeholder_image_src = Utils::get_placeholder_image_src();
					$image_src = Group_Control_Image_Size::get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail_size', $settings);

					if ($image_src) {
						$image_final_src = $image_src;
					} elseif ($placeholder_image_src) {
						$image_final_src = $placeholder_image_src;
					} else {
						return;
					}

		?>

			<div class="bdt-ps-slide-img" style="background-image: url('<?php echo esc_url($image_final_src); ?>')"></div>

			<?php
				}

				public function render_slides_loop() {
					$settings = $this->get_settings_for_display();

					$kenburns_reverse = $settings['kenburns_reverse'] ? ' bdt-animation-reverse' : '';

					$wp_query = $this->render_query();

					while ($wp_query->have_posts()) : $wp_query->the_post();

			?>

				<li class="bdt-slideshow-item elementor-repeater-item-<?php echo get_the_ID(); ?>">

					<?php if ('yes' == $settings['kenburns_animation']) : ?>
						<div class="bdt-position-cover bdt-animation-kenburns<?php echo esc_attr($kenburns_reverse); ?> bdt-transform-origin-center-left">
						<?php endif; ?>

						<?php $this->rendar_item_image(); ?>

						<?php if ('yes' == $settings['kenburns_animation']) : ?>
						</div>
					<?php endif; ?>

					<?php if ('none' !== $settings['overlay']) :
							$blend_type = ('blend' == $settings['overlay']) ? ' bdt-blend-' . $settings['blend_type'] : ''; ?>
						<div class="bdt-overlay-default bdt-position-cover<?php echo esc_attr($blend_type); ?>"></div>
					<?php endif; ?>

					<?php $this->render_item_content(); ?>

				</li>

	<?php

					endwhile;
					wp_reset_postdata();
				}

				public function render() {

					$this->render_header();

					$this->render_slides_loop();

					$this->render_footer();
				}
			}
