<?php
/**
 * Home page: banner, categories, new arrivals, services and customer reviews.
 *
 * @package MaisonSquare
 */

defined( 'ABSPATH' ) || exit;

get_header();

$ms_has_woo = function_exists( 'WC' );

// Banners come from the Banners screen. Without any, the default headline is used.
$ms_banners = ms_get_banners();
if ( ! $ms_banners ) {
	$ms_banners = array(
		array(
			'title'  => (string) ms_opt( 'hero_title' ),
			'text'   => (string) ms_opt( 'hero_text' ),
			'button' => (string) ms_opt( 'hero_button' ),
			'url'    => ms_new_arrivals_url(),
			'image'  => 0,
		),
	);
}
$ms_many = count( $ms_banners ) > 1;

// With a photo, the banner runs across the whole page.
$ms_photo = ! empty( $ms_banners[0]['image'] );
?>

<main id="main" class="ms-main ms-home">

	<section class="ms-hero<?php echo $ms_photo ? ' ms-hero--photo' : ''; ?>"<?php echo $ms_many ? ' data-ms-banners' : ''; ?>>
		<div class="ms-container ms-hero__grid">
			<div class="ms-hero__copy">
				<?php foreach ( $ms_banners as $ms_i => $ms_banner ) : ?>
					<div class="ms-hero__slide<?php echo 0 === $ms_i ? ' is-active' : ''; ?>" data-ms-slide="<?php echo (int) $ms_i; ?>"<?php echo 0 === $ms_i ? '' : ' hidden'; ?>>
						<?php if ( 0 === $ms_i ) : ?>
							<h1 class="ms-hero__title"><?php echo esc_html( $ms_banner['title'] ); ?></h1>
						<?php else : ?>
							<p class="ms-hero__title"><?php echo esc_html( $ms_banner['title'] ); ?></p>
						<?php endif; ?>

						<?php if ( '' !== trim( $ms_banner['text'] ) ) : ?>
							<p class="ms-hero__text"><?php echo esc_html( $ms_banner['text'] ); ?></p>
						<?php endif; ?>

						<?php if ( '' !== trim( $ms_banner['button'] ) && '' !== trim( $ms_banner['url'] ) ) : ?>
							<a class="ms-button" href="<?php echo esc_url( $ms_banner['url'] ); ?>"><?php echo esc_html( $ms_banner['button'] ); ?></a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>

				<?php if ( $ms_many ) : ?>
					<div class="ms-hero__picker" role="group" aria-label="<?php esc_attr_e( 'Banners', 'maison-square' ); ?>">
						<?php foreach ( $ms_banners as $ms_i => $ms_banner ) : ?>
							<button type="button" class="ms-hero__dot<?php echo 0 === $ms_i ? ' is-active' : ''; ?>" data-ms-goto="<?php echo (int) $ms_i; ?>" aria-pressed="<?php echo 0 === $ms_i ? 'true' : 'false'; ?>">
								<span class="screen-reader-text">
									<?php
									/* translators: %d: banner number */
									printf( esc_html__( 'Show banner %d', 'maison-square' ), (int) $ms_i + 1 );
									?>
								</span>
							</button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="ms-hero__visual">
				<?php foreach ( $ms_banners as $ms_i => $ms_banner ) : ?>
					<div class="ms-hero__media<?php echo 0 === $ms_i ? ' is-active' : ''; ?>" data-ms-media="<?php echo (int) $ms_i; ?>"<?php echo 0 === $ms_i ? '' : ' hidden'; ?>>
						<?php if ( $ms_banner['image'] ) : ?>
							<div class="ms-frame">
								<?php
								echo wp_get_attachment_image(
									$ms_banner['image'],
									$ms_photo ? 'full' : 'ms-square',
									false,
									array(
										'loading'       => 0 === $ms_i ? 'eager' : 'lazy',
										'fetchpriority' => 0 === $ms_i ? 'high' : 'auto',
										'sizes'         => $ms_photo ? '100vw' : '(min-width: 900px) 44vw, 92vw',
									)
								);
								?>
							</div>
						<?php else : ?>
							<img class="ms-hero__symbol" src="<?php echo esc_url( MS_THEME_URI . '/assets/img/symbol-color.svg' ); ?>" width="560" height="560" alt="">
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php
	// Categories.
	$ms_cats = ms_categories( 8 );
	if ( $ms_cats ) :
		?>
		<section class="ms-section ms-cats">
			<div class="ms-container">
				<div class="ms-section__head">
					<h2 class="ms-h2"><?php esc_html_e( 'shop by category.', 'maison-square' ); ?></h2>
				</div>
				<ul class="ms-tiles">
					<?php
					foreach ( $ms_cats as $ms_term ) :
						$ms_link = get_term_link( $ms_term );
						if ( is_wp_error( $ms_link ) ) {
							continue;
						}
						$ms_thumb = (int) get_term_meta( $ms_term->term_id, 'thumbnail_id', true );
						?>
						<li>
							<a class="ms-tile" href="<?php echo esc_url( $ms_link ); ?>">
								<span class="ms-tile__square">
									<?php
									if ( $ms_thumb ) {
										echo wp_get_attachment_image( $ms_thumb, 'ms-square', false, array( 'alt' => '' ) );
									} else {
										echo ms_icon( ms_category_icon( $ms_term ), 48 ); // phpcs:ignore WordPress.Security.EscapeOutput
									}
									?>
								</span>
								<span class="ms-tile__name"><?php echo esc_html( $ms_term->name ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php elseif ( $ms_has_woo && ms_is_manager() ) : ?>
		<section class="ms-section ms-cats">
			<div class="ms-container">
				<div class="ms-section__head">
					<h2 class="ms-h2"><?php esc_html_e( 'shop by category.', 'maison-square' ); ?></h2>
				</div>
				<p class="ms-hint">
					<?php esc_html_e( 'Only you can see this note. Your categories appear here and in the menu as soon as you add them.', 'maison-square' ); ?>
					<a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=product_cat&post_type=product' ) ); ?>"><?php esc_html_e( 'Add categories', 'maison-square' ); ?></a>
				</p>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// New arrivals.
	if ( $ms_has_woo ) :
		$ms_count = max( 4, min( 16, (int) ms_opt( 'arrivals_count' ) ) );
		$ms_new   = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => $ms_count,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'no_found_rows'  => true,
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery
					array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => array( 'exclude-from-catalog' ),
						'operator' => 'NOT IN',
					),
				),
			)
		);
		?>
		<section class="ms-section ms-arrivals">
			<div class="ms-container">
				<div class="ms-section__head">
					<h2 class="ms-h2"><?php esc_html_e( 'new arrivals.', 'maison-square' ); ?></h2>
					<?php if ( $ms_new->have_posts() ) : ?>
						<a class="ms-link" href="<?php echo esc_url( ms_new_arrivals_url() ); ?>"><?php esc_html_e( 'See all new arrivals', 'maison-square' ); ?></a>
					<?php endif; ?>
				</div>

				<?php if ( $ms_new->have_posts() ) : ?>
					<div class="woocommerce">
						<?php
						wc_set_loop_prop( 'columns', 4 );
						woocommerce_product_loop_start();
						while ( $ms_new->have_posts() ) {
							$ms_new->the_post();
							wc_get_template_part( 'content', 'product' );
						}
						woocommerce_product_loop_end();
						wp_reset_postdata();
						wc_reset_loop();
						?>
					</div>
				<?php else : ?>
					<p class="ms-empty"><?php esc_html_e( 'The first pieces are being unpacked. Come back soon.', 'maison-square' ); ?></p>
					<?php if ( ms_is_manager() ) : ?>
						<p class="ms-hint">
							<?php esc_html_e( 'Only you can see this note. The newest products appear here automatically.', 'maison-square' ); ?>
							<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=product' ) ); ?>"><?php esc_html_e( 'Add a product', 'maison-square' ); ?></a>
						</p>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// Services.
	$ms_services = array(
		array( 'delivery', (string) ms_opt( 'service_1_title' ), (string) ms_opt( 'service_1_text' ) ),
		array( 'location', (string) ms_opt( 'service_2_title' ), (string) ms_opt( 'service_2_text' ) ),
		array( 'payment', (string) ms_opt( 'service_3_title' ), (string) ms_opt( 'service_3_text' ) ),
	);
	$ms_services = array_filter(
		$ms_services,
		static function ( $ms_service ) {
			return '' !== trim( $ms_service[1] );
		}
	);
	if ( $ms_services ) :
		?>
		<section class="ms-services" aria-label="<?php esc_attr_e( 'Delivery and payment', 'maison-square' ); ?>">
			<div class="ms-container">
				<ul class="ms-services__list">
					<?php foreach ( $ms_services as $ms_service ) : ?>
						<li class="ms-service">
							<?php echo ms_icon( $ms_service[0], 32 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div>
								<h2 class="ms-service__title"><?php echo esc_html( $ms_service[1] ); ?></h2>
								<?php if ( '' !== trim( $ms_service[2] ) ) : ?>
									<p class="ms-service__text"><?php echo esc_html( $ms_service[2] ); ?></p>
								<?php endif; ?>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// Customer reviews. Shown only when real, approved reviews exist.
	$ms_reviews = function_exists( 'ms_get_reviews' ) ? ms_get_reviews( 3 ) : array();
	if ( $ms_reviews ) :
		?>
		<section class="ms-section ms-reviews">
			<div class="ms-container">
				<div class="ms-section__head">
					<h2 class="ms-h2"><?php esc_html_e( 'what customers say.', 'maison-square' ); ?></h2>
				</div>
				<ul class="ms-reviews__list">
					<?php
					foreach ( $ms_reviews as $ms_review ) :
						$ms_rating = max( 1, min( 5, (int) get_comment_meta( $ms_review->comment_ID, 'rating', true ) ) );
						$ms_name   = (string) strtok( trim( (string) $ms_review->comment_author ), ' ' );
						?>
						<li class="ms-review">
							<p class="ms-review__stars">
								<?php echo ms_stars( $ms_rating ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<span class="screen-reader-text">
									<?php
									/* translators: %d: number of stars */
									printf( esc_html__( '%d out of 5 stars', 'maison-square' ), (int) $ms_rating );
									?>
								</span>
							</p>
							<blockquote class="ms-review__text"><?php echo esc_html( wp_trim_words( $ms_review->comment_content, 36 ) ); ?></blockquote>
							<p class="ms-review__by">
								<strong><?php echo esc_html( $ms_name ); ?></strong>
								<a href="<?php echo esc_url( get_permalink( $ms_review->comment_post_ID ) ); ?>"><?php echo esc_html( get_the_title( $ms_review->comment_post_ID ) ); ?></a>
							</p>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php
get_footer();
