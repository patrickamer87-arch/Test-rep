<?php
/**
 * Helpers used by the templates.
 *
 * @package MaisonSquare
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default values for the store details kept in the Customizer.
 *
 * @return array
 */
function ms_defaults() {
	return array(
		'topbar_text'     => 'Delivery across Lebanon, and pick-up from our store.',
		'email'           => 'hello@maisonsquare.com',
		'phone'           => '',
		'whatsapp'        => '',
		'address'         => '',
		'hours'           => '',
		'instagram'       => '',
		'facebook'        => '',
		'tiktok'          => '',
		'youtube'         => '',
		'hero_title'      => 'good things for your home, in every season.',
		'hero_text'       => 'Things for every room and every season, delivered to your door or ready to pick up from our store.',
		'hero_button'     => 'Shop new arrivals',
		'service_1_title' => 'Delivery across Lebanon',
		'service_1_text'  => '$5 for orders under 7 kg, $10 above.',
		'service_2_title' => 'Pick-up from our store',
		'service_2_text'  => 'Order online and collect it yourself.',
		'service_3_title' => 'Pay your way',
		'service_3_text'  => 'Card, Whish or cash on delivery in Lebanon.',
		'payments'        => 'Visa, Mastercard, Whish, Cash on delivery',
		'carriers'        => 'Aramex, DHL',
		'footer_note'     => 'Good things for your home, in every season.',
		'arrivals_count'  => 8,
	);
}

/**
 * Read one store detail, falling back to its default.
 *
 * @param string $key Setting name without the ms_ prefix.
 * @return mixed
 */
function ms_opt( $key ) {
	$defaults = ms_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	return get_theme_mod( 'ms_' . $key, $default );
}

/**
 * Split a comma-separated setting into a clean list.
 *
 * @param string $key Setting name.
 * @return string[]
 */
function ms_list( $key ) {
	$items = array_map( 'trim', explode( ',', (string) ms_opt( $key ) ) );
	return array_values( array_filter( $items, 'strlen' ) );
}

/**
 * Markup for one icon from the brand sprite.
 *
 * @param string $name  Icon name without the ms- prefix.
 * @param int    $size  Pixel size.
 * @param string $class Extra class.
 * @return string
 */
function ms_icon( $name, $size = 24, $class = '' ) {
	return sprintf(
		'<svg class="%1$s" width="%2$d" height="%2$d" aria-hidden="true" focusable="false"><use href="#ms-%3$s"></use></svg>',
		esc_attr( trim( 'ms-icon ' . $class ) ),
		(int) $size,
		esc_attr( $name )
	);
}

/**
 * Print the brand icon sprite once, right after <body>.
 */
function ms_print_sprite() {
	$file = MS_THEME_DIR . '/assets/img/icons-sprite.svg';
	if ( is_readable( $file ) ) {
		// Static file that ships with the theme.
		echo file_get_contents( $file ); // phpcs:ignore WordPress.Security.EscapeOutput, WordPress.WP.AlternativeFunctions
	}
}
add_action( 'wp_body_open', 'ms_print_sprite', 5 );

/**
 * The horizontal logo, linked to the home page.
 *
 * @param string $variant color or reversed.
 * @param int    $height  Pixel height.
 * @return string
 */
function ms_logo( $variant = 'color', $height = 36 ) {
	$file  = 'reversed' === $variant ? 'logo-horizontal-reversed.svg' : 'logo-horizontal-color.svg';
	$width = (int) round( $height * 8241.37 / 1200 );

	return sprintf(
		'<a class="ms-logo" href="%1$s"><img src="%2$s" width="%3$d" height="%4$d" alt="%5$s"></a>',
		esc_url( home_url( '/' ) ),
		esc_url( MS_THEME_URI . '/assets/img/' . $file ),
		$width,
		(int) $height,
		esc_attr__( 'Maison Square, home', 'maison-square' )
	);
}

/**
 * Address of the shop page.
 *
 * @return string
 */
function ms_shop_url() {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		return wc_get_page_permalink( 'shop' );
	}
	return home_url( '/' );
}

/**
 * Address of the shop page sorted by newest first.
 *
 * @return string
 */
function ms_new_arrivals_url() {
	return add_query_arg( 'orderby', 'date', ms_shop_url() );
}

/**
 * Top-level product categories, in the order set in the admin.
 *
 * @param int $limit Maximum number, 0 for all.
 * @param int $parent Parent term id.
 * @return WP_Term[]
 */
