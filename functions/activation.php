<?php

function offers_setup_post_type() {

	$offer_args = array(
		'labels' => array(
			'name' => __('Offers', 'offer-generator'),
			'singular_name' => __('Offer', 'offer-generator'),
			'all_items' => __('All Offers', 'offer-generator'),
			'add_new_item' => __('Add New Offer', 'offer-generator'),
			'edit_item' => __('Edit Offer', 'offer-generator'),
			'view_item' => __('View Offer', 'offer-generator')
		),
		'public' => true,
		'publicly_queryable' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => __('offers', 'offer-generator')),
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'capability_type' => 'page',
		//'supports' => array('title', 'excerpt', 'thumbnail', 'page-attributes'),
		'exclude_from_search' => true,
		'menu_position' => 30,
		'menu_icon' => 'dashicons-clipboard'
	);
	
	// https://codex.wordpress.org/Function_Reference/register_post_type
	register_post_type('offer', $offer_args);


	$service_args = array(
		'labels' => array(
			'name' => __('Services', 'offer-generator'),
			'singular_name' => __('Service', 'offer-generator'),
			'all_items' => __('All Services', 'offer-generator'),
			'add_new_item' => __('Add New Service', 'offer-generator'),
			'edit_item' => __('Edit Service', 'offer-generator'),
			'view_item' => __('View Service', 'offer-generator')
		),
		'public' => false,
		//'publicly_queryable' => false,
		'has_archive' => false,
		//'rewrite' => array('slug' => __('offer-parts', 'offer-generator')),
		'show_ui' => true,
		'show_in_menu' => 'edit.php?post_type=offer',
		//'show_in_nav_menus' => true,
		//'show_in_admin_bar' => true,
		//'capability_type' => 'page',
		'supports' => array('title', 'editor', 'page-attributes'),
		//'exclude_from_search' => true,
		//'menu_position' => 20,
		//'menu_icon' => 'dashicons-clipboard'
	);

	// https://codex.wordpress.org/Function_Reference/register_post_type
	register_post_type('service', $service_args);


}
add_action( 'init', 'offers_setup_post_type' );





function offer_generator_install() {

    // trigger our function that registers the custom post type
    offers_setup_post_type();
 
    // clear the permalinks after the post type has been registered
    flush_rewrite_rules();

}
register_activation_hook( __FILE__, 'offer_generator_install' );
