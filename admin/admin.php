<?php
/**
 * Admin
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Add menu item to wp-admin
 */
function offer_generator_admin_menu() {

	$offer_generator_options_page = add_options_page(
		__('Offer Generator Settings', 'offer-generator'),
		__('Offer Generator', 'offer-generator'),
		'manage_options',
		'offer-generator',
		'offer_generator_settings_page'
		);

}
add_action( 'admin_menu', 'offer_generator_admin_menu' );


function offer_generator_settings_page() {
    ?>
        <div class="wrap">
            <h1>Offer Generator</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields("offer-generator-section");
                do_settings_sections("offer-generator");      
                submit_button(); 
                ?>          
            </form>
        </div>
    <?php
}


/**
 * Create settings page
 */
function display_offer_generator_fields() {

    add_settings_section("offer-generator-section", "", null, "offer-generator");

    add_settings_field("offer_generator_text_under_content", "Text under content", "display_offer_generator_text_under_content_element", "offer-generator", "offer-generator-section");
    register_setting("offer-generator-section", "offer_generator_text_under_content");

    add_settings_field("offer_generator_delivery_method", "Delivery method", "display_offer_generator_delivery_method_element", "offer-generator", "offer-generator-section");
    register_setting("offer-generator-section", "offer_generator_delivery_method");

    add_settings_field("offer_generator_payment_terms", "Payment Terms", "display_offer_generator_payment_terms_element", "offer-generator", "offer-generator-section");
    register_setting("offer-generator-section", "offer_generator_payment_terms");

    add_settings_field("offer_generator_other_terms", "Other Terms", "display_offer_generator_other_terms_element", "offer-generator", "offer-generator-section");
    register_setting("offer-generator-section", "offer_generator_other_terms");

    add_settings_field("offer_generator_accept_offer_content", "Content to show when Accept Offer clicked", "display_offer_generator_accept_offer_content_element", "offer-generator", "offer-generator-section");
    register_setting("offer-generator-section", "offer_generator_accept_offer_content");

    //add_settings_field("offer_generator_visibility", "Show share icons on", "display_offer_generator_options_element", "offer-generator", "offer-generator-section");
    //register_setting("offer-generator-section", "offer_generator_visibility");

}
add_action("admin_init", "display_offer_generator_fields");


/**
 * Create elements
 */
function display_offer_generator_text_under_content_element() {
    echo '<input type="text" name="offer_generator_text_under_content" style="width: 100%;" value="' . esc_attr(get_option('offer_generator_text_under_content')) . '" />';
}
function display_offer_generator_delivery_method_element() {
    echo '<input type="text" name="offer_generator_delivery_method" style="width: 100%;" value="' . esc_attr(get_option('offer_generator_delivery_method')) . '" />';
}
function display_offer_generator_payment_terms_element() {
    echo '<input type="text" name="offer_generator_payment_terms" style="width: 100%;" value="' . esc_attr(get_option('offer_generator_payment_terms')) . '" />';
}
function display_offer_generator_other_terms_element() {
    echo '<textarea name="offer_generator_other_terms" rows="10" style="width: 100%;">' . esc_attr(get_option('offer_generator_other_terms')) . '</textarea>';
}
function display_offer_generator_accept_offer_content_element() {
    echo '<textarea name="offer_generator_accept_offer_content" rows="10" style="width: 100%;">' . esc_attr(get_option('offer_generator_accept_offer_content')) . '</textarea>';
}
// function display_offer_generator_options_element() {
//     $offer_generator_visibility_options = get_option('offer_generator_visibility');
//     echo '<input type="checkbox" name="offer_generator_visibility[posts]" value="1" ' . checked( 1, isset( $offer_generator_visibility_options['posts'] ), false ) . ' /> Posts<br />';
//     echo '<input type="checkbox" name="offer_generator_visibility[pages]" value="1" ' . checked( 1, isset( $offer_generator_visibility_options['pages'] ), false ) . ' /> Pages<br />';
//     echo '<input type="checkbox" name="offer_generator_visibility[print_css]" value="1" ' . checked( 1, isset( $offer_generator_visibility_options['print_css'] ), false ) . ' /> Print CSS included in plugin<br />';
// }
