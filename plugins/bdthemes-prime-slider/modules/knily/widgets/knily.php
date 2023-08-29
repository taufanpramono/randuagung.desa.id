<?php
    
    namespace PrimeSlider\Modules\Knily\Widgets;
    
    use Elementor\Controls_Manager;
    use Elementor\Group_Control_Background;
    use Elementor\Group_Control_Border;
    use Elementor\Group_Control_Box_Shadow;
    use Elementor\Group_Control_Image_Size;
    use Elementor\Group_Control_Typography;
    use Elementor\Group_Control_Text_Shadow;
    use Elementor\Widget_Base;
    use Elementor\Repeater;

    use PrimeSlider\Traits\Global_Widget_Controls;
    use PrimeSlider\Traits\QueryControls\GroupQuery\Group_Control_Query;
    use PrimeSlider\Utils;
    use WP_Query;

    use PrimeSlider\Modules\Knily\Module;
    
    if ( !defined( 'ABSPATH' ) ) {
        exit;
    }

// Exit if accessed directly
    
    class Knily extends Widget_Base {

        use Group_Control_Query;
	    use Global_Widget_Controls;
        
        public function get_name() {
            return 'prime-slider-knily';
        }
        
        public function get_title() {
            return BDTPS . esc_html__( 'Knily', 'bdthemes-prime-slider' );
        }
        
        public function get_icon() {
            return 'bdt-widget-icon ps-wi-knily bdt-new';
        }
        
        public function get_categories() {
            return ['prime-slider'];
        }
        
        public function get_keywords() {
            return ['prime slider', 'slider', 'knily', 'prime', 'blog', 'post', 'news'];
        }
        
        public function get_style_depends() {
            return ['ps-knily'];
        }

        public function get_script_depends() {
            return ['ps-knily', 'goodshare'];
        }
        
        public function get_custom_help_url() {
            return 'https://youtu.be/VYjEPeDZv5k';
        }
        
        protected function _register_controls() {
            $this->register_query_section_controls();
        }
        
        private function register_query_section_controls() {
            
            $this->start_controls_section(
                'section_content_layout',
                [
                    'label' => esc_html__( 'Layout', 'bdthemes-prime-slider' ),
                ]
            );

            $this->add_responsive_control(
                'item_height',
                [
                    'label' => esc_html__('Height', 'bdthemes-prime-slider'),
                    'type'  => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'vh' ],
                    'range' => [
                        'px' => [
                            'min' => 200,
                            'max' => 1080,
                        ],
                        'vh' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                    ],
                    'selectors'   => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-item' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'content_max_width',
                [
                    'label' => esc_html__('Content Max Width', 'bdthemes-prime-slider'),
                    'type'  => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'vw', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 200,
                            'max' => 1080,
                        ],
                        'vw' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                        '%' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                    ],
                    'selectors'   => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-content' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'content_alignment',
                [
                    'label'     => esc_html__( 'Alignment', 'bdthemes-prime-slider' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'options'   => [
                        'left'   => [
                            'title' => esc_html__( 'Left', 'bdthemes-prime-slider' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'bdthemes-prime-slider' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => esc_html__( 'Right', 'bdthemes-prime-slider' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-content' => 'text-align: {{VALUE}};',
                    ],
                ]
            );
            
            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name'      => 'primary_thumbnail',
                    'exclude'   => ['custom'],
                    'default'   => 'full',
                ]
            );

            $this->end_controls_section();

            //New Query Builder Settings
	        $this->start_controls_section(
		        'section_post_query_builder',
		        [
			        'label' => __( 'Query', 'bdthemes-prime-slider' ),
			        'tab' => Controls_Manager::TAB_CONTENT,
		        ]
	        );
            
	        $this->register_query_builder_controls();

	        $this->update_control(
		        'is_replaced_deprecated_query',
		        [
			        'type'      => Controls_Manager::HIDDEN,
			        'default'   => 'yes',
		        ]
	        );
            	
	        $this->end_controls_section();

            $this->start_controls_section(
                'section_additional_settings',
                [
                    'label' => esc_html__( 'Additional Settings', 'bdthemes-prime-slider' ),
                ]
            );
            
            $this->add_control(
                'show_title',
                [
                    'label'   => esc_html__( 'Show Title', 'bdthemes-prime-slider' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'title_tags',
                [
                    'label'     => __( 'Title HTML Tag', 'bdthemes-prime-slider' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => prime_slider_title_tags(),
                    'default'   => 'h3',
                    'condition' => [
                        'show_title' => 'yes',
                    ],
                ]
            );
            
            $this->add_control(
                'show_excerpt',
                [
                    'label'     => __( 'Show Text', 'bdthemes-prime-slider' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'yes',
                    'separator' => 'before'
                ]
            );
            
            $this->add_control(
                'excerpt_length',
                [
                    'label'       => __( 'Text Limit', 'bdthemes-prime-slider' ),
                    'description' => esc_html__( 'It\'s just work for main content, but not working with excerpt. If you set 0 so you will get full main content.', 'bdthemes-prime-slider' ),
                    'type'        => Controls_Manager::NUMBER,
                    'default'     => 30,
                    'condition'   => [
                        'show_excerpt' => 'yes',
                    ],
                ]
            );
            
            $this->add_control(
                'strip_shortcode',
                [
                    'label'     => esc_html__( 'Strip Shortcode', 'bdthemes-prime-slider' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'yes',
                    'condition' => [
                        'show_excerpt' => 'yes',
                    ],
                ]
            );
            
            $this->add_control(
                'show_category',
                [
                    'label'   => esc_html__( 'Show Category', 'bdthemes-prime-slider' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'show_author',
                [
                    'label'     => esc_html__( 'Show Author', 'bdthemes-prime-slider' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'yes',
                    'separator' => 'before'
                ]
            );
            
            $this->add_control(
                'meta_separator',
                [
                    'label'       => __( 'Separator', 'bdthemes-prime-slider' ),
                    'type'        => Controls_Manager::TEXT,
                    'default'     => '//',
                    'label_block' => false,
                ]
            );
      
            $this->add_control(
				'show_date',
				[
					'label'     => esc_html__( 'Date', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => 'yes',
				]
			);
			
			$this->add_control(
				'human_diff_time',
				[
					'label'     => esc_html__( 'Human Different Time', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::SWITCHER,
					'condition' => [
						'show_date' => 'yes'
					]
				]
			);
			
			$this->add_control(
				'human_diff_time_short',
				[
					'label'       => esc_html__( 'Time Short Format', 'bdthemes-prime-slider' ),
					'description' => esc_html__( 'This will work for Hours, Minute and Seconds', 'bdthemes-prime-slider' ),
					'type'        => Controls_Manager::SWITCHER,
					'condition'   => [
						'human_diff_time' => 'yes',
						'show_date'       => 'yes'
					]
				]
			);
			
			$this->add_control(
				'show_time',
				[
					'label'     => esc_html__( 'Show Time', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::SWITCHER,
					'condition' => [
						'human_diff_time' => '',
						'show_date'       => 'yes'
					]
				]
			);

            $this->add_control(
                'show_button',
                [
                    'label'     => esc_html__( 'Show Button', 'bdthemes-prime-slider' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'yes',
                    'separator' => 'before'
                ]
            );
            
            $this->add_control(
                'button_text',
                [
                    'label'       => esc_html__( 'Button Text', 'bdthemes-prime-slider' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'More Details', 'bdthemes-prime-slider' ),
                    'default'     => esc_html__( 'More Details', 'bdthemes-prime-slider' ),
                    'label_block' => false,
                ]
            );

            $this->add_control(
                'show_social_share',
                [
                    'label'   => esc_html__('Show Social Share', 'bdthemes-prime-slider'),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'hide_thumbs',
                [
                    'label'   => esc_html__('Thumbs Hide', 'bdthemes-prime-slider'),
                    'type'    => Controls_Manager::SWITCHER,
                    'separator' => 'before',
                    'prefix_class' => 'bdt-thumbs--',
                ]
            );
    
            $this->end_controls_section();

            $this->start_controls_section(
                'section_content_social_link',
                [
                    'label' 	=> __('Social Share', 'bdthemes-prime-slider'),
                    'condition' => [
                        'show_social_share' => 'yes',
                    ],
                ]
            );
    
            $repeater = new Repeater();
    
            $medias = Module::get_social_media();
    
            $medias_names = array_keys( $medias );
    
            $repeater->add_control(
                'button',
                [
                    'label' => esc_html__( 'Social Media', 'bdthemes-prime-slider' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => array_reduce( $medias_names, function( $options, $media_name ) use ( $medias ) {
                        $options[ $media_name ] = $medias[ $media_name ]['title'];
    
                        return $options;
                    }, [] ),
                    'default' => 'facebook',
                ]
            );
    
            $repeater->add_control(
                'text',
                [
                    'label' => esc_html__( 'Custom Label', 'bdthemes-prime-slider' ),
                    'type' => Controls_Manager::TEXT,
                ]
            );
    
            $this->add_control(
                'share_buttons',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'default' => [
                        [ 'button' => 'facebook' ],
                        [ 'button' => 'linkedin' ],
                        [ 'button' => 'twitter' ],
                    ],
                    'title_field' => '{{{ button }}}',
                ]
            );
    
            $this->end_controls_section();

            $this->start_controls_section(
                'section_slider_settings',
                [
                    'label' => __('Slider Settings', 'bdthemes-prime-slider'),
                ]
            );
    
            $this->add_control(
                'autoplay',
                [
                    'label'   => __('Autoplay', 'bdthemes-prime-slider'),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'yes',
    
                ]
            );
    
            $this->add_control(
                'autoplay_speed',
                [
                    'label'     => esc_html__('Autoplay Speed', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::NUMBER,
                    'default'   => 5000,
                    'condition' => [
                        'autoplay' => 'yes',
                    ],
                ]
            );
    
            $this->add_control(
                'pauseonhover',
                [
                    'label' => esc_html__('Pause on Hover', 'bdthemes-prime-slider'),
                    'type'  => Controls_Manager::SWITCHER,
                ]
            );
    
            $this->add_control(
                'grab_cursor',
                [
                    'label'   => __('Grab Cursor', 'bdthemes-prime-slider'),
                    'type'    => Controls_Manager::SWITCHER,
                ]
            );
    
            $this->add_control(
                'speed',
                [
                    'label'   => __('Animation Speed (ms)', 'bdthemes-prime-slider'),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 500,
                    ],
                    'range' => [
                        'px' => [
                            'min'  => 100,
                            'max'  => 5000,
                            'step' => 50,
                        ],
                    ],
                ]
            );
    
            $this->add_control(
                'observer',
                [
                    'label'       => __('Observer', 'bdthemes-prime-slider'),
                    'description' => __('When you use carousel in any hidden place (in tabs, accordion etc) keep it yes.', 'bdthemes-prime-slider'),
                    'type'        => Controls_Manager::SWITCHER,
                ]
            );
    
            $this->add_control(
                'show_navigation_dots',
                [
                    'label' => __('Show Navigation Dots', 'bdthemes-prime-slider'),
                    'type'  => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'separator' => 'before'
                ]
            );

            $this->end_controls_section();

            //style
            $this->start_controls_section(
                'section_style_layout',
                [
                    'label'     => __( 'Sliders', 'bdthemes-prime-slider' ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'item_overlay_color',
                [
                    'label'     => esc_html__('Overlay Color', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-img-wrap::before' => 'background: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'content_padding',
                [
                    'label' 	 => __('Content Padding', 'bdthemes-prime-slider'),
                    'type' 		 => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'content_margin',
                [
                    'label' 	 => __('Content Margin', 'bdthemes-prime-slider'),
                    'type' 		 => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section();

            $this->start_controls_section(
                'section_style_title',
                [
                    'label'     => esc_html__('Title', 'bdthemes-prime-slider'),
                    'tab'       => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_title' => 'yes',
                    ],
                ]
            );
            
            $this->add_control(
                'title_color',
                [
                    'label'     => esc_html__('Color', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-title a' => 'color: {{VALUE}};',
                    ],
                ]
            );
    
            $this->add_control(
                'title_hover_color',
                [
                    'label'     => esc_html__('Hover Color', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-title a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );
    
            $this->add_responsive_control(
                'title_spacing',
                [
                    'label'      => esc_html__('Spacing', 'bdthemes-prime-slider'),
                    'type'       => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 100,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
    
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'title_typography',
                    'label'     => esc_html__('Typography', 'bdthemes-prime-slider'),
                    'selector'  => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-title',
                ]
            );
    
            $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'title_text_shadow',
                    'label' => __('Text Shadow', 'bdthemes-prime-slider'),
                    'selector' => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-title a',
                ]
            );
    
            $this->end_controls_section();
    
            
            $this->start_controls_section(
            	'section_style_text',
            	[
            		'label'     => esc_html__('Text', 'bdthemes-prime-slider'),
            		'tab'       => Controls_Manager::TAB_STYLE,
            		'condition' => [
            			'show_excerpt' => 'yes',
            		],
            	]
            );
    
            $this->add_control(
            	'text_color',
            	[
            		'label'     => esc_html__('Color', 'bdthemes-prime-slider'),
            		'type'      => Controls_Manager::COLOR,
            		'selectors' => [
            			'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-desc' => 'color: {{VALUE}};',
            		],
            	]
            );
    
            $this->add_responsive_control(
            	'text_margin',
            	[
            		'label' 	 => __('Margin', 'bdthemes-prime-slider'),
            		'type' 		 => Controls_Manager::DIMENSIONS,
            		'size_units' => ['px', 'em', '%'],
            		'selectors'  => [
            			'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            		],
            	]
            );
    
            $this->add_responsive_control(
            	'text_width',
            	[
            		'label' => esc_html__('Max Width', 'bdthemes-prime-slider'),
            		'type'  => Controls_Manager::SLIDER,
            		'range' => [
            			'px' => [
            				'min' => 100,
            				'max' => 800,
            			],
            		],
            		'selectors'   => [
            			'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-desc' => 'max-width: {{SIZE}}px;',
            		],
            	]
            );
    
            $this->add_group_control(
            	Group_Control_Typography::get_type(),
            	[
            		'name'      => 'text_typography',
            		'label'     => esc_html__('Typography', 'bdthemes-prime-slider'),
            		'selector'  => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-desc',
            	]
            );
    
            $this->end_controls_section();
    
            $this->start_controls_section(
                'section_style_meta',
                [
                    'label'      => esc_html__( 'Meta', 'bdthemes-prime-slider' ),
                    'tab'        => Controls_Manager::TAB_STYLE,
                    'conditions' => [
                        'relation' => 'or',
                        'terms'    => [
                            [
                                'name'  => 'show_author',
                                'value' => 'yes'
                            ],
                            [
                                'name'  => 'show_date',
                                'value' => 'yes'
                            ]
                        ]
                    ],
                ]
            );
            
            $this->add_control(
                'meta_color',
                [
                    'label'     => esc_html__( 'Color', 'bdthemes-prime-slider' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-meta, {{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-meta 
                        .bdt-author-name-wrap .bdt-author-name' => 'color: {{VALUE}};',
                    ],
                ]
            );
            
            $this->add_control(
                'meta_hover_color',
                [
                    'label'     => esc_html__( 'Hover Color', 'bdthemes-prime-slider' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-meta .bdt-author-name-wrap .bdt-author-name:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'meta_spacing',
                [
                    'label'     => esc_html__( 'Spacing', 'bdthemes-prime-slider' ),
                    'type'      => Controls_Manager::SLIDER,
                    'range'     => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'meta_space_between',
                [
                    'label'     => esc_html__( 'Space Between', 'bdthemes-prime-slider' ),
                    'type'      => Controls_Manager::SLIDER,
                    'range'     => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-meta .bdt-ps-separator' => 'margin: 0 {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'meta_typography',
                    'label'    => esc_html__( 'Typography', 'bdthemes-prime-slider' ),
                    'selector' => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-meta',
                ]
            );
            
            $this->end_controls_section();
    
            $this->start_controls_section(
                'section_style_button',
                [
                    'label'     => esc_html__('Button', 'bdthemes-prime-slider'),
                    'tab'       => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_button' => 'yes'
                    ],
                ]
            );
    
            $this->start_controls_tabs('tabs_button_style');
    
            $this->start_controls_tab(
                'tab_button_normal',
                [
                    'label' => esc_html__('Normal', 'bdthemes-prime-slider'),
                ]
            );
    
            $this->add_control(
                'button_color',
                [
                    'label'     => esc_html__('Color', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-btn-wrap a' => 'color: {{VALUE}};',
                    ],
                ]
            );
    
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'      => 'button_background',
                    'selector'  => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-btn-wrap a',
                ]
            );
    
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'        => 'button_border',
                    'selector'    => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-btn-wrap a',
                ]
            );
    
            $this->add_responsive_control(
                'button_border_radius',
                [
                    'label'      => esc_html__('Border Radius', 'bdthemes-prime-slider'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-btn-wrap a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
    
            $this->add_responsive_control(
                'button_padding',
                [
                    'label'      => esc_html__('Padding', 'bdthemes-prime-slider'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-btn-wrap a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
    
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'     => 'button_shadow',
                    'selector' => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-btn-wrap a',
                ]
            );
    
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'button_typography',
                    'label'    => esc_html__('Typography', 'bdthemes-prime-slider'),
                    'selector' => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-btn-wrap a',
                ]
            );
    
            $this->end_controls_tab();
    
            $this->start_controls_tab(
                'tab_button_hover',
                [
                    'label' => esc_html__('Hover', 'bdthemes-prime-slider'),
                    'condition' => [
                        'show_button' => 'yes'
                    ]
                ]
            );
    
            $this->add_control(
                'button_hover_color',
                [
                    'label'     => esc_html__('Color', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-btn-wrap a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );
    
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'      => 'button_hover_background',
                    'selector'  => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-btn-wrap a:hover',
                ]
            );
    
            $this->add_control(
                'button_hover_border_color',
                [
                    'label'     => esc_html__('Border Color', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::COLOR,
                    'condition' => [
                        'button_border_border!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-btn-wrap a:hover' => 'border-color: {{VALUE}};',
                    ],
                ]
            );
    
            $this->end_controls_tab();
    
            $this->end_controls_tabs();
    
            $this->end_controls_section();

            $this->start_controls_section(
				'section_style_category',
				[
					'label'     => esc_html__( 'Category', 'bdthemes-prime-slider' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => [
						'show_category' => 'yes'
					],
				]
			);
			
			$this->add_responsive_control(
				'category_bottom_spacing',
				[
					'label'     => esc_html__( 'Spacing', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'min'  => 0,
							'max'  => 50,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-category' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->start_controls_tabs( 'tabs_category_style' );
			
			$this->start_controls_tab(
				'tab_category_normal',
				[
					'label' => esc_html__( 'Normal', 'bdthemes-prime-slider' ),
				]
			);
			
			$this->add_control(
				'category_color',
				[
					'label'     => esc_html__( 'Color', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-category a' => 'color: {{VALUE}};',
					],
				]
			);
			
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'category_background',
					'selector' => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-category a',
				]
			);
			
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'category_border',
					'selector' => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-category a',
				]
			);
			
			$this->add_responsive_control(
				'category_border_radius',
				[
					'label'      => esc_html__( 'Border Radius', 'bdthemes-prime-slider' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-category a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'category_padding',
				[
					'label'      => esc_html__( 'Padding', 'bdthemes-prime-slider' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'category_space_between',
				[
					'label'     => esc_html__( 'Space Between', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'min'  => 0,
							'max'  => 50,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-category a+a' => 'margin-left: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'category_shadow',
					'selector' => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-category a',
				]
			);
			
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'category_typography',
					'label'    => esc_html__( 'Typography', 'bdthemes-prime-slider' ),
					'selector' => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-category a',
				]
			);
			
			$this->end_controls_tab();
			
			$this->start_controls_tab(
				'tab_category_hover',
				[
					'label'     => esc_html__( 'Hover', 'bdthemes-prime-slider' ),
				]
			);
			
			$this->add_control(
				'category_hover_color',
				[
					'label'     => esc_html__( 'Color', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-category a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'category_hover_background',
					'selector' => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-category a:hover',
				]
			);
			
			$this->add_control(
				'category_hover_border_color',
				[
					'label'     => esc_html__( 'Border Color', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => [
						'category_border_border!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-category a:hover' => 'border-color: {{VALUE}};',
					],
				]
			);
			
			$this->end_controls_tab();
			
			$this->end_controls_tabs();
			
			$this->end_controls_section();

            $this->start_controls_section(
                'section_style_social_share',
                [
                    'label'     => esc_html__('Social Share', 'bdthemes-prime-slider'),
                    'tab'       => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_social_share' => 'yes'
                    ],
                ]
            );
    
            $this->start_controls_tabs('tabs_social_share_style');
    
            $this->start_controls_tab(
                'tab_social_share_normal',
                [
                    'label' => esc_html__('Normal', 'bdthemes-prime-slider'),
                ]
            );
    
            $this->add_control(
                'social_share_color',
                [
                    'label'     => esc_html__('Color', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-social-share .bdt-ps-btn' => 'color: {{VALUE}};',
                    ],
                ]
            );
    
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'      => 'social_share_background',
                    'selector'  => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-social-share .bdt-ps-btn',
                ]
            );
    
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'        => 'social_share_border',
                    'selector'    => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-social-share .bdt-ps-btn',
                ]
            );
    
            $this->add_responsive_control(
                'social_share_border_radius',
                [
                    'label'      => esc_html__('Border Radius', 'bdthemes-prime-slider'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-social-share .bdt-ps-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
    
            $this->add_responsive_control(
                'social_share_padding',
                [
                    'label'      => esc_html__('Padding', 'bdthemes-prime-slider'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-social-share .bdt-ps-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
    
            $this->add_responsive_control(
                'social_share_margin',
                [
                    'label'      => esc_html__('Margin', 'bdthemes-prime-slider'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-social-share .bdt-ps-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
    
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'     => 'social_share_shadow',
                    'selector' => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-social-share .bdt-ps-btn',
                ]
            );
    
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'social_share_typography',
                    'label'    => esc_html__('Typography', 'bdthemes-prime-slider'),
                    'selector' => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-social-share .bdt-ps-btn',
                ]
            );
    
            $this->end_controls_tab();
    
            $this->start_controls_tab(
                'tab_social_share_hover',
                [
                    'label' => esc_html__('Hover', 'bdthemes-prime-slider'),
                    'condition' => [
                        'show_social_share' => 'yes'
                    ]
                ]
            );
    
            $this->add_control(
                'social_share_hover_color',
                [
                    'label'     => esc_html__('Color', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-social-share .bdt-ps-btn:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );
    
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'      => 'social_share_hover_background',
                    'selector'  => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-social-share .bdt-ps-btn:hover',
                ]
            );
    
            $this->add_control(
                'social_share_hover_border_color',
                [
                    'label'     => esc_html__('Border Color', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::COLOR,
                    'condition' => [
                        'social_share_border_border!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .bdt-prime-slider-knily .bdt-btn-wrap a:hover' => 'border-color: {{VALUE}};',
                    ],
                ]
            );
    
            $this->end_controls_tab();
    
            $this->end_controls_tabs();
    
            $this->end_controls_section();

            //Thumbs
            $this->start_controls_section(
                'section_style_thumbs',
                [
                    'label'     => esc_html__('Thumbs Slider', 'bdthemes-prime-slider'),
                    'tab'       => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'hide_thumbs' => ''
                    ]
                ]
            );

            $this->add_responsive_control(
				'thumbs_height',
                [
                    'label' => esc_html__('Item Height', 'bdthemes-prime-slider'),
                    'type'  => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'vh' ],
                    'range' => [
                        'px' => [
                            'min' => 200,
                            'max' => 1080,
                        ],
                        'vh' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                    ],
                    'selectors'   => [
                        '{{WRAPPER}} .bdt-knily-thumbs .bdt-item' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
			);

            $this->add_responsive_control(
				'thumbs_width',
                [
                    'label' => esc_html__('Width(Wrapper)', 'bdthemes-prime-slider'),
                    'type'  => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'vh' ],
                    'range' => [
                        'px' => [
                            'min' => 200,
                            'max' => 1080,
                        ],
                        'vh' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                    ],
                    'selectors'   => [
                        '{{WRAPPER}} .bdt-knily-thumbs' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
			);

            $this->start_controls_tabs('tabs_thumbs_style');
    
            $this->start_controls_tab(
                'tab_thumbs_normal',
                [
                    'label' => esc_html__('Normal', 'bdthemes-prime-slider'),
                ]
            );

            $this->add_control(
                'item_thumb_overlay_color',
                [
                    'label'     => esc_html__('Overlay Color', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bdt-knily-thumbs .bdt-item .bdt-thumbs-img-wrap:before' => 'background: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'thumbs_border',
					'selector' => '{{WRAPPER}} .bdt-knily-thumbs .bdt-item',
				]
			);
			
			$this->add_responsive_control(
				'thumbs_border_radius',
				[
					'label'      => esc_html__( 'Border Radius', 'bdthemes-prime-slider' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .bdt-knily-thumbs .bdt-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'thumbs_content_padding',
				[
					'label'      => esc_html__( 'Padding', 'bdthemes-prime-slider' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .bdt-knily-thumbs .bdt-item .bdt-knily-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
            
            $this->add_control(
                'thumb_counter_heading',
                [
                    'label'     => esc_html__('C O U N T E R', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_responsive_control(
                'counter_horizontal_spacing',
                [
                    'label'      => esc_html__('Horizontal Spacing', 'bdthemes-prime-slider'),
                    'type'       => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 100,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .bdt-knily-thumbs .bdt-item .bdt-knily-content .bdt-knily-counter' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'counter_vertical_spacing',
                [
                    'label'      => esc_html__('Vertical Spacing', 'bdthemes-prime-slider'),
                    'type'       => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 100,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .bdt-knily-thumbs .bdt-item .bdt-knily-content .bdt-knily-counter' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->add_control(
                'thumb_counter_color',
                [
                    'label'     => esc_html__('Counter Color', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bdt-knily-thumbs .bdt-item .bdt-knily-content .bdt-knily-counter' => 'color: {{VALUE}};',
                    ],
                ]
            );
    
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'thumb_counter_typography',
                    'label'     => esc_html__('Typography', 'bdthemes-prime-slider'),
                    'selector'  => '{{WRAPPER}} .bdt-knily-thumbs .bdt-item .bdt-knily-content .bdt-knily-counter',
                ]
            );

            $this->add_control(
                'thumb_title_heading',
                [
                    'label'     => esc_html__('T I T L E', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );
            
            $this->add_control(
                'thumb_title_color',
                [
                    'label'     => esc_html__('Color', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bdt-knily-thumbs .bdt-item .bdt-knily-content .bdt-knily-title a' => 'color: {{VALUE}};',
                    ],
                ]
            );
    
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'thumb_title_typography',
                    'label'     => esc_html__('Typography', 'bdthemes-prime-slider'),
                    'selector'  => '{{WRAPPER}} .bdt-knily-thumbs .bdt-item .bdt-knily-content .bdt-knily-title',
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'tab_thumbs_hover',
                [
                    'label' => esc_html__('Hover', 'bdthemes-prime-slider'),
                ]
            );

			$this->add_control(
				'thumbs_hover_border_color',
				[
					'label'     => esc_html__( 'Border Color', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => [
						'thumbs_border_border!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .bdt-knily-thumbs .bdt-item:hover' => 'border-color: {{VALUE}};',
					],
				]
			);

            $this->add_control(
                'thumb_counter_hover_color',
                [
                    'label'     => esc_html__('Counter Color', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bdt-knily-thumbs .bdt-item:hover .bdt-knily-content .bdt-knily-counter' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'thumb_title_hover_color',
                [
                    'label'     => esc_html__('Title Color', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bdt-knily-thumbs .bdt-item:hover .bdt-knily-content .bdt-knily-title a' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before'
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();
    
            $this->end_controls_section();
    
            //Navigation Css
            $this->start_controls_section(
                'section_style_pagination',
                [
                    'label'     => __( 'Navigation', 'bdthemes-prime-slider' ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_navigation_dots' => 'yes'
                    ]
                ]
            );

            $this->add_control(
                'arrows',
                [
                    'label'     => esc_html__('A R R O W', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
				'arrows_color',
				[
					'label'     => __( 'Color', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-navigation .bdt-navigation-next, {{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-navigation .bdt-navigation-prev' => 'color: {{VALUE}}',
					],
				]
			);

            $this->add_control(
				'arrows_hover_color',
				[
					'label'     => __( 'Hover Color', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-navigation .bdt-navigation-next:hover, {{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-navigation .bdt-navigation-prev:hover' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_responsive_control(
				'arrows_space_between',
				[
					'label'     => __( 'Space Between', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-navigation' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
					],
				]
			);

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'arrow_typography',
                    'label'     => esc_html__('Typography', 'bdthemes-prime-slider'),
                    'selector'  => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-navigation .bdt-navigation-next, {{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-navigation .bdt-navigation-prev',
                ]
            );

            $this->add_control(
                'dots',
                [
                    'label'     => esc_html__('D 0 T S', 'bdthemes-prime-slider'),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

			$this->add_responsive_control(
				'dots_nny_position',
				[
					'label'          => __( 'Dots Vertical Offset', 'bdthemes-prime-slider' ),
					'type'           => Controls_Manager::SLIDER,
					'range'          => [
						'px' => [
							'min' => -200,
							'max' => 200,
						],
					],
					'selectors'      => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-pagination .swiper-pagination'=> 'bottom: {{SIZE}}px;'
					],
				]
			);

            $this->start_controls_tabs( 'tabs_navigation_dots_style' );
			
			$this->start_controls_tab(
				'tabs_nav_dots_normal',
				[
					'label'     => __( 'Normal', 'bdthemes-prime-slider' ),
				]
			);
			
			$this->add_control(
				'dots_color',
				[
					'label'     => __( 'Color', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-pagination .swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_responsive_control(
				'dots_space_between',
				[
					'label'     => __( 'Space Between', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-pagination .swiper-pagination .swiper-pagination-bullet' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'dots_size',
				[
					'label'     => __( 'Size', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'min' => 5,
							'max' => 20,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-pagination .swiper-pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					],
                    'condition' => [
						'advanced_dots_size' => ''
					],
				]
			);
			
			$this->add_control(
				'advanced_dots_size',
				[
					'label'     => __( 'Advanced Size', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::SWITCHER,
				]
			);
			
			$this->add_responsive_control(
				'advanced_dots_width',
				[
					'label'     => __( 'Width(px)', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'min' => 1,
							'max' => 50,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-pagination .swiper-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'advanced_dots_size' => 'yes'
					],
				]
			);
			
			$this->add_responsive_control(
				'advanced_dots_height',
				[
					'label'     => __( 'Height(px)', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'min' => 1,
							'max' => 50,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-pagination .swiper-pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'advanced_dots_size' => 'yes'
					],
				]
			);

			$this->add_responsive_control(
				'advanced_dots_radius',
				[
					'label'      => esc_html__('Border Radius', 'bdthemes-prime-slider'),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-pagination .swiper-pagination .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'advanced_dots_size' => 'yes'
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'dots_box_shadow',
					'selector' => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-pagination .swiper-pagination .swiper-pagination-bullet',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tabs_nav_dots_active',
				[
					'label'     => __( 'Active', 'bdthemes-prime-slider' ),
				]
			);

			$this->add_control(
				'active_dot_color',
				[
					'label'     => __( 'Color', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_responsive_control(
				'active_dots_size',
				[
					'label'     => __( 'Size', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'min' => 5,
							'max' => 20,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-pagination .swiper-pagination .swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}' => '--ps-swiper-dots-active-height: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'advanced_dots_size' => ''
					],
				]
			);
			
			$this->add_responsive_control(
				'active_advanced_dots_width',
				[
					'label'     => __( 'Width(px)', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'min' => 1,
							'max' => 50,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-pagination .swiper-pagination .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'advanced_dots_size' => 'yes'
					],
				]
			);
			
			$this->add_responsive_control(
				'active_advanced_dots_height',
				[
					'label'     => __( 'Height(px)', 'bdthemes-prime-slider' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'min' => 1,
							'max' => 50,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-pagination .swiper-pagination .swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}' => '--ps-swiper-dots-active-height: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'advanced_dots_size' => 'yes'
					],
				]
			);

			$this->add_responsive_control(
				'active_advanced_dots_radius',
				[
					'label'      => esc_html__('Border Radius', 'bdthemes-prime-slider'),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-pagination .swiper-pagination .swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'advanced_dots_size' => 'yes'
					],
				]
			);

			$this->add_responsive_control(
				'active_advanced_dots_align',
				[
					'label'   => __( 'Alignment', 'bdthemes-prime-slider' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => [
						'flex-start' => [
							'title' => __( 'Top', 'bdthemes-prime-slider' ),
							'icon'  => 'eicon-v-align-top',
						],
						'center' => [
							'title' => __( 'Center', 'bdthemes-prime-slider' ),
							'icon'  => 'eicon-v-align-middle',
						],
						'flex-end' => [
							'title' => __( 'Bottom', 'bdthemes-prime-slider' ),
							'icon'  => 'eicon-v-align-bottom',
						],
					],
					'selectors' => [
						'{{WRAPPER}}' => '--ps-swiper-dots-align: {{VALUE}};',
					],
					'condition' => [
						'advanced_dots_size' => 'yes'
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'dots_active_box_shadow',
					'selector' => '{{WRAPPER}} .bdt-prime-slider-knily .bdt-knily-pagination .swiper-pagination .swiper-pagination-bullet-active',
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

            $this->end_controls_section();
  
        }
        
        public function query_posts() {
		    $settings = $this->get_settings();
		
            $args = [];
    
            if ( $settings['posts_limit'] ) {
                $args['posts_per_page'] = $settings['posts_limit'];
                $args['paged']          = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
            }
        
            $default = $this->getGroupControlQueryArgs();
            $args = array_merge( $default, $args );
        
            $query = new WP_Query( $args );
        
            return $query;
	    }
        
        public function render_image($image_id, $size) {
            $placeholder_image_src = Utils::get_placeholder_image_src();

            $image_src = wp_get_attachment_image_src($image_id, $size);

            if (!$image_src) {
                $image_src = $placeholder_image_src;
            } else {
                $image_src = $image_src[0];
            }

            ?>
            <img class="bdt-knily-img swiper-lazy" src="<?php echo esc_url($image_src); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
            <?php
        }

        public function render_title() {
            $settings = $this->get_settings_for_display();

            if (!$this->get_settings('show_title')) {
                return;
            }

            $this->add_render_attribute('slider-title', 'class', 'bdt-knily-title', true);
            $titleClass = $this->get_render_attribute_string('slider-title');
            echo
            '<' . esc_html($settings['title_tags']) . ' ' . $titleClass . ' >
                    <a href="' . esc_url(get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">
                        ' . esc_html(get_the_title())  . '
                    </a>
                </' . esc_html($settings['title_tags']) . '>';
        }
        
        public function render_excerpt($excerpt_length) {

            if (!$this->get_settings('show_excerpt')) {
                return;
            }
            $strip_shortcode = $this->get_settings_for_display('strip_shortcode');
            ?>
            <div class="bdt-knily-desc" data-swiper-parallax-y ="-80" data-swiper-parallax-duration="600">
                <?php
                if (has_excerpt()) {
                    the_excerpt();
                } else {
                    echo prime_slider_custom_excerpt($excerpt_length, $strip_shortcode);
                }
                ?>
            </div>
            <?php
        }
        
        public function render_category() {
            if ( !$this->get_settings( 'show_category' ) ) {
                return;
            }

            ?>
            <div class="bdt-knily-category" data-swiper-parallax-y ="-120" data-swiper-parallax-duration="400"> 
                <?php echo get_the_category_list( ' ' ); ?>
		    </div>
            <?php
        }

        public function render_date() {
            $settings = $this->get_settings_for_display();
            
            
            if ( ! $this->get_settings( 'show_date' ) ) {
                return;
            }
            
            ?>
            <div class="bdt-flex bdt-flex-middle">
                <div class="bdt-knily-date">
                    <?php if ( $settings['human_diff_time'] == 'yes' ) {
                        echo prime_slider_post_time_diff( ( $settings['human_diff_time_short'] == 'yes' ) ? 'short' : '' );
                    } else {
                        echo get_the_date();
                    } ?>
                </div>
                <?php if ($settings['show_time']) : ?>
                <div class="bdt-post-time">
                    <i class="eicon-clock-o" aria-hidden="true"></i>
                    <?php echo get_the_time(); ?>
                </div>
                <?php endif; ?>
            </div>
            
            <?php
        }
    
        public function render_author() {
                
            if ( ! $this->get_settings( 'show_author' ) ) {
                return;
            }
            ?>
            <div class="bdt-author-name-wrap">
                <span class="bdt-by"><?php echo esc_html__( 'by', 'bdthemes-prime-slider' ) ?></span>
                <a class="bdt-author-name" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>">
                    <?php echo get_the_author() ?>
                </a>
            </div>
            <?php
        }
    
        public function render_button() {
            $settings   = $this->get_settings_for_display();
            if ( ! $this->get_settings( 'show_button' ) ) {
                return;
            }
            ?>
            <div class="bdt-btn-wrap" data-swiper-parallax-y ="-50" data-swiper-parallax-duration="700">
                <a href="<?php echo esc_url(get_permalink()); ?>">
                    <span><?php echo esc_html( $settings['button_text'] ); ?></span>
                    <i class="eicon-arrow-right"></i>
                </a>
            </div>
            <?php
        }
        
        protected function render_header() {
            $settings   = $this->get_settings_for_display();
            $id         = 'bdt-prime-slider-' . $this->get_id();
    
            $this->add_render_attribute( 'prime-slider-knily', 'id', $id );
            $this->add_render_attribute( 'prime-slider-knily', 'class', [ 'bdt-prime-slider-knily', 'elementor-swiper' ] );

            $this->add_render_attribute(
                [
                    'prime-slider-knily' => [
                        'data-settings' => [
                            wp_json_encode(array_filter([
                                "autoplay"       => ("yes" == $settings["autoplay"]) ? ["delay" => $settings["autoplay_speed"]] : false,
                                "loop"           => true,
                                "speed"          => $settings["speed"]["size"],
                                "effect"         => 'fade',
                                "fadeEffect"     => ['crossFade' => true],
                                "parallax"       => true,
                                "grabCursor"     => ($settings["grab_cursor"] === "yes") ? true : false,
                                "pauseOnHover"   => ("yes" == $settings["pauseonhover"]) ? true : false,
                                "slidesPerView"  => 1,
                                "loopedSlides"   => 4,
                                "observer"       => ($settings["observer"]) ? true : false,
                                "observeParents" => ($settings["observer"]) ? true : false,

                                "navigation" => [
                                    "nextEl" => "#" . $id . " .bdt-navigation-next",
                                    "prevEl" => "#" . $id . " .bdt-navigation-prev",
                                ],
                                "pagination" => [
                                    "el"             => "#" . $id . " .swiper-pagination",
                                    "clickable"      => "true",
                                ],
                                "lazy" => [
                                    "loadPrevNext"  => "true",
                                ],
                            ]))
                        ]
                    ]
                ]
            );
    
            ?>
            <div <?php $this->print_render_attribute_string( 'prime-slider-knily' ); ?>>
                <div class="swiper-container">
                    <div class="swiper-wrapper">
            <?php
        }

        public function render_footer() {
            $settings = $this->get_settings_for_display();
            ?>
                    </div>

                    <div class="bdt-knily-navigation">
                        <div class="bdt-navigation-next">
                            <span><?php echo esc_html__('next', 'bdthemes-prime-slider') ?></span>
                            <i class="eicon-chevron-right"></i>
                        </div>
                        <div class="bdt-navigation-prev">
                            <span><?php echo esc_html__('prev', 'bdthemes-prime-slider') ?></span>
                            <i class="eicon-chevron-left"></i>
                        </div>
                    </div>
                    <?php if($settings['show_navigation_dots']) : ?>
                        <div class="bdt-knily-pagination">
                            <div class="swiper-pagination"></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
		}

        public function render_thumbnav($post_id, $image_size, $count ) {
            $settings = $this->get_settings_for_display();

            $this->add_render_attribute('thumb-item', 'class', 'bdt-item swiper-slide', true);

            ?>
            <div <?php echo $this->get_render_attribute_string('thumb-item'); ?>>
                <div class="bdt-thumbs-img-wrap">
                    <?php $this->render_image(get_post_thumbnail_id($post_id), $image_size); ?>
                </div>
                <div class="bdt-knily-content">
                    <span class="bdt-knily-counter"><?php echo $count; ?></span>
                    <h3 class="bdt-knily-title">
                        <a href="javascript:void(0);"><?php echo esc_html(get_the_title()); ?></a>
                    </h3>
                </div>
            </div>
            <?php
        }

        public function render_social_share() {
            $settings  = $this->get_active_settings();
    
            if ( empty( $settings['share_buttons'] ) ) {
                return;
            }
    
            ?>
            <div class="bdt-social-share">
                <?php
                foreach ( $settings['share_buttons'] as $button ) {
                    $social_name = $button['button'];
    
                    $this->add_render_attribute(
                        [
                            'social-attrs' => [
                                'class' => [
                                    'bdt-ps-btn',
                                    'bdt-ps-' . $social_name
                                ],
                                'data-social' => $social_name,
                            ]
                        ], '', '', true
                    );
    
                    ?>
                    <div <?php echo $this->get_render_attribute_string( 'social-attrs' ); ?>>
                        <?php echo $button['text'] ? esc_html($button['text']) : Module::get_social_media( $social_name )['title']; ?>
                    </div>
                    <?php
                }
                ?>
            </div>
    
            
            <?php
        }

        public function render_slider_item($post_id, $image_size, $excerpt_length) {
            $settings = $this->get_settings_for_display();

            $this->add_render_attribute('slider-item', 'class', 'bdt-item swiper-slide ', true);

            ?>
            <div <?php echo $this->get_render_attribute_string('slider-item'); ?>>
                <div class="bdt-img-wrap">
                    <?php $this->render_image(get_post_thumbnail_id($post_id), $image_size); ?>
                </div>
                <div class="bdt-content">

                    <?php $this->render_category(); ?>

                    <?php if ($settings['show_title']) : ?>
                    <div data-swiper-parallax-y ="-100" data-swiper-parallax-duration="500">
                        <?php $this->render_title(); ?>
                    </div>
                    <?php endif; ?>

                    <?php $this->render_excerpt( $excerpt_length ); ?>

                    <?php if ($settings['show_author'] or $settings['show_date']) : ?>
                    <div class="upk-flex" data-swiper-parallax-y ="-65" data-swiper-parallax-duration="650">
                        <div class="bdt-knily-meta">
                            <?php $this->render_author(); ?>
                            <span class="bdt-ps-separator"><?php echo $settings['meta_separator']; ?></span>
                            <?php $this->render_date(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php $this->render_button(); ?>
                </div>
                
                <?php $this->render_social_share(); ?>
            </div>
            
            <?php
        }

        public function render() {
            $settings = $this->get_settings_for_display();

            $wp_query = $this->query_posts();
            if ( !$wp_query->found_posts ) {
                return;
            }
            
            ?>
            <div class="bdt-prime-slider">
                <?php

                $this->render_header();

                while ($wp_query->have_posts()) {
                    $wp_query->the_post();
                    $thumbnail_size = $settings['primary_thumbnail_size'];

                    $this->render_slider_item(get_the_ID(), $thumbnail_size, $settings['excerpt_length']);
                }

                $this->render_footer();

                ?>
                <div thumbsSlider="" class="swiper-container bdt-knily-thumbs">
                    <div class="swiper-wrapper">
                        <?php
                        $i=1;
                        while ($wp_query->have_posts()) {
                            $wp_query->the_post();
                            $thumbnail_size = $settings['primary_thumbnail_size'];

                            $this->render_thumbnav(get_the_ID(), $thumbnail_size, $i);
                            $i++;
                        }
                        ?>
                    </div>
                </div>

            </div>
            <?php
            wp_reset_postdata();
		}
    }