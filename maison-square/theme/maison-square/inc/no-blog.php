<?php
/**
 * The store has no blog. Hide the blog screens and send blog addresses home.
 *
 * @package MaisonSquare
 */

defined( 'ABSPATH' ) || exit;

/**
 * Send blog pages, posts and post archives to the home page.
 */
function ms_no_blog_redirect() {
	$is_posts_page = is_home() && ! is_front_page();
	$is_post       = is_singular( 'post' );
	$is_archive    = is_category() || is_tag() || is_date() || is_author();

	if ( $is_posts_page || $is_post || $is_archive ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}

	// Every search uses the product results page.
	if ( is_search() && post_type_exists( 'product' ) && ! isset( $_GET['post_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		wp_safe_redirect(
			add_query_arg(
				array(
					's'         => rawurlencode( get_search_query( false ) ),
					'post_type' => 'product',
				),
				home_url( '/' )
			),
			302
		);
		exit;
	}
}
add_action( 'template_redirect', 'ms_no_blog_redirect' );

/**
 * Search only finds products.
 *
 * @param WP_Query $query Query.
 */
function ms_search_products_only( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}
	if ( post_type_exists( 'product' ) ) {
		$query->set( 'post_type', 'product' );
	}
}
add_action( 'pre_get_posts', 'ms_search_products_only' );

/**
 * Remove the Posts screen from the admin menu.
 */
function ms_no_blog_admin_menu() {
	remove_menu_page( 'edit.php' );
}
add_action( 'admin_menu', 'ms_no_blog_admin_menu' );

/**
 * Remove "New post" from the admin bar.
 *
 * @param WP_Admin_Bar $bar Admin bar.
 */
function ms_no_blog_admin_bar( $bar ) {
	$bar->remove_node( 'new-post' );
}
add_action( 'admin_bar_menu', 'ms_no_blog_admin_bar', 999 );

/**
 * Posts take no comments. Product reviews are not affected.
 */
function ms_no_blog_post_support() {
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'post', 'trackbacks' );
}
add_action( 'init', 'ms_no_blog_post_support' );

/**
 * Tidy the dashboard: no quick draft, no WordPress news.
 */
function ms_no_blog_dashboard() {
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', 'ms_no_blog_dashboard' );

// No blog feeds in the page head.
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
