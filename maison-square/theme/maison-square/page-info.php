<?php
/**
 * The page at /info. The QR codes on the packaging and stationery point here,
 * so it shows who Maison Square is and every way to reach the store.
 *
 * @package MaisonSquare
 */

defined( 'ABSPATH' ) || exit;

get_header();

$ms_social = ms_social_links();
$ms_lines  = ms_contact_lines();
$ms_note   = trim( (string) ms_opt( 'footer_note' ) );
?>

<main id="main" class="ms-main ms-container ms-info">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'ms-info__card' ); ?>>
			<img class="ms-info__symbol" src="<?php echo esc_url( MS_THEME_URI . '/assets/img/symbol-color.svg' ); ?>" width="120" height="120" alt="">
			<span class="ms-yline" aria-hidden="true"></span>

			<div class="ms-info__details">
				<h1 class="ms-pagetitle"><?php echo esc_html( ms_headline( get_the_title() ) ); ?></h1>

				<?php if ( '' !== $ms_note ) : ?>
					<p class="ms-info__note"><?php echo esc_html( $ms_note ); ?></p>
				<?php endif; ?>

				<?php ms_contact_list( 'ms-contact ms-contact--info' ); ?>

				<?php if ( $ms_social ) : ?>
					<ul class="ms-social ms-social--info">
						<?php foreach ( $ms_social as $ms_label => $ms_url ) : ?>
							<li><a href="<?php echo esc_url( $ms_url ); ?>" rel="noopener"><?php echo esc_html( $ms_label ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( '' !== trim( get_the_content() ) ) : ?>
					<div class="ms-content">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>

				<p class="ms-info__actions">
					<a class="ms-button" href="<?php echo esc_url( ms_shop_url() ); ?>"><?php esc_html_e( 'Visit the shop', 'maison-square' ); ?></a>
				</p>

				<?php if ( ms_is_manager() && count( $ms_lines ) < 3 ) : ?>
					<p class="ms-hint">
						<?php esc_html_e( 'Only you can see this note. Add the phone, WhatsApp, address and opening hours and they appear here, in the footer and in the menu.', 'maison-square' ); ?>
						<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=ms_contact' ) ); ?>"><?php esc_html_e( 'Add contact details', 'maison-square' ); ?></a>
					</p>
				<?php endif; ?>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
