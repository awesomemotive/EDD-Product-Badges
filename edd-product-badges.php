<?php
/**
 * Plugin Name:     Easy Digital Downloads - Product Badges
 * Plugin URI:      https://easydigitaldownloads.com/extensions/product-badges
 * Description:     Allows site operators to add 'badges' to products on their EDD-powered website
 * Version:         1.0.0
 * Author:          Daniel J Griffiths
 * Author URI:      http://section214.com
 * Text Domain:     edd-product-badges
 *
 * @package         EDD\ProductBadges
 * @author          Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright       Copyright (c) 2014, Daniel J Griffiths
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'EDD_Product_Badges' ) ) {


    /**
     * Main EDD_Product_Badges class
     *
     * @since       1.0.0
     */
    class EDD_Product_Badges {

        /**
         * @var         EDD_Product_Badges $instance The one true EDD_Product_Badges
         * @since       1.0.0
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      self::$instance The one true EDD_Product_Badges
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new EDD_Product_Badges();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'EDD_PRODUCT_BADGES_VER', '1.0.0' );

            // Plugin path
            define( 'EDD_PRODUCT_BADGES_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'EDD_PRODUCT_BADGES_URL', plugin_dir_url( __FILE__ ) );
        }


        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            require_once EDD_PRODUCT_BADGES_DIR . '/includes/scripts.php';
            require_once EDD_PRODUCT_BADGES_DIR . '/includes/metabox.php';
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function hooks() {
            // Edit plugin metalinks
            add_filter( 'plugin_row_meta', array( $this, 'plugin_metalinks' ), null, 2 );

            // Handle licensing
            if( class_exists( 'EDD_License' ) ) {
                $license = new EDD_License( __FILE__, 'Product Badges', EDD_PRODUCT_BADGES_VER, 'Daniel J Griffiths' );
            }

            // Enqueue JS
            add_action( 'admin_enqueue_scripts', 'edd_product_badges_admin_scripts', 100 );

            // Add meta box
            add_action( 'add_meta_boxes', 'edd_product_badges_add_meta_box' );

            // Save meta box fields
            add_action( 'save_post', 'edd_product_badges_meta_box_save' );

            // Display product badges
            add_action( 'edd_download_before', array( $this, 'show_badge' ) );
        }


        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
            $lang_dir = apply_filters( 'EDD_Product_Badges_language_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale     = apply_filters( 'plugin_locale', get_locale(), '' );
            $mofile     = sprintf( '%1$s-%2$s.mo', 'edd-product-badges', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/edd-product-badges/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/edd-product-badges/ folder
                load_textdomain( 'edd-product-badges', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/edd-product-badges/languages/ folder
                load_textdomain( 'edd-product-badges', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'edd-product-badges', false, $lang_dir );
            }
        }


        /**
         * Modify plugin metalinks
         *
         * @access      public
         * @since       1.0.0
         * @param       array $links The current links array
         * @param       string $file A specific plugin table entry
         * @return      array $links The modified links array
         */
        public function plugin_metalinks( $links, $file ) {
            if( $file == plugin_basename( __FILE__ ) ) {
                $help_link = array(
                    '<a href="https://easydigitaldownloads.com/support/forum/add-on-plugins/product-badges/" target="_blank">' . __( 'Support Forum', 'edd-product-badges' ) . '</a>'
                );

                $docs_link = array(
                    '<a href="http://section214.com/docs/category/edd-product-badges/" target="_blank">' . __( 'Docs', 'edd-product-badges' ) . '</a>'
                );

                $links = array_merge( $links, $help_link, $docs_link );
            }

            return $links;
        }


        /**
         * Display product badges
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function show_badge() {
            $post_id = get_the_ID();

            // Does this post have a badge?
            $has_badge = get_post_meta( $post_id, '_edd_product_badge_enable', true ) ? true : false;

            if( $has_badge ) {
                
                $type       = get_post_meta( $post_id, '_edd_product_badge_type', true );
                $image      = get_post_meta( $post_id, '_edd_product_badge_image', true );
                $text       = get_post_meta( $post_id, '_edd_product_badge_text', true );

                $margin_left= get_post_meta( $post_id, '_edd_product_badge_margin_left', true );
                $margin_left= ( isset( $margin_left ) && !empty( $margin_left ) ? $margin_left : '0' );
                $margin_top = get_post_meta( $post_id, '_edd_product_badge_margin_top', true );
                $margin_top = ( isset( $margin_top ) && !empty( $margin_top ) ? $margin_top : '0' );

                $opacity    = get_post_meta( $post_id, '_edd_product_badge_opacity', true );
                $opacity    = ( isset( $opacity ) && !empty( $opacity ) ? $opacity : '1' );

                if( $type == 'image' && !empty( $image ) ) {
                    $width      = get_post_meta( $post_id, '_edd_product_badge_width', true );
                    $height     = get_post_meta( $post_id, '_edd_product_badge_height', true );

                    $style       = ( isset( $width ) && !empty( $width ) ? 'width: ' . $width . ' ' : '' );
                    $style      .= ( isset( $height ) && !empty( $height ) ? 'height: ' . $height . ' ' : '' );
                    $style      .= 'margin-left: ' . $margin_left . 'px; ';
                    $style      .= 'margin-top: ' . $margin_top . 'px; ';
                    $style      .= 'opacity: ' . $opacity . ';';

                    $badge   = '<span class="product-badge" style="position: absolute;">';
                    $badge  .= '<img src="' . $image . '" style="' . $style . '" />';
                    $badge  .= '</span>';

                    echo $badge;    
                } elseif( $type == 'text' && !empty( $text ) ) {
                    $padding_top    = get_post_meta( $post_id, '_edd_product_badge_padding_top', true );
                    $padding_top    = ( isset( $padding_top ) && !empty( $padding_top ) ? $padding_top . 'px' : '0' );
                    $padding_right  = get_post_meta( $post_id, '_edd_product_badge_padding_right', true );
                    $padding_right  = ( isset( $padding_right ) && !empty( $padding_right ) ? $padding_right . 'px' : '0' );
                    $padding_bottom = get_post_meta( $post_id, '_edd_product_badge_padding_bottom', true );
                    $padding_bottom = ( isset( $padding_bottom ) && !empty( $padding_bottom ) ? $padding_bottom . 'px' : '0' );
                    $padding_left   = get_post_meta( $post_id, '_edd_product_badge_padding_left', true );
                    $padding_left   = ( isset( $padding_left ) && !empty( $padding_left ) ? $padding_left . 'px' : '0' );

                    $radius_tl      = get_post_meta( $post_id, '_edd_product_badge_radius_tl', true );
                    $radius_tl      = ( isset( $radius_tl ) && !empty( $radius_tl ) ? $radius_tl . 'px' : '0' );
                    $radius_tr      = get_post_meta( $post_id, '_edd_product_badge_radius_tr', true );
                    $radius_tr      = ( isset( $radius_tr ) && !empty( $radius_tr ) ? $radius_tr . 'px' : '0' );
                    $radius_bl      = get_post_meta( $post_id, '_edd_product_badge_radius_bl', true );
                    $radius_bl      = ( isset( $radius_bl ) && !empty( $radius_bl ) ? $radius_bl . 'px' : '0' );
                    $radius_br      = get_post_meta( $post_id, '_edd_product_badge_radius_br', true );
                    $radius_br      = ( isset( $radius_br ) && !empty( $radius_br ) ? $radius_br . 'px' : '0' );

                    $text_color     = get_post_meta( $post_id, '_edd_product_badge_text_color', true );
                    $background     = get_post_meta( $post_id, '_edd_product_badge_background', true );

                    $style       = 'padding: ' . $padding_top . ' ' . $padding_right . ' ' . $padding_bottom . ' ' . $padding_left . '; ';
                    $style      .= 'border-radius: ' . $radius_tl . ' ' . $radius_tr . ' ' . $radius_br . ' ' . $radius_bl . '; ';
                    $style      .= '-moz-border-radius: ' . $radius_tl . ' ' . $radius_tr . ' ' . $radius_br . ' ' . $radius_bl . '; ';
                    $style      .= '-webkit-border-radius: ' . $radius_tl . ' ' . $radius_tr . ' ' . $radius_br . ' ' . $radius_bl . '; ';
                    $style      .= 'color: ' . $text_color . '; ';
                    $style      .= 'background: ' . $background . '; ';
                    $style      .= 'margin-left: ' . $margin_left . 'px; ';
                    $style      .= 'margin-top: ' . $margin_top . 'px; ';
                    $style      .= 'opacity: ' . $opacity . ';';

                    $badge   = '<span class="product-badge" style="position: absolute; ' . $style . '">';
                    $badge  .= $text;
                    $badge  .= '</span>';

                    echo $badge;
                }
            }
        }
    }
}


/**
 * The main function responsible for returning the one true EDD_Product_Badges
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      EDD_Product_Badges The one true EDD_Product_Badges
 */
function EDD_Product_Badges_load() {
    if( !class_exists( 'Easy_Digital_Downloads' ) ) {
        deactivate_plugins( __FILE__ );
        unset( $_GET['activate'] );

        // Display notice
        add_action( 'admin_notices', 'EDD_Product_Badges_missing_edd_notice' );
    } else {
        return EDD_Product_Badges::instance();
    }
}
add_action( 'plugins_loaded', 'EDD_Product_Badges_load' );


/**
 * We need Easy Digital Downloads... if it isn't present, notify the user!
 *
 * @since       1.0.0
 * @return      void
 */
function EDD_Product_Badges_missing_edd_notice() {
    echo '<div class="error"><p>' . __( 'Product Badges requires Easy Digital Downloads! Please install it to continue!', 'edd-product-badges' ) . '</p></div>';
}
