<?php

namespace PrimeSlider\Modules\Fluent\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Repeater;

use PrimeSlider\Traits\Global_Widget_Controls;
use PrimeSlider\Traits\QueryControls\GroupQuery\Group_Control_Query;
use PrimeSlider\Utils;
use WP_Query;

if ( !defined('ABSPATH') ) exit; // Exit if accessed directly

class Fluent extends Widget_Base {
    use Group_Control_Query;
	use Global_Widget_Controls;

    public function get_name() {
        return 'prime-slider-fluent';
    }

    public function get_title() {
        return BDTPS . esc_html__('Fluent', 'bdthemes-prime-slider');
    }

    public function get_icon() {
        return 'bdt-widget-icon ps-wi-fluent';
    }

    public function get_categories() {
        return ['prime-slider'];
    }

    public function get_keywords() {
        return ['prime slider', 'slider', 'blog', 'prime', 'fluent'];
    }

    public function get_style_depends() {
        return ['ps-fluent'];
    }

    public function get_script_depends() {
        return ['mThumbnailScroller', 'ps-fluent'];
    }

    public function get_custom_help_url() {
    	return 'https://youtu.be/HxwdDoOsdMA';
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
                'separator'   => 'before',
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
                'label'        => esc_html__( 'Image Size', 'bdthemes-prime-slider' ) . BDTPS_NC,
                'exclude'      => [ 'custom' ],
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
                'label'   => _x( 'Position', 'bdthemes-prime-slider' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    ''              => _x( 'Default', 'bdthemes-prime-slider' ),
                    'center center' => _x( 'Center Center', 'bdthemes-prime-slider' ),
                    'center left'   => _x( 'Center Left', 'bdthemes-prime-slider' ),
                    'center right'  => _x( 'Center Right', 'bdthemes-prime-slider' ),
                    'top center'    => _x( 'Top Center', 'bdthemes-prime-slider' ),
                    'top left'      => _x( 'Top Left', 'bdthemes-prime-slider' ),
                    'top right'     => _x( 'Top Right', 'bdthemes-prime-slider' ),
                    'bottom center' => _x( 'Bottom Center', 'bdthemes-prime-slider' ),
                    'bottom left'   => _x( 'Bottom Left', 'bdthemes-prime-slider' ),
                    'bottom right'  => _x( 'Bottom Right', 'bdthemes-prime-slider' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-slideshow-item>.bdt-ps-slide-img' => 'background-position: {{VALUE}};',
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
                'label'   => _x( 'Attachment', 'bdthemes-prime-slider' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    ''       => _x( 'Default', 'bdthemes-prime-slider' ),
                    'scroll' => _x( 'Scroll', 'bdthemes-prime-slider' ),
                    'fixed'  => _x( 'Fixed', 'bdthemes-prime-slider' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-slideshow-item>.bdt-ps-slide-img' => 'background-attachment: {{VALUE}};',
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
                'label'      => _x( 'Repeat', 'bdthemes-prime-slider' ),
                'type'       => Controls_Manager::SELECT,
                'default'    => '',
                'options'    => [
                    ''          => _x( 'Default', 'bdthemes-prime-slider' ),
                    'no-repeat' => _x( 'No-repeat', 'bdthemes-prime-slider' ),
                    'repeat'    => _x( 'Repeat', 'bdthemes-prime-slider' ),
                    'repeat-x'  => _x( 'Repeat-x', 'bdthemes-prime-slider' ),
                    'repeat-y'  => _x( 'Repeat-y', 'bdthemes-prime-slider' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-slideshow-item>.bdt-ps-slide-img' => 'background-repeat: {{VALUE}};',
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
                'label'      => _x( 'Size', 'bdthemes-prime-slider' ),
                'type'       => Controls_Manager::SELECT,
                'default'    => '',
                'options'    => [
                    ''        => _x( 'Default', 'bdthemes-prime-slider' ),
                    'auto'    => _x( 'Auto', 'bdthemes-prime-slider' ),
                    'cover'   => _x( 'Cover', 'bdthemes-prime-slider' ),
                    'contain' => _x( 'Contain', 'bdthemes-prime-slider' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-slideshow-item>.bdt-ps-slide-img' => 'background-size: {{VALUE}};',
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
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'title_html_tag',
            [
                'label'     => __('Title HTML Tag', 'bdthemes-element-pack'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'h1',
                'options'   => prime_slider_title_tags(),
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
			'show_excerpt',
			[
				'label'   => __( 'Show Text', 'bdthemes-prime-slider' ) . BDTPS_NC,
				'type'    => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label'     => __( 'Text Limit', 'bdthemes-prime-slider' ),
				'description' => esc_html__('It\'s just work for main content, but not working with excerpt. If you set 0 so you will get full main content.', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 15,
				'condition' => [
                    'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
            'strip_shortcode',
            [
                'label'   => esc_html__('Strip Shortcode', 'bdthemes-prime-slider'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition'   => [
                    'show_excerpt' => 'yes',
                ],
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
            'show_admin_info',
            [
                'label'   => esc_html__('Show Admin Meta', 'bdthemes-prime-slider'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label'   => esc_html__('Show Date', 'bdthemes-prime-slider'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_social_icon',
            [
                'label'   => esc_html__('Show Social Icon', 'bdthemes-prime-slider'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_thumb_scroller',
            [
                'label'   => esc_html__('Show Thumb Scroller', 'bdthemes-prime-slider'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_navigation_arrows',
            [
                'label'   => esc_html__('Show Navigation Arrows', 'bdthemes-prime-slider'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_scroll_button',
            [
                'label'   => esc_html__('Show Scroll Button', 'bdthemes-prime-slider'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'content_alignment',
            [
                'label'     => esc_html__('Alignment', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__('Left', 'bdthemes-prime-slider'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'bdthemes-prime-slider'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'bdthemes-prime-slider'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ps_meta_alignment',
            [
                'label'     => esc_html__('Meta Alignment', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'     => [
                        'title' => esc_html__('Left', 'bdthemes-prime-slider'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'   => [
                        'title' => esc_html__('Center', 'bdthemes-prime-slider'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'bdthemes-prime-slider'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider-fluent .bdt-prime-slider-meta' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_scroll_button',
            [
                'label'     => esc_html__('Scroll Down', 'bdthemes-prime-slider'),
                'condition' => [
                    'show_scroll_button' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'duration',
            [
                'label'      => esc_html__('Duration', 'bdthemes-prime-slider'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 100,
                        'max'  => 5000,
                        'step' => 50,
                    ],
                ],
            ]
        );

        $this->add_control(
            'offset',
            [
                'label' => esc_html__('Offset', 'bdthemes-prime-slider'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => -200,
                        'max'  => 200,
                        'step' => 10,
                    ],
                ],
            ]
        );

        $this->add_control(
            'section_id',
            [
                'label'       => esc_html__('Section ID', 'bdthemes-prime-slider'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'my-header',
                'description' => esc_html__("By clicking this scroll button, to which section in your page you want to go? Just write that's section ID here such 'my-header'. N.B: No need to add '#'.", 'bdthemes-prime-slider'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_social_link',
            [
                'label'     => __('Social Icon', 'bdthemes-prime-slider'),
                'condition' => [
                    'show_social_icon' => 'yes',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'social_link_title',
            [
                'label'   => __('Title', 'bdthemes-prime-slider'),
                'type'    => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'social_link',
            [
                'label'   => __('Link', 'bdthemes-prime-slider'),
                'type'    => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'social_icon',
            [
                'label' => __('Choose Icon', 'bdthemes-prime-slider'),
                'type'  => Controls_Manager::ICONS,
            ]
        );

        $this->add_control(
            'social_link_list',
            [
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'social_link'       => __('http://www.facebook.com/bdthemes/', 'bdthemes-prime-slider'),
                        'social_icon'       => ['value' => 'fab fa-facebook-f', 'library' => 'fa-brands'],
                        'social_link_title' => 'Facebook',
                    ],
                    [
                        'social_link'       => __('http://www.twitter.com/bdthemes/', 'bdthemes-prime-slider'),
                        'social_icon'       => ['value' => 'fab fa-twitter', 'library' => 'fa-brands'],
                        'social_link_title' => 'Twitter',
                    ],
                    [
                        'social_link'       => __('http://www.vimeo.com//bdthemes/', 'bdthemes-prime-slider'),
                        'social_icon'       => ['value' => 'fab fa-vimeo-v', 'library' => 'fa-brands'],
                        'social_link_title' => 'Vimeo',
                    ],
                    [
                        'social_link'       => __('http://www.instagram.com/bdthemes/', 'bdthemes-prime-slider'),
                        'social_icon'       => ['value' => 'fab fa-instagram', 'library' => 'fa-brands'],
                        'social_link_title' => 'Instagram',
                    ],
                ],
                'title_field' => '{{{ social_link_title }}}',
            ]
        );

        $this->end_controls_section();

        //New Query Builder Settings
        $this->start_controls_section(
            'section_post_query_builder',
            [
                'label' => __( 'Query', 'bdthemes-prime-slider' ) . BDTPS_NC,
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->register_query_builder_controls();

        $this->update_control(
            'posts_limit',
            [
                'type'      => Controls_Manager::NUMBER,
                'default'   => 9,
            ]
        );

        $this->end_controls_section();

        //Global Widget Controls
        $this->start_controls_section(
            'section_content_query',
            [
                'label' => esc_html__( 'Query (deprecated)', 'bdthemes-prime-slider' ),
                'condition' => [
                    'is_replaced_deprecated_query!' => 'yes'
                ]
            ]
        );

        $this->register_query_controls();

        $this->update_control(
            'limit',
            [
                'type'      => Controls_Manager::NUMBER,
                'default'   => 9,
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
                'label' => esc_html__('Pause on Hover', 'bdthemes-prime-slider'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'velocity',
            [
                'label' => __('Animation Speed', 'bdthemes-element-pack'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0.1,
                        'max'  => 1,
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
                'label' => esc_html__('Sliders', 'bdthemes-prime-slider'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'overlay',
            [
                'label'     => esc_html__('Overlay', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'background',
                'options'   => [
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

        $this->add_control(
            'ps_content_innner_padding',
            [
                'label'      => esc_html__('Content Inner Padding', 'bdthemes-prime-slider'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_slider_style');

        $this->start_controls_tab(
            'tab_slider_title',
            [
                'label' => __('Title', 'bdthemes-prime-slider'),
            ]
        );

        $this->add_responsive_control(
            'title_width',
            [
                'label'     => esc_html__('Title Width', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 220,
                        'max' => 1200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-main-title' => 'width: {{SIZE}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-main-title .bdt-title-tag a' => 'color: {{VALUE}}; -webkit-text-stroke-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_title' => ['yes'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'title_typography',
                'label'     => esc_html__('Typography', 'bdthemes-prime-slider'),
                'selector'  => '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-main-title .bdt-title-tag',
                'condition' => [
                    'show_title' => ['yes'],
                ],
            ]
        );

        $this->add_responsive_control(
            'prime_slider_title_spacing',
            [
                'label'     => esc_html__('Title Spacing', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-main-title .bdt-title-tag' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_title' => ['yes'],
                ],
            ]
        );

        $this->add_control(
			'first_word_style',
			[
				'label'   => esc_html__('First Word Style', 'bdthemes-prime-slider'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'first_word_title_color',
			[
				'label'     => esc_html__('Color', 'bdthemes-prime-slider'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-main-title .bdt-title-tag .frist-word' => 'color: {{VALUE}}; -webkit-text-stroke-color: {{VALUE}};',
				],
				'condition' => [
					'show_title' => ['yes'],
					'first_word_style' => ['yes'],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'first_word_typography',
				'selector' => '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-main-title .frist-word',
				'condition' => [
					'show_title' => ['yes'],
					'first_word_style' => ['yes'],
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_slider_text',
            [
                'label' => __('Text', 'bdthemes-prime-slider') . BDTPS_NC,
                'condition' => [
					'show_excerpt' => 'yes'
				],
            ]
        );

        $this->add_control(
			'excerpt_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-blog-text' => 'color: {{VALUE}};',
                ],
                'condition' => [
					'show_excerpt' => 'yes'
				],
			]
		);

		$this->add_responsive_control(
			'excerpt_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-blog-text'   => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
					'show_excerpt' => 'yes'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-blog-text',
                'condition' => [
					'show_excerpt' => 'yes'
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_slider_category',
            [
                'label'     => __('Category', 'bdthemes-prime-slider'),
                'condition' => [
                    'show_category' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'category_icon_color',
            [
                'label'     => __('Color', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-ps-category a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'category_icon_background_color',
            [
                'label'     => __('Background', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-ps-category a' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'category_border',
                'label'    => esc_html__('Border', 'bdthemes-prime-slider'),
                'selector' => '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-ps-category a',
            ]
        );

        $this->add_responsive_control(
            'category_border_radius',
            [
                'label'      => esc_html__('Radius', 'bdthemes-prime-slider'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-ps-category a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'category_padding',
            [
                'label'      => esc_html__('Padding', 'bdthemes-prime-slider'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-ps-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'category_typography',
                'label'    => esc_html__('Typography', 'bdthemes-prime-slider'),
                'selector' => '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-content .bdt-ps-category a',
            ]
        );

        $this->add_responsive_control(
            'ps_category_spacing',
            [
                'label'     => esc_html__('Spacing', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-ps-category-wrapper' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_slider_meta',
            [
                'label'     => __('Meta', 'bdthemes-prime-slider'),
                'condition' => [
                    'show_admin_info' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'meta_text_color',
            [
                'label'     => __('Color', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-meta .bdt-author' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'meta_typography',
                'label'    => esc_html__('Typography', 'bdthemes-prime-slider'),
                'selector' => '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-meta .bdt-author',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_slider_date',
            [
                'label'     => __('Date', 'bdthemes-prime-slider'),
                'condition' => [
                    'show_date' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'date_text_color',
            [
                'label'     => __('Color', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider-fluent .bdt-prime-slider-date' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'date_typography',
                'label'    => esc_html__('Typography', 'bdthemes-prime-slider'),
                'selector' => '{{WRAPPER}} .bdt-prime-slider-fluent .bdt-prime-slider-date',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_thumb_scroller',
            [
                'label'     => esc_html__('Thumb Scroller', 'bdthemes-prime-slider'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_thumb_scroller' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'thumb_scroller_item_background',
            [
                'label'     => __('Background Color', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider-fluent .bdt-ps-thumbnav .bdt-thumb-content:hover, {{WRAPPER}} .bdt-prime-slider-fluent .bdt-ps-thumbnav.bdt-active .bdt-thumb-content:before' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_thumb_scroller_style');

        $this->start_controls_tab(
            'tab_thumb_scroller_category',
            [
                'label'     => __('Category', 'bdthemes-prime-slider'),
                'condition' => [
                    'show_category' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'thumb_scroller_category_icon_color',
            [
                'label'     => __('Color', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider-fluent .bdt-ps-thumbnav .bdt-thumb-content .bdt-ps-category *' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'thumb_scroller_category_typography',
                'label'    => esc_html__('Typography', 'bdthemes-prime-slider'),
                'selector' => '{{WRAPPER}} .bdt-prime-slider-fluent .bdt-ps-thumbnav .bdt-thumb-content .bdt-ps-category *',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_thumb_scroller_title',
            [
                'label' => __('Title', 'bdthemes-prime-slider'),
            ]
        );

        $this->add_control(
            'thumb_scroller_title_color',
            [
                'label'     => esc_html__('Color', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider-fluent .bdt-ps-thumbnav .bdt-thumb-content h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'thumb_scroller_title_typography',
                'label'    => esc_html__('Typography', 'bdthemes-prime-slider'),
                'selector' => '{{WRAPPER}} .bdt-prime-slider-fluent .bdt-ps-thumbnav .bdt-thumb-content h3',
            ]
        );

        $this->add_responsive_control(
            'thumb_scroller_title_spacing',
            [
                'label'     => esc_html__('Title Spacing', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider-fluent .bdt-ps-thumbnav .bdt-thumb-content h3' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_social_icon',
            [
                'label'     => esc_html__('Social Icon', 'bdthemes-prime-slider'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_social_icon' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'social_line_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .bdt-prime-slider-fluent.bdt-ps-icon .bdt-prime-slider-content:before',
            ]
        );

        $this->start_controls_tabs('tabs_social_icon_style');

        $this->start_controls_tab(
            'tab_social_icon_normal',
            [
                'label' => esc_html__('Normal', 'bdthemes-prime-slider'),
            ]
        );

        $this->add_control(
            'social_icon_color',
            [
                'label'     => esc_html__('Color', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-social-icon i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-social-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'social_icon_background',
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-social-icon a',
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'social_icon_border',
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-social-icon a',
            ]
        );

        $this->add_control(
            'social_icon_padding',
            [
                'label'      => esc_html__('Padding', 'bdthemes-prime-slider'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-social-icon a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_icon_radius',
            [
                'label'      => esc_html__('Border Radius', 'bdthemes-prime-slider'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-social-icon a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'social_icon_shadow',
                'selector' => '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-social-icon a',
            ]
        );

        $this->add_responsive_control(
            'social_icon_size',
            [
                'label'     => __('Icon Size', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-social-icon a' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_icon_spacing',
            [
                'label'     => esc_html__('Icon Spacing', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-social-icon a' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_icon_position',
            [
                'label'     => esc_html__('Icon Position', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider-fluent .bdt-prime-slider-social-icon' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_icon_tooltip',
            [
                'label'   => esc_html__('Show Tooltip', 'bdthemes-prime-slider'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_social_icon_hover',
            [
                'label' => esc_html__('Hover', 'bdthemes-prime-slider'),
            ]
        );

        $this->add_control(
            'social_icon_hover_color',
            [
                'label'     => esc_html__('Color', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-social-icon a:hover i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-social-icon a:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'social_icon_hover_background',
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-social-icon a:hover',
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'icon_hover_border_color',
            [
                'label'     => esc_html__('Border Color', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'social_icon_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-social-icon a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_scroll_down',
            [
                'label'     => __('Scroll Down', 'bdthemes-prime-slider'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_scroll_button' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'scroll_down_primary_color',
            [
                'label'     => __('Primary Color', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider-fluent .bdt-scroll-down-wrapper .bdt-scroll-down-content-wrapper span' => '--primary-border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'scroll_down_secondary_color',
            [
                'label'     => __('Secondary Color', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider-fluent .bdt-scroll-down-wrapper .bdt-scroll-down-content-wrapper span' => '--secondary-border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_navigation',
            [
                'label'     => __('Navigation', 'bdthemes-prime-slider'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_navigation_arrows' => ['yes'],
                ],
            ]
        );

        $this->start_controls_tabs('tabs_navigation_style');

        $this->start_controls_tab(
            'tab_nav_arrows_dots_style',
            [
                'label' => __('Normal', 'bdthemes-prime-slider'),
            ]
        );

        $this->add_control(
            'arrows_color',
            [
                'label'     => __('Arrows Color', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-previous svg, {{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-next svg' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'arrows_size',
            [
                'label'     => esc_html__('Arrows Size', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider-fluent .bdt-prime-slider-next svg, {{WRAPPER}} .bdt-prime-slider-fluent .bdt-prime-slider-previous svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_spacing',
            [
                'label'     => esc_html__('Arrows Spacing', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider-fluent .bdt-prime-slider-next, .bdt-prime-slider-fluent .bdt-prime-slider-previous' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_nav_arrows_dots_hover_style',
            [
                'label' => __('Hover', 'bdthemes-prime-slider'),
            ]
        );

        $this->add_control(
            'arrows_hover_color',
            [
                'label'     => __('Arrows Color', 'bdthemes-prime-slider'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-previous:hover svg, {{WRAPPER}} .bdt-prime-slider .bdt-prime-slider-next:hover svg' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    public function query_posts() {
        $settings = $this->get_settings();
    
        if ( isset( $settings['is_replaced_deprecated_query'] ) &&
             $settings['is_replaced_deprecated_query'] == 'yes' ) {
            $args = [];
        
            if ( $settings['posts_limit'] ) {
                $args['posts_per_page'] = $settings['posts_limit'];
                $args['paged']          = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
            }
        
            $default = $this->getGroupControlQueryArgs();
            $args = array_merge( $default, $args );
        
            $query = new WP_Query( $args );
        
            return $query;
        
        } else {
            $args = [
                'post_type'      => 'post',
                'posts_per_page' => $settings['limit'],
                'orderby'        => $settings['orderby'],
                'order'          => $settings['order'],
                'post_status'    => 'publish',
            ];
        
            if ( 'by_name' === $settings['post_source'] and ! empty( $settings['post_categories'] ) ) {
                $args['tax_query'][] = [
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => $settings['post_categories'],
                ];
            }
        
            $query = new WP_Query( $args );
        
            return $query;
        }
    }

    public function render_header($skin_name = 'fluent') {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('slider', 'class', 'bdt-prime-slider-' . $skin_name);

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

        if ( 'yes' == $settings['show_social_icon'] ) {
            $this->add_render_attribute('slider', 'class', 'bdt-ps-icon');
        }

        ?>
      <div class="bdt-prime-slider">
      <div <?php $this->print_render_attribute_string('slider'); ?>>

      <div class="bdt-position-relative bdt-visible-toggle" <?php $this->print_render_attribute_string('slideshow'); ?>>

      <ul class="bdt-slideshow-items">
        <?php
    }

    public function render_category() {
        ?>
      <div class="bdt-ps-category">
        <span><?php echo get_the_category_list(', '); ?></span>
      </div>
        <?php
    }

    public function render_scroll_button() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('bdt-scroll-down', 'class', ['bdt-scroll-down']);


        if ( '' == $settings['show_scroll_button'] ) {
            return;
        }

        $this->add_render_attribute(
            [
                'bdt-scroll-down' => [
                    'data-settings' => [
                        wp_json_encode(array_filter([
                            'duration' => ('' != $settings['duration']['size']) ? $settings['duration']['size'] : '',
                            'offset'   => ('' != $settings['offset']['size']) ? $settings['offset']['size'] : '',
                        ]))
                    ]
                ]
            ]
        );

        $this->add_render_attribute('bdt-scroll-down', 'data-selector', '#' . esc_attr($settings['section_id']));
        $this->add_render_attribute('bdt-scroll-wrapper', 'class', 'bdt-scroll-down-wrapper');

        ?>
      <div <?php $this->print_render_attribute_string('bdt-scroll-wrapper'); ?>>
        <div <?php $this->print_render_attribute_string('bdt-scroll-down'); ?>>
            <div bdt-scrollspy="cls: bdt-animation-slide-bottom; repeat: true">
                <div class="bdt-scroll-down-content-wrapper">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
      </div>

        <?php
    }

    public function render_social_link($position = 'left') {
        $settings = $this->get_settings_for_display();

        if ( '' == $settings['show_social_icon'] ) {
            return;
        }

        $this->add_render_attribute('social-icon', 'class', 'bdt-prime-slider-social-icon bdt-position-center-right');

        ?>

      <div <?php $this->print_render_attribute_string('social-icon'); ?>>

          <?php
          foreach ( $settings['social_link_list'] as $link ) :
              $tooltip = ('yes' == $settings['social_icon_tooltip']) ? ' title="' . esc_attr($link['social_link_title']) . '" bdt-tooltip="pos: ' . $position . '"' : ''; ?>

            <a href="<?php echo esc_url($link['social_link']); ?>"
               target="_blank" <?php echo wp_kses_post($tooltip); ?>>
                <?php Icons_Manager::render_icon($link['social_icon'], ['aria-hidden' => 'true', 'class' => 'fa-fw']); ?>
            </a>
          <?php endforeach; ?>
      </div>

        <?php
    }

    public function render_date() {
        $settings = $this->get_settings_for_display();

        if ( '' == $settings['show_date'] ) {
            return;
        }

        $this->add_render_attribute('date', 'class', 'bdt-prime-slider-date bdt-position-top-right', true);

        ?>

      <div <?php $this->print_render_attribute_string('date'); ?>>
          <?php if ( $settings['show_date'] ) : ?>
              <?php echo '<span>' . esc_attr(get_the_date('l, F j, Y')) . '</span>'; ?>
          <?php endif ?>
      </div>

        <?php
    }

    public function render_navigation_arrows() {
        $settings = $this->get_settings_for_display();

        ?>

        <?php if ( $settings['show_navigation_arrows'] ) : ?>
        <div class="bdt-navigation-arrows bdt-position-bottom-right bdt-position-z-index">
          <a class="bdt-prime-slider-previous" href="#" bdt-slidenav-previous bdt-slideshow-item="previous"></a>
          <a class="bdt-prime-slider-next" href="#" bdt-slidenav-next bdt-slideshow-item="next"></a>
        </div>
        <?php endif; ?>

        <?php

    }

    public function render_thumbnav() {
        $settings = $this->get_settings_for_display();
        ?>

        <?php if ( 'yes' == $settings['show_thumb_scroller'] ) : ?>
        <div class="bdt-thumbnav-wrapper bdt-position-center-left">
          <div class="bdt-thumbnav-scroller">
            <ul>
                <?php
                $slide_index = 1;

                global $post;

                $wp_query = $this->query_posts();

                if ( !$wp_query->found_posts ) {
                    return;
                }

                while ( $wp_query->have_posts() ) {
                    $wp_query->the_post();

                    ?>

                  <li class="bdt-ps-thumbnav" bdt-slideshow-item="<?php echo($slide_index - 1); ?>">
                    <a href="#">
                      <div class="bdt-thumb-content">
                        <?php if ( 'yes' == $settings['show_category'] ) : ?>
                          <?php $this->render_category(); ?>
                        <?php endif; ?>
                        <?php if ( 'yes' == $settings['show_title'] ) : ?>
                        <h3><?php echo get_the_title(); ?></h3>
                        <?php endif; ?>
                      </div>
                    </a>
                      <?php $slide_index++; ?>
                  </li>

                    <?php
                }

                wp_reset_postdata(); ?>

            </ul>
          </div>
        </div>
        <?php endif; ?>

        <?php
    }

    public function render_footer() {
        ?>

      </ul>

        <?php $this->render_navigation_arrows(); ?>

        <?php $this->render_thumbnav(); ?>

      </div>

        <?php $this->render_social_link(); ?>
        <?php $this->render_scroll_button(); ?>
      </div>
      </div>
        <?php
    }

    public function rendar_item_image() {
        $settings = $this->get_settings_for_display();

        $placeholder_image_src = Utils::get_placeholder_image_src();
        $image_src = Group_Control_Image_Size::get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail_size', $settings);
        
        if ( $image_src ) {
            $image_final_src = $image_src;
        } elseif ( $placeholder_image_src ) {
            $image_final_src = $placeholder_image_src;
        } else {
            return;
        }

        ?>

        <div class="bdt-ps-slide-img" style="background-image: url('<?php echo esc_url($image_final_src); ?>')"></div>

        <?php
    }

    public function render_excerpt() {
		if ( ! $this->get_settings( 'show_excerpt' ) ) {
			return;
		}

		$strip_shortcode = $this->get_settings_for_display('strip_shortcode');

		?>
		<div class="bdt-blog-text">
			<?php 
				if ( has_excerpt() ) {
					the_excerpt();
				} else {
					echo prime_slider_custom_excerpt($this->get_settings_for_display('excerpt_length'), $strip_shortcode);
				}
			?>
		</div>
		<?php
	}

    public function render_item_content($post) {
        $settings = $this->get_settings_for_display();

        ?>

      <div class="bdt-prime-slider-content">

        <?php $this->render_date(); ?>

        <?php if ( 'yes' == $settings['show_category'] ) : ?>
        <div class="bdt-ps-category-wrapper">
            <?php $this->render_category(); ?>
        </div>
        <?php endif; ?>

        <?php if ( 'yes' == $settings['show_admin_info'] ) : ?>
        <div class="bdt-prime-slider-meta bdt-flex bdt-flex-middile" bdt-slideshow-parallax="y: 70,-30">
          <div class="bdt-post-slider-author bdt-margin-small-right bdt-border-circle bdt-overflow-hidden">
              <?php echo get_avatar(get_the_author_meta('ID'), 42); ?>
          </div>
          <div class="bdt-meta-author bdt-flex bdt-flex-middle">
            <span
                class="bdt-author bdt-text-capitalize"><?php esc_html_e('Published by ', 'bdthemes-prime-slider'); ?><?php echo esc_attr(get_the_author()); ?> </span>
          </div>
        </div>
        <?php endif; ?>

        <?php if ( 'yes' == $settings['show_title'] ) : ?>
        <div class="bdt-main-title">
        <<?php echo Utils::get_valid_html_tag($settings['title_html_tag']); ?> class="bdt-title-tag" bdt-slideshow-parallax="y: 80,0,-80;
        opacity: 1,1,0">

        <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
            <?php echo prime_slider_first_word(get_the_title()); ?>
        </a>

        </<?php echo Utils::get_valid_html_tag($settings['title_html_tag']); ?>>
        </div>
        <?php endif; ?>

        <?php $this->render_excerpt();?>

      </div>

        <?php
    }

    public function render_slides_loop() {
        $settings = $this->get_settings_for_display();

        $kenburns_reverse = $settings['kenburns_reverse'] ? ' bdt-animation-reverse' : '';

        $slide_index = 1;

        global $post;

        $wp_query = $this->query_posts();

        if ( !$wp_query->found_posts ) {
            return;
        }

        while ( $wp_query->have_posts() ) {
            $wp_query->the_post();

            ?>

          <li class="bdt-slideshow-item bdt-flex bdt-flex-middle elementor-repeater-item-<?php echo get_the_ID(); ?>">

              <?php if ('yes' == $settings['kenburns_animation']) : ?>
            <div
                class="bdt-position-cover bdt-animation-kenburns<?php echo esc_attr($kenburns_reverse); ?> bdt-transform-origin-center-left">
                <?php endif; ?>

                <?php $this->rendar_item_image(); ?>

                <?php if ('yes' == $settings['kenburns_animation']) : ?>
            </div>
          <?php endif; ?>

              <?php if ( 'none' !== $settings['overlay'] ) :
                  $blend_type = ('blend' == $settings['overlay']) ? ' bdt-blend-' . $settings['blend_type'] : ''; ?>
                <div class="bdt-overlay-default bdt-position-cover<?php echo esc_attr($blend_type); ?>"></div>
              <?php endif; ?>

              <?php $this->render_item_content($post, $slide_index); ?>

              <?php $slide_index++; ?>

          </li>


            <?php
        }

        wp_reset_postdata();

    }

    public function render() {

        $this->render_header();

        $this->render_slides_loop();

        $this->render_footer();
    }

}