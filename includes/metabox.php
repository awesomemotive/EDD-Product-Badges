<?php
/**
 * Meta Box
 *
 * @package		EDD\ProductBadges\MetaBox
 * @since		1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


/**
 * Register meta box for Product Badges
 *
 * @since		1.0.0
 * @return		void
 */
function edd_product_badges_add_meta_box() {
	add_meta_box(
		'productbadges',
		__( 'Product Badge', 'edd-product-badges' ),
		'edd_product_badges_render_meta_box',
		'download',
		'side',
		'default'
	);
}


/**
 * Render meta box
 *
 * @since		1.0.0
 * @global		object $post The post we are editing
 * @return		void
 */
function edd_product_badges_render_meta_box() {
	global $post;

	$post_id = $post->ID;
	$enable_badge			= get_post_meta( $post_id, '_edd_product_badge_enable', true ) ? true : false;
	$enable_badge_css		= ( $enable_badge == false ? ' style="display: none;"' : '' );
	$badge_type				= get_post_meta( $post_id, '_edd_product_badge_type', true );
	$badge_text				= get_post_meta( $post_id, '_edd_product_badge_text', true );
	$badge_text_css			= ( $badge_type == 'image' ? ' style="display: none;"' : '' );
	$badge_image			= get_post_meta( $post_id, '_edd_product_badge_image', true );
	$badge_image_css		= ( empty( $badge_type ) || $badge_type == 'text' ? ' style="display: none;"' : '' );
	$badge_margin_left		= get_post_meta( $post_id, '_edd_product_badge_margin_left', true );
	$badge_margin_top		= get_post_meta( $post_id, '_edd_product_badge_margin_top', true );
	$badge_opacity			= get_post_meta( $post_id, '_edd_product_badge_opacity', true );
	$badge_padding_top		= get_post_meta( $post_id, '_edd_product_badge_padding_top', true );
	$badge_padding_bottom	= get_post_meta( $post_id, '_edd_product_badge_padding_bottom', true );
	$badge_padding_left		= get_post_meta( $post_id, '_edd_product_badge_padding_left', true );
	$badge_padding_right	= get_post_meta( $post_id, '_edd_product_badge_padding_right', true );
	$badge_padding_css		= ( $badge_type == 'image' ? ' style="display: none;"' : '' );
	$badge_radius_tl		= get_post_meta( $post_id, '_edd_product_badge_radius_tl', true );
	$badge_radius_tr		= get_post_meta( $post_id, '_edd_product_badge_radius_tr', true );
	$badge_radius_bl		= get_post_meta( $post_id, '_edd_product_badge_radius_bl', true );
	$badge_radius_br		= get_post_meta( $post_id, '_edd_product_badge_radius_br', true );
	$badge_radius_css		= ( $badge_type == 'image' ? ' style="display: none;"' : '' );
	$badge_text_color		= get_post_meta( $post_id, '_edd_product_badge_text_color', true );
	$badge_text_color_css	= ( $badge_type == 'image' ? ' style="display: none;"' : '' );
	$badge_background		= get_post_meta( $post_id, '_edd_product_badge_background', true );
	$badge_background_css	= ( $badge_type == 'image' ? ' style="display: none;"' : '' );
	$badge_width			= get_post_meta( $post_id, '_edd_product_badge_width', true );
	$badge_height			= get_post_meta( $post_id, '_edd_product_badge_height', true );
	$badge_size_css			= ( $badge_type == 'text' ? ' style="display: none;"' : '' );

	// Enable badge on this file?
	echo '<p><label for="_edd_product_badge_enable">
		<input type="checkbox" name="_edd_product_badge_enable" id="_edd_product_badge_enable" value="1" ' . checked( true,  $enable_badge, false ) . ' /> ' .
		__( 'Enable Badge?', 'edd-product-badges' ) .
		'</label></p>';

	echo '<div id="edd_product_badge_wrapper"' . $enable_badge_css . '>';

	echo '<p><strong>' . __( 'Badge Type:', 'edd-product-badges' ) . '</strong></p>';

	// Badge type
	echo '<p><label for="_edd_product_badge_type">' .
		__( 'Text/image based', 'edd-product-badges' ) . '<label><br />
		<select name="_edd_product_badge_type" id="_edd_product_badge_type">
		<option value="text"' . ( !isset( $badge_type ) || $badge_type == 'text' ? ' selected' : '' ) . '>' . __( 'Text based', 'edd-product-badges' ) . '</option>
		<option value="image"' . ( isset( $badge_type ) && $badge_type == 'image' ? ' selected' : '' ) . '>' . __( 'Image based', 'edd-product-badges' ) . '</option>
		</select>
		</p>';

	// Badge text
	echo '<p' . $badge_text_css . '><label for="_edd_product_badge_text">' .
		__( 'Badge text', 'edd-product-badges' ) . '</label><br />
		<input type="text" name="_edd_product_badge_text" id="_edd_product_badge_text" class="large-text" value="' . ( isset( $badge_text ) && !empty( $badge_text ) ? $badge_text : '' ) . '" /><br />' .
		'</p>';

	// Badge image
	if( empty( $badge_image ) ) {
		$file = '';
	}

	echo '<div class="edd_product_badge_image_wrapper"' . $badge_image_css . '>' .
		__( 'Upload a file or enter the URL', 'edd-product-badges' ) . '<br />' .
		EDD()->html->text( array(
			'name'			=> '_edd_product_badge_image',
			'value'			=> $badge_image,
			'placeholder'	=> '',
			'class'			=> 'edd_product_badge_image_upload_field large-text',
		) ) .
		'<span class="edd_upload_file" style="position: relative; float: right; margin-top: -29px; margin-right: -3px;">
		<a href="#" data-uploader_title="" data-uploader_button_text="' . __( 'Insert', 'edd-product-badges' ) . '" class="edd_product_badge_upload_image_button" onclick="return false;">' . __( 'Upload a File', 'edd-product-badges' ) . '</a>
		</span>
		</div>';

	echo '<p><strong>' . __( 'Badge Style:', 'edd-product-badges' ) . '</strong></p>';

	// Badge opacity
	echo '<p><label for="_edd_product_badge_opacity">' .
		__( 'Opacity', 'edd-product-badges' ) . '<label><br />
		<select name="_edd_product_badge_opacity" id="_edd_product_badge_opacity">
		<option value="1"' . ( !isset( $badge_opacity ) || $badge_opacity == '1' ? ' selected' : '' ) . '>1</option>
		<option value="0.9"' . ( isset( $badge_opacity ) && $badge_opacity == '0.9' ? ' selected' : '' ) . '>0.9</option>
		<option value="0.8"' . ( isset( $badge_opacity ) && $badge_opacity == '0.8' ? ' selected' : '' ) . '>0.8</option>
		<option value="0.7"' . ( isset( $badge_opacity ) && $badge_opacity == '0.7' ? ' selected' : '' ) . '>0.7</option>
		<option value="0.6"' . ( isset( $badge_opacity ) && $badge_opacity == '0.6' ? ' selected' : '' ) . '>0.6</option>
		<option value="0.5"' . ( isset( $badge_opacity ) && $badge_opacity == '0.5' ? ' selected' : '' ) . '>0.5</option>
		<option value="0.4"' . ( isset( $badge_opacity ) && $badge_opacity == '0.4' ? ' selected' : '' ) . '>0.4</option>
		<option value="0.3"' . ( isset( $badge_opacity ) && $badge_opacity == '0.3' ? ' selected' : '' ) . '>0.3</option>
		<option value="0.2"' . ( isset( $badge_opacity ) && $badge_opacity == '0.2' ? ' selected' : '' ) . '>0.2</option>
		<option value="0.1"' . ( isset( $badge_opacity ) && $badge_opacity == '0.1' ? ' selected' : '' ) . '>0.1</option>
		<option value="0"' . ( isset( $badge_opacity ) && $badge_opacity == '0' ? ' selected' : '' ) . '>0</option>
		</select>
		</p>';

	// Badge margins
	echo '<p>' .
		__( 'Badge margins (without px)', 'edd-product-badges' ) . '<br />
		<input type="text" name="_edd_product_badge_margin_left" id="_edd_product_badge_margin_left" class="small-text" style="width: 23%;" value="' . ( isset( $badge_margin_left ) && !empty( $badge_margin_left ) ? $badge_margin_left : '0' ) . '" />
		<input type="text" name="_edd_product_badge_margin_top" id="_edd_product_badge_margin_top" class="small-text" style="width: 23%;" value="' . ( isset( $badge_margin_top ) && !empty( $badge_margin_top ) ? $badge_margin_top : '0' ) . '" /><br />
		<span style="width: 23%; display: inline-block; text-align: center; margin-right: 1%;"><label for="_edd_product_badge_margin_left">' . __( 'Left', 'edd-product-badges' ) . '</label></span>
		<span style="width: 23%; display: inline-block; text-align: center; margin-right: 1%;"><label for="_edd_product_badge_margin_top">' . __( 'Top', 'edd-product-badges' ) . '</label></span>
		</p>';

	// Badge padding
	echo '<p' . $badge_padding_css . ' class="edd_product_badge_padding">' .
		__( 'Badge text padding (without px)', 'edd-product-badges' ) . '<br />
		<input type="text" name="_edd_product_badge_padding_top" id="_edd_product_badge_padding_top" class="small-text" style="width: 23%;" value="' . ( isset( $badge_padding_top ) && !empty( $badge_padding_top ) ? $badge_padding_top : '0' ) . '" />
		<input type="text" name="_edd_product_badge_padding_right" id="_edd_product_badge_padding_right" class="small-text" style="width: 23%;" value="' . ( isset( $badge_padding_right ) && !empty( $badge_padding_right ) ? $badge_padding_right : '0' ) . '" />
		<input type="text" name="_edd_product_badge_padding_bottom" id="_edd_product_badge_padding_bottom" class="small-text" style="width: 23%;" value="' . ( isset( $badge_padding_bottom ) && !empty( $badge_padding_bottom ) ? $badge_padding_bottom : '0' ) . '" />
		<input type="text" name="_edd_product_badge_padding_left" id="_edd_product_badge_padding_left" class="small-text" style="width: 23%;" value="' . ( isset( $badge_padding_left ) && !empty( $badge_padding_left ) ? $badge_padding_left : '0' ) . '" /><br />
		<span style="width: 23%; display: inline-block; text-align: center; margin-right: 1%;"><label for="_edd_product_badge_padding_top">' . __( 'Top', 'edd-product-badges' ) . '</label></span>
		<span style="width: 23%; display: inline-block; text-align: center; margin-right: 1%;"><label for="_edd_product_badge_padding_right">' . __( 'Right', 'edd-product-badges' ) . '</label></span>
		<span style="width: 23%; display: inline-block; text-align: center; margin-right: 1%;"><label for="_edd_product_badge_padding_bottom">' . __( 'Bottom', 'edd-product-badges' ) . '</label></span>
		<span style="width: 23%; display: inline-block; text-align: center; margin-right: 1%;"><label for="_edd_product_badge_padding_left">' . __( 'Left', 'edd-product-badges' ) . '</label></span>
		</p>';

	// Badge radius
	echo '<p' . $badge_radius_css . ' class="edd_product_badge_radius">' .
		__( 'Badge radius (without px)', 'edd-product-badges' ) . '<br />
		<input type="text" name="_edd_product_badge_radius_tl" id="_edd_product_badge_radius_tl" class="small-text" style="width: 23%;" value="' . ( isset( $badge_radius_tl ) && !empty( $badge_radius_tl ) ? $badge_radius_tl : '0' ) . '" />
		<input type="text" name="_edd_product_badge_radius_tr" id="_edd_product_badge_radius_tr" class="small-text" style="width: 23%;" value="' . ( isset( $badge_radius_tr ) && !empty( $badge_radius_tr ) ? $badge_radius_tr : '0' ) . '" />
		<input type="text" name="_edd_product_badge_radius_bl" id="_edd_product_badge_radius_bl" class="small-text" style="width: 23%;" value="' . ( isset( $badge_radius_bl ) && !empty( $badge_radius_bl ) ? $badge_radius_bl : '0' ) . '" />
		<input type="text" name="_edd_product_badge_radius_br" id="_edd_product_badge_radius_br" class="small-text" style="width: 23%;" value="' . ( isset( $badge_radius_br ) && !empty( $badge_radius_br ) ? $badge_radius_br : '0' ) . '" /><br />
		<span style="width: 23%; display: inline-block; text-align: center; margin-right: 1%; vertical-align: top;"><label for="_edd_product_badge_radius_tl">' . __( 'Top Left', 'edd-product-badges' ) . '</label></span>
		<span style="width: 23%; display: inline-block; text-align: center; margin-right: 1%; vertical-align: top;"><label for="_edd_product_badge_radius_tr">' . __( 'Top Right', 'edd-product-badges' ) . '</label></span>
		<span style="width: 23%; display: inline-block; text-align: center; margin-right: 1%;"><label for="_edd_product_badge_radius_bl">' . __( 'Bottom Left', 'edd-product-badges' ) . '</label></span>
		<span style="width: 23%; display: inline-block; text-align: center;"><label for="_edd_product_badge_radius_br">' . __( 'Bottom Right', 'edd-product-badges' ) . '</label></span>
		</label></p>';

	// Badge text color
	echo '<p' . $badge_text_color_css . '><label for="_edd_product_badge_text_color">' .
		__( 'Badge text color', 'edd-product-badges' ) . '</label><br />
		<input type="text" class="edd-color-picker" id="_edd_product_badge_text_color" name="_edd_product_badge_text_color" value="' . ( isset( $badge_text_color ) && !empty( $badge_text_color ) ? $badge_text_color : '#ffffff' ) . '" data-default-color="#ffffff" />
		</p>';

	// Badge background color
	echo '<p' . $badge_background_css . '><label for="_edd_product_badge_background">' .
		__( 'Badge background color', 'edd-product-badges' ) . '</label><br />
		<input type="text" class="edd-color-picker" id="_edd_product_badge_background" name="_edd_product_badge_background" value="' . ( isset( $badge_background ) && !empty( $badge_background ) ? $badge_background : '#ff0000' ) . '" data-default-color="#ff0000" />
		</p>';

	// Badge size
	echo '<p' . $badge_size_css . ' class="edd_product_badge_size">' .
		__( 'Badge image size (include px or %)', 'edd-product-badges' ) . '<br />
		<input type="text" name="_edd_product_badge_width" id="_edd_product_badge_width" class="small-text" style="width: 23%;" value="' . ( isset( $badge_width ) && !empty( $badge_width ) ? $badge_width : '' ) . '" />
		<input type="text" name="_edd_product_badge_height" id="_edd_product_badge_width" class="small-text" style="width: 23%;" value="' . ( isset( $badge_height ) && !empty( $badge_height ) ? $badge_height : '' ) . '" /><br />
		<span style="width: 23%; display: inline-block; text-align: center; margin-right: 1%;"><label for="_edd_product_badge_width">' . __( 'Width', 'edd-product-badges' ) . '</label></span>
		<span style="width: 23%; display: inline-block; text-align: center;"><label for="_edd_product_badge_height">' . __( 'Height', 'edd-product-badges' ) . '</label></span>
		</label></p>';

	// Allow extension of the Product Badges meta box
	do_action( 'edd_product_badges_meta_box_fields', $post->ID );

	echo '</div>';

	wp_nonce_field( basename( __FILE__ ), 'edd_product_badges_meta_box_nonce' );
}


