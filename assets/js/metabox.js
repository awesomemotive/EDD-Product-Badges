/*global jQuery, document, wp, edd_vars, window, setInterval, tb_show, imgurl, tb_remove*/
jQuery(document).ready(function () {
	'use strict';
	jQuery("input[name='_edd_product_badge_enable']").change(function () {
		if (jQuery(this).is(':checked')) {
			jQuery('#edd_product_badge_wrapper').css('display', 'block');
		} else {
			jQuery('#edd_product_badge_wrapper').css('display', 'none');
		}
	});
	jQuery("select[name='_edd_product_badge_type']").change(function () {
		if (jQuery("select[name='_edd_product_badge_type'] option:selected").val() === 'text') {
			jQuery("label[for='_edd_product_badge_text']").closest('p').css('display', 'block');
			jQuery('#_edd_product_badge_image').closest('div').css('display', 'none');
			jQuery('.edd_product_badge_padding').css('display', 'block');
			jQuery('.edd_product_badge_radius').css('display', 'block');
			jQuery('.edd_product_badge_size').css('display', 'none');
			jQuery("label[for='_edd_product_badge_text_color']").closest('p').css('display', 'block');
			jQuery("label[for='_edd_product_badge_background']").closest('p').css('display', 'block');
			jQuery("label[for='_edd_product_badge_size']").closest('p').css('display', 'none');
		} else {
			jQuery("label[for='_edd_product_badge_text']").closest('p').css('display', 'none');
			jQuery('#_edd_product_badge_image').closest('div').css('display', 'block');
			jQuery('.edd_product_badge_padding').css('display', 'none');
			jQuery('.edd_product_badge_radius').css('display', 'none');
			jQuery('.edd_product_badge_size').css('display', 'block');
			jQuery("label[for='_edd_product_badge_text_color']").closest('p').css('display', 'none');
			jQuery("label[for='_edd_product_badge_background']").closest('p').css('display', 'none');
			jQuery("label[for='_edd_product_badge_size']").closest('p').css('display', 'block');
		}
	});

	if (wp === 'undefined' || edd_vars.new_media_ui !== '1') {
		// Old thickbox uploader
		if (jQuery('.edd_product_badge_upload_image_button').length > 0) {
			window.formfield = '';

			jQuery('body').on('click', '.edd_product_badge_upload_image_button', function (e) {
				e.preventDefault();
				window.formfield = jQuery(this).parent().prev();
				window.tbframe_interval = setInterval(function () {
					jQuery('#TB_iframeContent').contents().find('.savesend .button').val(edd_vars.use_this_file).end().find('#insert-gallery, .wp-post-thumbnail').hide();
				}, 2000);
				if (edd_vars.post_id !== null) {
					var post_id = 'post_id=' + edd_vars.post_id + '&';
				}
				tb_show(edd_vars.add_new_download, 'media-upload.php?' + post_id + 'TB_iframe=true');
			});

			window.edd_send_to_editor = window.send_to_editor;
			window.send_to_editor = function (html) {
				if (window.formfield) {
					imgurl = jQuery('a', '<div>' + html + '</div>').attr('href');
					window.formfield.val(imgurl);
					window.clearInverval(window.tbframe_interval);
					tb_remove();
				} else {
					window.edd_send_to_editor(html);
				}
				window.send_to_editor = window.edd_send_to_editor;
				window.formfield = '';
				window.imagefield = false;
			};
		}
	} else {
		// WordPress 3.5+ uploader
		var file_frame;
		window.formfield = '';

		jQuery('body').on('click', '.edd_product_badge_upload_image_button', function (e) {
			e.preventDefault();

			var button = jQuery(this);

			window.formfield = jQuery(this).closest('.edd_product_badge_image_wrapper');

			// If the media frame already exists, reopen it
			if (file_frame) {
				file_frame.open();
				return;
			}

			// Create the media frame
			file_frame = wp.media.frames.file_frame = wp.media({
				frame: 'post',
				state: 'insert',
				title: button.data('uploader_title'),
				button: {
					text: button.data('uploader_button_text'),
				},
				multiple: jQuery(this).data('multiple') === '0' ? false : true
			});

			file_frame.on('menu:render:default', function (view) {
				// Store our views in an object
				var views = {};

				// Unset default menu items
				view.unset('library-separator');
				view.unset('gallery');
				view.unset('featured-image');
				view.unset('embed');

				// Initialize the views in our view object
				view.set(views);
			});

			// When an image is selected, run a callback
			file_frame.on('insert', function () {
				var selection = file_frame.state().get('selection');
				selection.each(function (attachment, index) {
					attachment = attachment.toJSON();
					window.formfield.find('.edd_product_badge_image_upload_field').val(attachment.url);
				});
			});

			// Open the modal
			file_frame.open();
		});

		// WordPress 3.5+ uploader
		var file_frame;
		window.formfield = '';
	}
});
