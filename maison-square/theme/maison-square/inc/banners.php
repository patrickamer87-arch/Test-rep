<?php
/**
 * Home page banners, managed under "Banners" in the admin menu.
 *
 * @package MaisonSquare
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Banners screen.
 */
function ms_register_banners() {
	register_post_type(
		'ms_banner',
		array(
			'labels'              => array(
				'name'                  => __( 'Banners', 'maison-square' ),
				'singular_name'         => __( 'Banner', 'maison-square' ),
				'menu_name'             => __( 'Banners', 'maison-square' ),
				'add_new'               => __( 'Add banner', 'maison-square' ),
				'add_new_item'          => __( 'Add a banner', 'maison-square' ),
				'edit_item'             => __( 'Edit banner', 'maison-square' ),
				'new_item'              => __( 'New banner', 'maison-square' ),
				'search_items'          => __( 'Search banners', 'maison-square' ),
				'not_found'             => __( 'No banners yet. The home page shows the default headline until you add one.', 'maison-square' ),
				'not_found_in_trash'    => __( 'No banners in the bin.', 'maison-square' ),
				'featured_image'        => __( 'Banner photo', 'maison-square' ),
				'set_featured_image'    => __( 'Choose the banner photo (square works best)', 'maison-square' ),
				'remove_featured_image' => __( 'Remove the banner photo', 'maison-square' ),
				'use_featured_image'    => __( 'Use as banner photo', 'maison-square' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'menu_position'       => 21,
			'menu_icon'           => 'dashicons-format-image',
			'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'exclude_from_search' => true,
		)
	);
}
add_action( 'init', 'ms_register_banners' );

/**
 * Prompt in the title field.
 *
 * @param string  $text Placeholder.
 * @param WP_Post $post Post.
 * @return string
 */
function ms_banner_title_placeholder( $text, $post ) {
	if ( $post && 'ms_banner' === $post->post_type ) {
		return __( 'Banner headline, lowercase, ending with a full stop.', 'maison-square' );
	}
	return $text;
}
add_filter( 'enter_title_here', 'ms_banner_title_placeholder', 10, 2 );

/**
 * Add the text and button box.
 */
function ms_banner_meta_box() {
	add_meta_box(
		'ms_banner_details',
		__( 'Banner text and button', 'maison-square' ),
		'ms_banner_meta_box_html',
		'ms_banner',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'ms_banner_meta_box' );

/**
 * Fields of the text and button box.
 *
 * @param WP_Post $post Banner.
 */
function ms_banner_meta_box_html( $post ) {
	wp_nonce_field( 'ms_banner_save', 'ms_banner_nonce' );
	$text   = (string) get_post_meta( $post->ID, '_ms_banner_text', true );
	$button = (string) get_post_meta( $post->ID, '_ms_banner_button', true );
	$url    = (string) get_post_meta( $post->ID, '_ms_banner_url', true );
	?>
	<p>
		<label for="ms_banner_text"><strong><?php esc_html_e( 'Text under the headline', 'maison-square' ); ?></strong></label><br>
		<textarea id="ms_banner_text" name="ms_banner_text" rows="3" style="width:100%"><?php echo esc_textarea( $text ); ?></textarea>
	</p>
	<p>
		<label for="ms_banner_button"><strong><?php esc_html_e( 'Button label', 'maison-square' ); ?></strong></label><br>
		<input type="text" id="ms_banner_button" name="ms_banner_button" class="regular-text" value="<?php echo esc_attr( $button ); ?>">
	</p>
	<p>
		<label for="ms_banner_url"><strong><?php esc_html_e( 'Button link', 'maison-square' ); ?></strong></label><br>
		<input type="url" id="ms_banner_url" name="ms_banner_url" class="large-text" placeholder="https://" value="<?php echo esc_attr( $url ); ?>">
	</p>
	<p class="description">
		<?php esc_html_e( 'The title is the headline. Add a square photo as the banner photo, and use Order (on the right) to sort banners. Up to five published banners are shown.', 'maison-square' ); ?>
	</p>
	<?php
}

/**
 * Save the text and button box.
 *
 * @param int $post_id Banner id.
 */
function ms_banner_save( $post_id ) {
	if ( ! isset( $_POST['ms_banner_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ms_banner_nonce'] ) ), 'ms_banner_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text   = isset( $_POST['ms_banner_text'] ) ? sanitize_textarea_field( wp_unslash( $_POST['ms_banner_text'] ) ) : '';
	$button = isset( $_POST['ms_banner_button'] ) ? sanitize_text_field( wp_unslash( $_POST['ms_banner_button'] ) ) : '';
	$url    = isset( $_POST['ms_banner_url'] ) ? esc_url_raw( wp_unslash( $_POST['ms_banner_url'] ) ) : '';

	update_post_meta( $post_id, '_ms_banner_text', $text );
	update_post_meta( $post_id, '_ms_banner_button', $button );
	update_post_meta( $post_id, '_ms_banner_url', $url );
}
add_action( 'save_post_ms_banner', 'ms_banner_save' );

/**
 * Published banners, sorted by Order.
 *
 * @return array[] Each with title, text, button, url and image (attachment id).
 */
function ms_get_banners() {
	$posts = get_posts(
		array(
			'post_type'   => 'ms_banner',
			'post_status' => 'publish',
			'numberposts' => 5,
			'orderby'     => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
		)
	);

	$banners = array();
	foreach ( $posts as $post ) {
		$banners[] = array(
			'title'  => get_the_title( $post ),
			'text'   => (string) get_post_meta( $post->ID, '_ms_banner_text', true ),
			'button' => (string) get_post_meta( $post->ID, '_ms_banner_button', true ),
			'url'    => (string) get_post_meta( $post->ID, '_ms_banner_url', true ),
			'image'  => (int) get_post_thumbnail_id( $post ),
		);
	}
	return $banners;
}