function ms_categories( $limit = 0, $parent = 0 ) {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return array();
	}

	$args = array(
		'taxonomy'   => 'product_cat',
		'parent'     => (int) $parent,
		'hide_empty' => false,
	);

	$default_cat = (int) get_option( 'default_product_cat', 0 );
	if ( $default_cat ) {
		$args['exclude'] = array( $default_cat );
	}
	if ( $limit ) {
		$args['number'] = (int) $limit;
	}

	$terms = get_terms( $args );
	return is_wp_error( $terms ) ? array() : $terms;
}

/**
 * Pick the brand icon that best fits a category name.
 *
 * @param WP_Term $term Category.
 * @return string Icon name.
 */
function ms_category_icon( $term ) {
	$map = array(
		'phone'     => 'phones',
		'laptop'    => 'laptops',
		'audio'     => 'audio',
		'watch'     => 'watches',
		'camera'    => 'cameras',
		'speaker'   => 'speakers',
		'gaming'    => 'gaming',
		'charger'   => 'chargers',
		'screen'    => 'screens',
		'batter'    => 'batteries',
		'wifi'      => 'wifi',
		'christmas' => 'christmas',
		'easter'    => 'easter',
		'ramadan'   => 'ramadan',
		'season'    => 'seasonal',
		'gift'      => 'gift',
		'candle'    => 'candles',
		'lamp'      => 'lamps',
		'light'     => 'lighting',
		'furnitur'  => 'furniture',
		'plant'     => 'plants',
		'wall'      => 'wall-art',
		'tableware' => 'tableware',
		'dining'    => 'tableware',
		'kitchen'   => 'kitchen',
		'decor'     => 'decor',
		'deco'      => 'decor',
		'offer'     => 'offers',
	);

	$haystack = strtolower( $term->slug . ' ' . $term->name );
	foreach ( $map as $needle => $icon ) {
		if ( false !== strpos( $haystack, $needle ) ) {
			return $icon;
		}
	}
	return 'categories';
}

/**
 * Print the shop menu. Uses the "Main menu" set under Appearance > Menus,
 * and falls back to the product categories when no menu is set.
 */
function ms_primary_nav() {
	add_filter( 'nav_menu_item_id', '__return_empty_string' );

	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'ms-menu',
				'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
				'depth'          => 2,
				'fallback_cb'    => false,
			)
		);
		remove_filter( 'nav_menu_item_id', '__return_empty_string' );
		return;
	}

	remove_filter( 'nav_menu_item_id', '__return_empty_string' );

	echo '<ul class="ms-menu">';
	foreach ( ms_categories( 7 ) as $term ) {
		$link = get_term_link( $term );
		if ( is_wp_error( $link ) ) {
			continue;
		}
		$children = ms_categories( 12, $term->term_id );
		printf(
			'<li class="menu-item%1$s"><a href="%2$s">%3$s</a>',
			$children ? ' menu-item-has-children' : '',
			esc_url( $link ),
			esc_html( $term->name )
		);
		if ( $children ) {
			echo '<ul class="sub-menu">';
			foreach ( $children as $child ) {
				$child_link = get_term_link( $child );
				if ( is_wp_error( $child_link ) ) {
					continue;
				}
				printf( '<li class="menu-item"><a href="%1$s">%2$s</a></li>', esc_url( $child_link ), esc_html( $child->name ) );
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	printf( '<li class="menu-item"><a href="%1$s">%2$s</a></li>', esc_url( ms_new_arrivals_url() ), esc_html__( 'New arrivals', 'maison-square' ) );
	printf( '<li class="menu-item"><a href="%1$s">%2$s</a></li>', esc_url( ms_shop_url() ), esc_html__( 'Shop all', 'maison-square' ) );
	echo '</ul>';
}

/**
 * Contact details that have been filled in.
 *
 * @return array[] Each with icon, text and url (url may be empty).
 */
function ms_contact_lines() {
	$lines = array();

	$email = sanitize_email( ms_opt( 'email' ) );
	if ( $email ) {
		$lines[] = array(
			'icon' => 'mail',
			'text' => $email,
			'url'  => 'mailto:' . $email,
		);
	}

	$phone = trim( (string) ms_opt( 'phone' ) );
	if ( '' !== $phone ) {
		$lines[] = array(
			'icon' => 'call',
			'text' => $phone,
			'url'  => 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ),
		);
	}

	$whatsapp = preg_replace( '/[^0-9]/', '', (string) ms_opt( 'whatsapp' ) );
	if ( $whatsapp ) {
		$lines[] = array(
			'icon' => 'chat',
			'text' => __( 'WhatsApp us', 'maison-square' ),
			'url'  => 'https://wa.me/' . $whatsapp,
		);
	}

	$address = trim( (string) ms_opt( 'address' ) );
	if ( '' !== $address ) {
		$lines[] = array(
			'icon' => 'location',
			'text' => $address,
			'url'  => '',
		);
	}

	$hours = trim( (string) ms_opt( 'hours' ) );
	if ( '' !== $hours ) {
		$lines[] = array(
			'icon' => 'check',
			'text' => $hours,
			'url'  => '',
		);
	}

	return $lines;
}

/**
 * Print the contact details as a list.
 *
 * @param string $class List class.
 */
function ms_contact_list( $class = 'ms-contact' ) {
	$lines = ms_contact_lines();
	if ( ! $lines ) {
		return;
	}
	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $lines as $line ) {
		echo '<li>' . ms_icon( $line['icon'], 20 ); // phpcs:ignore WordPress.Security.EscapeOutput
		if ( $line['url'] ) {
			printf( '<a href="%1$s">%2$s</a>', esc_url( $line['url'] ), esc_html( $line['text'] ) );
		} else {
			echo '<span>' . esc_html( $line['text'] ) . '</span>';
		}
		echo '</li>';
	}
	echo '</ul>';
}

