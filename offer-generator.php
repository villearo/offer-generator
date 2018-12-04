<?php
/*
Plugin Name:    Offer Generator
Plugin URI:     https://github.com/villearo/offer-generator
Description:    Custom post type for making offers
Version:        1.0.0
Author:         Ville Aro
Author URI:     https://jouhea-kotisivut.fi/
Text Domain:    offer-generator
Domain Path:    /languages
License:        GPLv2 or later
License URI:    http://www.gnu.org/licenses/gpl-2.0.html
*/


/**
 * Load plugin textdomain
 */
function offer_generator_load_textdomain() {
  load_plugin_textdomain( 'offer-generator', false, 'offer-generator/languages' ); 
}
add_action( 'plugins_loaded', 'offer_generator_load_textdomain' );


/**
 * Global variables
 */
$plugin_file = plugin_basename(__FILE__);                             // plugin file for reference
define( 'OFFER_GENERATOR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );   // define the absolute plugin path for includes
define( 'OFFER_GENERATOR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );     // define the plugin url for use in enqueue


/**
 * Includes
 */
include( OFFER_GENERATOR_PLUGIN_PATH . 'functions/activation.php' );
include( OFFER_GENERATOR_PLUGIN_PATH . 'functions/deactivation.php' );
include( OFFER_GENERATOR_PLUGIN_PATH . 'admin/admin.php' );
include( OFFER_GENERATOR_PLUGIN_PATH . 'functions/meta-boxes.php' );
include( OFFER_GENERATOR_PLUGIN_PATH . 'functions/display-offer.php' );
include( OFFER_GENERATOR_PLUGIN_PATH . 'functions/shortcode.php' );


// /**
//  * Use archive template shipped with plugin
//  */
// function get_custom_archive_template( $archive_template ) {
//     global $post;
//     if ( is_post_type_archive( 'offers' ) ) {
//          $archive_template = OFFER_GENERATOR_PLUGIN_PATH . '/templates/archive-offers.php';
//     }
//     return $archive_template;
// }
// add_filter( 'archive_template', 'get_custom_archive_template' );


/**
 * Use single template shipped with plugin
 */
// function get_custom_single_template( $single_template ) {
//     global $post;
//     if ( $post->post_type == 'offers' ) {
//          $single_template = OFFER_GENERATOR_PLUGIN_PATH . '/templates/single-offer.php';
//     }
//     return $single_template;
// }
// add_filter( 'single_template', 'get_custom_single_template' );


/**
 * Filter single post content to show offer fields
 */
function offer_generator_filter_the_content( $content ) {
 	global $post;
    if ( is_single() && $post->post_type == 'offer' ) {
        //return $content . "I'm filtering the content inside the main loop";
        //include(OFFER_GENERATOR_PLUGIN_PATH . '/templates/single-offer.php');
        offer_generator_display_offer($post);
    } else {
    	return $content;
    }
}
add_filter( 'the_content', 'offer_generator_filter_the_content' );


/**
 * Randomize Slugs
 */
function custom_unique_post_slug( $slug, $post_ID, $post_status, $post_type ) {
    if ( $post_type == 'offer' ) {
        $post = get_post($post_ID);
        if ( empty($post->post_name) || $slug != $post->post_name ) {
            $slug = md5( time() );
        }
    }
    return $slug;
}
add_filter( 'wp_unique_post_slug', 'custom_unique_post_slug', 10, 4 );








function offer_generator_show_extra_profile_fields( $user ) { ?>
<h3>Extra info for Offer Generator Plugin</h3>
<table class="form-table">
	<tr>
	    <th>
	    	<label for="phone">Phone Number</label>
	    </th>
	    <td>
	    	<input type="text" name="phone" id="phone" value="<?php echo esc_attr( get_the_author_meta( 'phone', $user->ID ) ); ?>" class="regular-text" /><br />
	        <p class="description">Please enter your phone number.</p>
	    </td>
	</tr>
</table>
<?php }
add_action( 'show_user_profile', 'offer_generator_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'offer_generator_show_extra_profile_fields' );



function offer_generator_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
	    return false;
	update_usermeta( $user_id, 'phone', $_POST['phone'] );
}
add_action( 'personal_options_update', 'offer_generator_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'offer_generator_save_extra_profile_fields' );






