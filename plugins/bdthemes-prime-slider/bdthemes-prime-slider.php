<?php

/**
 * Plugin Name: Prime Slider (Premium)
 * Plugin URI: https://primeslider.pro/
 * Description: Prime Slider is a packed of elementor widget that gives you some awesome header and slider combination for your website.
 * Version: 2.2.0
 * Author: BdThemes
 * Author URI: https://bdthemes.com/
 * Text Domain: bdthemes-prime-slider
 * Domain Path: /languages
 * License: GPL3
 * Elementor requires at least: 3.0.0
 * Elementor tested up to: 3.4.8
 *
 * @fs_premium_only /modules/custom/, /modules/woostand/, /modules/wooexpand/, /modules/event-calendar/, /modules/fluent/, /modules/flexure/, /modules/monster/, /modules/marble/, /modules/knily/, /modules/astoria/, /modules/crossroad/, /assets/css/ps-knily.css, /assets/css/ps-marble.css, /assets/css/ps-astoria.css, /assets/css/ps-crossroad.css, /assets/vendor/js/gsap.js, /assets/vendor/js/gsap.min.js, /assets/vendor/js/SplitText.js, /assets/vendor/js/SplitText.min.js, /assets/vendor/js/charming.js, /assets/vendor/js/charming.min.js, /assets/vendor/js/anime.js, /assets/vendor/js/anime.min.js, /assets/js/widgets/ps-general.js, /assets/js/widgets/ps-general.min.js, /assets/js/widgets/ps-blog.js, /assets/js/widgets/ps-blog.min.js, /assets/js/widgets/ps-isolate.js, /assets/js/widgets/ps-isolate.min.js, /assets/js/widgets/ps-knily.js, /assets/js/widgets/ps-knily.min.js, /assets/js/widgets/ps-marble.js, /assets/js/widgets/ps-marble.min.js, /assets/js/widgets/ps-astoria.js, /assets/js/widgets/ps-astoria.min.js, /assets/js/widgets/ps-crossroad.js, /assets/js/widgets/ps-crossroad.min.js
 */

if ( function_exists( 'bdt_ps' ) ) {
    bdt_ps()->set_basename( true, __FILE__ );
} else {
    
    if ( !function_exists( 'bdt_ps' ) ) {
        // Create a helper function for easy SDK access.
        function bdt_ps()
        {
            global  $bdt_ps ;
            
            if ( !isset( $bdt_ps ) ) {
				
				class bdtpsFsNull {
					public function is__premium_only() {
						return true;
					}
					public function is_plan() {
						return 'agency';
					}
				}
				$bdt_ps = new bdtpsFsNull();
            }
            
            return $bdt_ps;
        }
        
        // Init Freemius.
        bdt_ps();
        // Signal that SDK was initiated.
        do_action( 'bdt_ps_loaded' );
    }
    
    // Some pre define value for easy use
    define( 'BDTPS_VER', '2.2.0' );
    define( 'BDTPS__FILE__', __FILE__ );
    define( 'BDTPS_PNAME', basename( dirname( BDTPS__FILE__ ) ) );
    define( 'BDTPS_PBNAME', plugin_basename( BDTPS__FILE__ ) );
    define( 'BDTPS_PATH', plugin_dir_path( BDTPS__FILE__ ) );
    define( 'BDTPS_MODULES_PATH', BDTPS_PATH . 'modules/' );
    define( 'BDTPS_INC_PATH', BDTPS_PATH . 'includes/' );
    define( 'BDTPS_URL', plugins_url( '/', BDTPS__FILE__ ) );
    define( 'BDTPS_ASSETS_URL', BDTPS_URL . 'assets/' );
    define( 'BDTPS_MODULES_URL', BDTPS_URL . 'modules/' );
    // Helper function here
    include dirname( __FILE__ ) . '/includes/helper.php';
    include dirname( __FILE__ ) . '/includes/utils.php';
    /**
     * Plugin load here correctly
     * Also loaded the language file from here
     */
    function prime_slider_load_plugin()
    {
        load_plugin_textdomain( 'bdthemes-prime-slider', false, basename( dirname( __FILE__ ) ) . '/languages' );
        
        if ( !did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', 'prime_slider_fail_load' );
            return;
        }
        
        // Filters for developer
        require BDTPS_PATH . 'includes/prime-slider-filters.php';
        // Prime Slider widget and assets loader
        require BDTPS_PATH . 'loader.php';
        // Notice class
        require BDTPS_PATH . 'includes/admin-notice.php';
    }
    
    add_action( 'plugins_loaded', 'prime_slider_load_plugin' );
    /**
     * Check Elementor installed and activated correctly
     */
    function prime_slider_fail_load()
    {
        $screen = get_current_screen();
        if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
            return;
        }
        $plugin = 'elementor/elementor.php';
        
        if ( _is_elementor_installed() ) {
            if ( !current_user_can( 'activate_plugins' ) ) {
                return;
            }
            $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
            $admin_message = '<p>' . esc_html__( 'Ops! Prime Slider not working because you need to activate the Elementor plugin first.', 'bdthemes-prime-slider' ) . '</p>';
            $admin_message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Elementor Now', 'bdthemes-prime-slider' ) ) . '</p>';
        } else {
            if ( !current_user_can( 'install_plugins' ) ) {
                return;
            }
            $install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
            $admin_message = '<p>' . esc_html__( 'Ops! Prime Slider not working because you need to install the Elementor plugin', 'bdthemes-prime-slider' ) . '</p>';
            $admin_message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Elementor Now', 'bdthemes-prime-slider' ) ) . '</p>';
        }
        
        echo  '<div class="error">' . $admin_message . '</div>' ;
    }
    
    /**
     * Check the elementor installed or not
     */
    if ( !function_exists( '_is_elementor_installed' ) ) {
        function _is_elementor_installed()
        {
            $file_path = 'elementor/elementor.php';
            $installed_plugins = get_plugins();
            return isset( $installed_plugins[$file_path] );
        }
    
    }
}