/**
 * Social profiles that have been filled in.
 *
 * @return array label => url
 */
function ms_social_links() {
	$out = array();
	$all = array(
		'instagram' => 'Instagram',
		'facebook'  => 'Facebook',
		'tiktok'    => 'TikTok',
		'youtube'   => 'YouTube',
	);
	foreach ( $all as $key => $label ) {
		$url = esc_url_raw( (string) ms_opt( $key ) );
		if ( $url ) {
			$out[ $label ] = $url;
		}
	}
	return $out;
}

/**
 * Print a footer menu, with a fallback list when none is set.
 *
 * @param string $location Menu location.
 * @param array  $fallback label => url pairs.
 */
function ms_footer_menu( $location, $fallback ) {
	if ( has_nav_menu( $location ) ) {
		add_filter( 'nav_menu_item_id', '__return_empty_string' );
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'menu_class'     => 'ms-footer__list',
				'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		remove_filter( 'nav_menu_item_id', '__return_empty_string' );
		return;
	}

	echo '<ul class="ms-footer__list">';
	foreach ( $fallback as $label => $url ) {
		printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Whether the visitor can manage the store (used for setup hints).
 *
 * @return bool
 */
function ms_is_manager() {
	return current_user_can( 'manage_options' ) || current_user_can( 'manage_woocommerce' );
}

/**
 * A headline in the brand style: it ends with a full stop.
 * (The lowercase is applied by the styles, so the stored title is untouched.)
 *
 * @param string $text Title.
 * @return string
 */
function ms_headline( $text ) {
	$text = trim( wp_strip_all_tags( (string) $text ) );
	if ( '' === $text ) {
		return '';
	}
	if ( ! preg_match( '/[.!?:]$/u', $text ) ) {
		$text .= '.';
	}
	return $text;
}

/**
 * Five stars for a rating. Uses the brand star: filled for each star earned,
 * outlined for the rest.
 *
 * @param int $rating 1 to 5.
 * @param int $size   Pixel size of one star.
 * @return string
 */
function ms_stars( $rating, $size = 20 ) {
	$rating = max( 0, min( 5, (int) $rating ) );
	$out    = '';

	for ( $i = 1; $i <= 5; $i++ ) {
		$out .= sprintf(
			'<svg class="ms-icon ms-star" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="%2$s" stroke="currentColor" stroke-width="2" stroke-linejoin="miter" aria-hidden="true" focusable="false"><path d="M12 3l2.7 5.9 6.3.7-4.7 4.3 1.3 6.3L12 17l-5.6 3.2 1.3-6.3L3 9.6l6.3-.7z"/></svg>',
			(int) $size,
			$i <= $rating ? 'currentColor' : 'none'
		);
	}

	return $out;
}

/**
 * The search form used on empty and missing pages.
 *
 * @param string $id Field id, unique on the page.
 */
function ms_search_form( $id = 'ms-search-page' ) {
	?>
	<form role="search" method="get" class="ms-search ms-search--page" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label class="screen-reader-text" for="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Search products', 'maison-square' ); ?></label>
		<input type="search" id="<?php echo esc_attr( $id ); ?>" name="s" placeholder="<?php esc_attr_e( 'Search products', 'maison-square' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
		<input type="hidden" name="post_type" value="product">
		<button type="submit" class="ms-iconbtn">
			<?php echo ms_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Search', 'maison-square' ); ?></span>
		</button>
	</form>
	<?php
}

/**
 * Picture for a name in the "we accept" and "we ship with" lists.
 * An image uploaded to Media and named "logo-" plus the name is used
 * (for example logo-aramex.png for "Aramex").
 *
 * @param string $name Name as typed in the list.
 * @return int Attachment id, or 0.
 */
function ms_mark_attachment_id( $name ) {
	$slug = 'logo-' . sanitize_title( $name );
	$map = get_transient( 'ms_mark_ids' );
	if ( ! is_array( $map ) ) {
		$map = array();
	}

	if ( ! isset( $map[ $slug ] ) ) {
		$found = get_posts(
			array(
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'post_mime_type' => 'image',
				'name' => $slug,
				'numberposts' => 1,
				'fields' => 'ids',
			)
		);

		$map[ $slug ] = $found ? (int) $found[0] : 0;
		set_transient( 'ms_mark_ids', $map, DAY_IN_SECONDS );
	}

	return (int) $map[ $slug ];
}

/**
 * Forget the stored logo lookups when pictures are added, changed or removed.
 */
function ms_mark_forget() {
	delete_transient( 'ms_mark_ids' );
}
add_action( 'add_attachment', 'ms_mark_forget' );
add_action( 'edit_attachment', 'ms_mark_forget' );
add_action( 'delete_attachment', 'ms_mark_forget' );

/**
 * Payment pictures that come with WooCommerce.
 *
 * @param string $name Name as typed in the list.
 * @return string Image address, or an empty string.
 */
function ms_mark_bundled( $name ) {
	if ( ! function_exists( 'WC' ) ) {
		return '';
	}

	$files = array(
		'visa' => 'payment-methods/visa.svg',
		'mastercard' => 'payment-methods/mastercard.svg',
		'master-card' => 'payment-methods/mastercard.svg',
		'american-express' => 'payment-methods/amex.svg',
		'amex' => 'payment-methods/amex.svg',
		'google-pay' => 'payment-methods/googlepay.svg',
		'apple-pay' => 'payment-methods/applepay.svg',
		'cash-on-delivery' => 'payment_methods/cod.svg',
	);

	$slug = sanitize_title( $name );
	if ( ! isset( $files[ $slug ] ) ) {
		return '';
	}

	$path = '/assets/images/' . $files[ $slug ];
	return is_readable( WC()->plugin_path() . $path ) ? WC()->plugin_url() . $path : '';
}

/**
 * Print a list of payment or delivery names as cards of one size:
 * an uploaded logo, a picture that comes with WooCommerce, or the name itself.
 *
 * @param string[] $names Names from the Customizer.
 */
function ms_marks( $names ) {
	if ( ! $names ) {
		return;
	}

	echo '<ul class="ms-marks">';
	foreach ( $names as $name ) {
		$id = ms_mark_attachment_id( $name );
		if ( $id ) {
			$image = wp_get_attachment_image(
				$id,
				'medium',
				false,
				array(
					'class' => 'ms-mark__img',
					'alt' => $name,
					'loading' => 'lazy',
				)
			);
			printf( '<li class="ms-mark ms-mark--logo" title="%1$s">%2$s</li>', esc_attr( $name ), $image ); // phpcs:ignore WordPress.Security.EscapeOutput
			continue;
		}

		$src = ms_mark_bundled( $name );
		if ( $src ) {
			// WooCommerce's card pictures fill the card, its square pictures sit in the middle of one.
			printf(
				'<li class="ms-mark %3$s" title="%2$s"><img class="ms-mark__img" src="%1$s" alt="%2$s" width="64" height="40" loading="lazy"></li>',
				esc_url( $src ),
				esc_attr( $name ),
				false !== strpos( $src, '/payment_methods/' ) ? 'ms-mark--icon' : 'ms-mark--card'
			);
			continue;
		}

		echo '<li class="ms-mark"><span class="ms-mark__name">' . esc_html( $name ) . '</span></li>';
	}
	echo '</ul>';
}

/**
 * Address that opens a WhatsApp chat with the store, or an empty string.
 *
 * @return string
 */
function ms_whatsapp_url() {
	$number = preg_replace( '/[^0-9]/', '', (string) ms_opt( 'whatsapp' ) );
	return $number ? 'https://wa.me/' . $number : '';
}

/**
 * The WhatsApp button shown in the corner of every page.
 */
function ms_whatsapp_button() {
	$url = ms_whatsapp_url();
	if ( '' === $url ) {
		return;
	}

	// WordPress comes with the icons of the social services. Use its WhatsApp one.
	$icon = function_exists( 'block_core_social_link_get_icon' ) ? (string) block_core_social_link_get_icon( 'whatsapp' ) : '';
	if ( '' === trim( $icon ) ) {
		$icon = ms_icon( 'chat', 28 );
	}

	printf(
		'<a class="ms-whatsapp" href="%1$s" target="_blank" rel="noopener" aria-label="%2$s" title="%2$s">%3$s</a>',
		esc_url( $url ),
		esc_attr__( 'Chat with us on WhatsApp', 'maison-square' ),
		$icon // phpcs:ignore WordPress.Security.EscapeOutput
	);
}
