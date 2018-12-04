<?php

function offer_generator_add_meta_boxes() {
    add_meta_box(
        'offer-details',                                // The HTML id attribute for the metabox section
        __('Offer Details', 'offer-generator'),        // The title of metabox section
        'offer_generator_offer_meta_box_callback',      // The metabox callback function
        'offer',                                       // Your custom post type slug
        'normal',                                       // Position can be 'normal', 'side', and 'advanced'
        'default'                                       // Priority can be 'high' or 'low'
    );
    add_meta_box(
        'service-details',                              // The HTML id attribute for the metabox section
        __('Service Details', 'offer-generator'),       // The title of metabox section
        'offer_generator_service_meta_box_callback',    // The metabox callback function
        'service',                                     // Your custom post type slug
        'normal',                                       // Position can be 'normal', 'side', and 'advanced'
        'default'                                       // Priority can be 'high' or 'low'
    );
}
add_action( 'add_meta_boxes', 'offer_generator_add_meta_boxes' );


function offer_generator_offer_meta_box_callback( $post ) {
    wp_nonce_field( 'offer_generator_nonce_action', 'offer_generator_nonce' );
    $post_id = $post->ID;
    $offer_title = isset( get_post_custom($post_id)['offer_title'] ) ? esc_attr( get_post_custom($post_id)['offer_title'][0] ) : __('Tarjous verkkosivuston suunnittelusta, toteutuksesta ja ylläpidosta', 'offer-generator');
    $selected_services = isset( get_post_custom($post_id)['selected_services'] ) ? maybe_unserialize(get_post_custom($post_id)['selected_services'][0]) : array();

    echo '<label>' . __('Offer Title', 'offer-generator') . '</label><br/><input type="text" name="offer_title" id="offer_title" size="100" value="'. $offer_title .'" /><br/><br/>';

    // Get all Available Services
    $args = array(
        'numberposts' => -1,
        'post_type'   => 'service',
        'order'       => 'ASC',
        'orderby'     => 'menu_order'
    );
    $available_services = get_posts( $args );

    // Loop trough services
    foreach($available_services as $key => $post) {
        $post_id = $post->ID;
        if ( is_array( $selected_services ) && in_array( $post_id, $selected_services ) ) {
            $is_selected = 1;
        } else {
            $is_selected = 0;
        }
        echo '<input type="checkbox" name="selected_services[]" value="'.$post_id.'" ' . checked( 1, $is_selected, false ) . ' /><label for="service_'.$post_id.'">'.$post->post_title.'</label><br>';
    }

}


function offer_generator_service_meta_box_callback( $post ) {
    wp_nonce_field( 'offer_generator_nonce_action', 'offer_generator_nonce' );
    $post_id = $post->ID;
    $service_price = isset( get_post_custom($post_id)['service_price'] ) ? esc_attr( get_post_custom($post_id)['service_price'][0] ) : "";
    
    echo '<label>' . __('Service Price', 'offer-generator') . '</label><br/><input type="number" name="service_price" id="service_price" size="100" value="'. $service_price .'" /><br/><br/>';
    echo '<input type="checkbox" name="is_recurring" value="1" ' . checked( 1, isset( get_post_custom($post_id)['is_recurring'] ), false ) . ' /><label for="is_recurring">'. __('Recurring service', 'offer-generator') .'</label><br>';

}


function offer_generator_save_meta_box( $post_id ) {

    // Check if our nonce is set.
    if ( ! isset( $_POST['offer_generator_nonce'] ) ) {
        return;
    }

    $nonce = $_POST['offer_generator_nonce'];

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'offer_generator_nonce_action' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Selected Services field
    if ( isset($_POST['selected_services']) && $_POST['selected_services'] != "" ) {
        update_post_meta( $post_id, 'selected_services', $_POST['selected_services'] );
    } else {
        delete_post_meta( $post_id, 'selected_services' );
    }

    // Offer Title field
    if( isset( $_POST['offer_title'] ) ) {
        update_post_meta( $post_id, 'offer_title', $_POST['offer_title']);
    }

    // Service Price field
    if( isset( $_POST['service_price'] ) ) {
        update_post_meta( $post_id, 'service_price', $_POST['service_price']);
    }

    // Recurring field
    if ( isset($_POST['is_recurring']) && $_POST['is_recurring'] != "" ) {
        update_post_meta( $post_id, 'is_recurring', $_POST['is_recurring'] );
    } else {
        delete_post_meta( $post_id, 'is_recurring' );
    }

}
add_action( 'save_post', 'offer_generator_save_meta_box' );
