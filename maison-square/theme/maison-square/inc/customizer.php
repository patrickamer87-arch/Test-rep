<?php
/**
 * Store details the team can change under Appearance > Customize > Maison Square.
 *
 * @package MaisonSquare
 */

defined( 'ABSPATH' ) || exit;

/**
 * The editable fields: key => section, label, type.
 *
 * @return array
 */
function ms_customizer_fields() {
	return array(
		'topbar_text'     => array( 'ms_home', __( 'Line at the very top of the site', 'maison-square' ), 'text' ),
		'hero_title'      => array( 'ms_home', __( 'Headline (used when no banner is published)', 'maison-square' ), 'text' ),
		'hero_text'       => array( 'ms_home', __( 'Text under the headline', 'maison-square' ), 'textarea' ),
		'hero_button'     => array( 'ms_home', __( 'Button label', 'maison-square' ), 'text' ),
		'arrivals_count'  => array( 'ms_home', __( 'How many new arrivals to show', 'maison-square' ), 'number' ),
		'service_1_title' => array( 'ms_home', __( 'Service 1: title', 'maison-square' ), 'text' ),
		'service_1_text'  => array( 'ms_home', __( 'Service 1: text', 'maison-square' ), 'text' ),
		'service_2_title' => array( 'ms_home', __( 'Service 2: title', 'maison-square' ), 'text' ),
		'service_2_text'  => array( 'ms_home', __( 'Service 2: text', 'maison-square' ), 'text' ),
		'service_3_title' => array( 'ms_home', __( 'Service 3: title', 'maison-square' ), 'text' ),
		'service_3_text'  => array( 'ms_home', __( 'Service 3: text', 'maison-square' ), 'text' ),

		'email'           => array( 'ms_contact', __( 'Email', 'maison-square' ), 'email' ),
		'phone'           => array( 'ms_contact', __( 'Phone', 'maison-square' ), 'text' ),
		'whatsapp'        => array( 'ms_contact', __( 'WhatsApp number, with country code', 'maison-square' ), 'text' ),
		'address'         => array( 'ms_contact', __( 'Store address', 'maison-square' ), 'textarea' ),
		'hours'           => array( 'ms_contact', __( 'Opening hours', 'maison-square' ), 'text' ),
		'instagram'       => array( 'ms_contact', __( 'Instagram link', 'maison-square' ), 'url' ),
		'facebook'        => array( 'ms_contact', __( 'Facebook link', 'maison-square' ), 'url' ),
		'tiktok'          => array( 'ms_contact', __( 'TikTok link', 'maison-square' ), 'url' ),
		'youtube'         => array( 'ms_contact', __( 'YouTube link', 'maison-square' ), 'url' ),

		'payments'        => array( 'ms_footer', __( 'We accept (separate with commas)', 'maison-square' ), 'text', __( 'Visa, Mastercard, American Express, Google Pay, Apple Pay and Cash on delivery get a picture automatically. For any other name, upload its logo to Media as a picture named logo- plus the name, for example logo-whish.png. Every name is shown as a card of the same size; a picture 192 wide and 120 high fills it exactly.', 'maison-square' ) ),
		'carriers'        => array( 'ms_footer', __( 'We ship with (separate with commas)', 'maison-square' ), 'text', __( 'To show a logo instead of a name, upload it to Media as a picture named logo- plus the name, for example logo-aramex.png or logo-dhl.png. A picture 192 wide and 120 high fills the card exactly.', 'maison-square' ) ),
		'footer_note'     => array( 'ms_footer', __( 'Line under the footer logo', 'maison-square' ), 'text' ),
	);
}

/**
 * Sanitizer for a field type.
 *
 * @param string $type Field type.
 * @return callable
 */
function ms_customizer_sanitizer( $type ) {
	switch ( $type ) {
		case 'textarea':
			return 'sanitize_textarea_field';
		case 'url':
			return 'esc_url_raw';
		case 'email':
			return 'sanitize_email';
		case 'number':
			return 'absint';
		default:
			return 'sanitize_text_field';
	}
}

/**
 * Register the panel, its sections and fields.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function ms_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'ms_panel',
		array(
			'title'    => __( 'Maison Square', 'maison-square' ),
			'priority' => 20,
		)
	);

	$sections = array(
		'ms_home'    => __( 'Home page', 'maison-square' ),
		'ms_contact' => __( 'Contact details and social links', 'maison-square' ),
		'ms_footer'  => __( 'Footer: payment and delivery', 'maison-square' ),
	);
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section(
			$id,
			array(
				'title' => $title,
				'panel' => 'ms_panel',
			)
		);
	}

	$defaults = ms_defaults();
	foreach ( ms_customizer_fields() as $key => $field ) {
		list( $section, $label, $type ) = $field;

		$wp_customize->add_setting(
			'ms_' . $key,
			array(
				'default'           => isset( $defaults[ $key ] ) ? $defaults[ $key ] : '',
				'sanitize_callback' => ms_customizer_sanitizer( $type ),
			)
		);
		$wp_customize->add_control(
			'ms_' . $key,
			array(
				'label'       => $label,
				'description' => isset( $field[3] ) ? $field[3] : '',
				'section'     => $section,
				'type'        => $type,
			)
		);
	}
}
add_action( 'customize_register', 'ms_customize_register' );
