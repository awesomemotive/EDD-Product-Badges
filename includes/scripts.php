<?php
/**
 * Scripts
 *
 * @package     EDD\ProductBadges\Scripts
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


/**
 * Load admin scripts
 *
 * @since       1.0.0
 * @param       string $hook The page we are currently on
 * @global      string $post_type The type of post that we are editing
 * @return      void
 */
function edd_product_badges_admin_scripts( $hook ) {
    global $post_type;

    if( ( $hook == 'post.php' || $hook == 'post-new.php' ) && $post_type == 'download' ) {
        wp_enqueue_script( 'edd_product_badges_metabox_js', EDD_PRODUCT_BADGES_URL . '/assets/js/metabox.js', array( 'jquery' ) );
    }
}
