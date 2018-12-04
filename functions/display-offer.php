<?php

/**
 * Set variables
 */
$price = 0;

/**
 * Output single offer
 */
function offer_generator_display_offer($post) {

    /**
     * Enque plugins styles and scripts in head if theme doesnt say it provides them
     */
    if ( !current_theme_supports('offer_generator') ) {
        wp_enqueue_style( 'offer-generator-styles', OFFER_GENERATOR_PLUGIN_URL . 'styles/offer-generator-styles.css' );
        wp_enqueue_script( 'offer-generator-scripts', OFFER_GENERATOR_PLUGIN_URL . 'scripts/offer-generator-scripts.js', array(), false, true );
    }

    $post_id = $post->ID;
    $selected_services = get_post_meta($post_id, 'selected_services', true);
    $total_price = 0;
    $recurring_price = 0;
    $expiration_date = date('j.n.Y', strtotime("+14 day"));
    $delivery_method = get_option('offer_generator_delivery_method');
    $payment_terms = get_option('offer_generator_payment_terms');
    $other_terms = get_option('offer_generator_other_terms');

    function service_description($post) {
        return '<div class="service-description"><h3>'.get_post($post)->post_title.'</h3>'.wpautop(get_post($post)->post_content).'</div>';
    }

    echo '<h1>'.get_post_meta($post->ID, 'offer_title', true).'</h1><hr>';
    echo '<span class="client">Asiakas: '.get_the_title().'</span>';
    ?>

    <time class="entry-date updated" datetime="<?php echo get_post_time('c'); ?>"><?php echo get_the_date(); ?></time>

    <div class="entry-content">
        <?php
        echo '<div class="content">'.get_the_content().'</div>';
        echo '<div class="info-text color-gray-medium">'.get_option('offer_generator_text_under_content').'</div>';
        
        // One time services
        echo '<div class="one-time-services">';
        echo '<h4 class="sans">'.__('Non-recurring charges:', 'offer-generator').'</h4>';
        foreach ($selected_services as $key => $post) {
            if ( get_post_meta($post, 'is_recurring', true) != 1 ) {
                echo '<div>';
                echo '<span class="service-name">'.get_post($post)->post_title.'</span> ';
                echo '<span class="service-price">'.get_post_meta($post, 'service_price', true).'</span>';
                echo '</div>';
                $total_price = $total_price + get_post_meta($post, 'service_price', true);
            }
        }
        echo '<hr><strong>'.__('Non-recurring charges total:', 'offer-generator').'</strong> ';
        echo '<strong class="total-price">'.$total_price.' € + alv.</strong>';
        echo '</div>';


        // Continuous services
        echo '<div class="recurring-services">';
        echo '<h4 class="sans">'.__('Recurring charges:', 'offer-generator').'</h4>';
        foreach ($selected_services as $key => $post) {
            if ( get_post_meta($post, 'is_recurring', true) == 1 ) {
                echo '<div>';
                echo '<span class="service-name">'.get_post($post)->post_title.'</span> ';
                echo '<span class="service-price">'.get_post_meta($post, 'service_price', true).'</span>';
                echo '</div>';
                $recurring_price = $recurring_price + get_post_meta($post, 'service_price', true);
            }
        }
        echo '<hr><strong>'.__('Recurring charges total:', 'offer-generator').'</strong> ';
        echo '<strong class="recurring-price">'.$recurring_price.' € + alv.</strong>';
        echo '</div>';

        ?>
        <div class="grid nested offer-terms">
            <div class="col-3_sm-6"><?php _e('Delivery method:', 'offer-generator'); ?></div>
            <div class="col-9_sm-6"><?php echo $delivery_method; ?></div>
            <div class="col-3_sm-6"><?php _e('Payment terms:', 'offer-generator'); ?></div>
            <div class="col-9_sm-6"><?php echo $payment_terms; ?></div>
            <div class="col-3_sm-6"><?php _e('Offer valid until:', 'offer-generator'); ?></div>
            <div class="col-9_sm-6"><?php echo $expiration_date; ?></div>
            <div class="col-3_sm-12"><?php _e('Other terms:', 'offer-generator'); ?></div>
            <div class="col-9_sm-12"><?php echo wpautop($other_terms); ?></div>
            <div class="col-3_sm-12"><?php _e('Contact person:', 'offer-generator'); ?></div>
            <div class="col-9_sm-12"><?php echo get_the_author().'<br>'.get_the_author_meta('user_email').'<br>'.get_the_author_meta('phone')?></div>
        </div>
        <?php

        // Service descriptions
        echo '<div class="service-descriptions">';
        echo '<h2>'.__('Service descriptions', 'offer-generator').'</h2><hr>';
        // Non-recurring services descriptions
        foreach ($selected_services as $key => $post) {
            if ( get_post_meta($post, 'is_recurring', true) != 1 ) {
                echo service_description($post);
            }
        }
        // Recurring services descriptions
        foreach ($selected_services as $key => $post) {
            if ( get_post_meta($post, 'is_recurring', true) == 1 ) {
                echo service_description($post);
            }
        }
        ?>
    </div>

    <div id="offer-footer">
    <button onclick="window.print();"><?php _e('Print Offer', 'offer-generator'); ?></button>
    <button id="offer-accept"><?php _e('Accept Offer', 'offer-generator'); ?></button>
    <div id="offer-accept-content"><?php echo do_shortcode(get_option('offer_generator_accept_offer_content')); ?></div>
    </div>

<?php
    //echo $output;
}