/**
 * Save post meta when the save_post action is called
 *
 * @since		1.0.0
 * @param		int $post_id The ID of the post we are saving
 * @global		object $post The post we are saving
 * @return		void
 */
function edd_product_badges_meta_box_save( $post_id ) {
	global $post;

	// Don't process if nonce can't be validated
	if( !isset( $_POST['edd_product_badges_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['edd_product_badges_meta_box_nonce'], basename( __FILE__ ) ) ) return $post_id;

	// Don't process if this is an autosave
	if( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) return $post_id;

	// Don't process if this is a revision
	if( isset( $post->post_type ) && $post->post_type == 'revision' ) return $post_id;

	// Don't process if the current user shouldn't be editing this product
	if( !current_user_can( 'edit_product', $post_id ) ) return $post_id;

	$fields = apply_filters( 'edd_product_badges_meta_box_fields_save', array(
			'_edd_product_badge_enable',
			'_edd_product_badge_type',
			'_edd_product_badge_text',
			'_edd_product_badge_image',
			'_edd_product_badge_opacity',
			'_edd_product_badge_margin_left',
			'_edd_product_badge_margin_top',
			'_edd_product_badge_padding_top',
			'_edd_product_badge_padding_bottom',
			'_edd_product_badge_padding_left',
			'_edd_product_badge_padding_right',
			'_edd_product_badge_radius_tl',
			'_edd_product_badge_radius_tr',
			'_edd_product_badge_radius_bl',
			'_edd_product_badge_radius_br',
			'_edd_product_badge_text_color',
			'_edd_product_badge_background',
			'_edd_product_badge_width',
			'_edd_product_badge_height'
		)
	);

	foreach( $fields as $field ) {
		if( isset( $_POST[ $field ] ) ) {
			if( is_string( $_POST[ $field ] ) ) {
				$new = esc_attr( $_POST[ $field ] );
			} else {
				$new = $_POST[ $field ];
			}

			$new = apply_filters( 'edd_product_badges_meta_box_save_' . $field, $new );

			update_post_meta( $post_id, $field, $new );
		} else {
			delete_post_meta( $post_id, $field );
		}
	}
}
