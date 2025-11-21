<?php
/**
 * Plugin Name:  Sample Plugin
 * Description: Testing a simple plugin with Gravity Forms webhook integration
 * Version: 1.1.0
 * Author: Ronaldo
 * Author URI: https://ryne.sblik.com
 * Plugin URI: https://ryne.sblik.com
 */

// -------------------------
// Quote Functions
// -------------------------

function fcGetSuccesQuotePhrase(): string {
    $quotes = [
        "Success is not final; failure is not fatal: It is the courage to continue that counts.",
        "The most certain way to succeed is always to try just one more time.",
        "Success is the sum of small efforts, repeated day in and day out.",
        "If there is no struggle, there is no progress.",
        "Success is how high you bounce when you hit bottom.",
        "The successful man will profit from his mistakes and try again in a different way."
    ];

    return wptexturize($quotes[array_rand($quotes)]);
}

function showQuotePhrase(): void {
    echo "<div class='notice notice-info is-dismissible'><p id='koi-quote'>".fcGetSuccesQuotePhrase()."</p></div>";
}
add_action('admin_notices', 'showQuotePhrase');

// -------------------------
// Enqueue CSS & JS
// -------------------------

function fc_enqueue_assets() {
    // Register and enqueue CSS
    wp_enqueue_style(
        'fc-plugin-style',
        plugin_dir_url(__FILE__) . 'assets/css/fc-style.css',
        [],
        '1.0.0'
    );

    // Register and enqueue JS
    wp_enqueue_script(
        'fc-plugin-script',
        plugin_dir_url(__FILE__) . 'assets/js/fc-script.js',
        ['jquery'],
        '1.0.0',
        true
    );
}
add_action('admin_enqueue_scripts', 'fc_enqueue_assets');

// -------------------------
// Gravity Forms Webhook (Form ID = 2)
// -------------------------

add_action('gform_after_submission_2', 'fc_after_submission', 10, 2);

function fc_after_submission($entry, $form) {
    $payload = [
        // Name field (ID 1)
        'first_name' => rgar($entry, '3.3'),
        'last_name'  => rgar($entry, '3.6'),

        // Email field (ID 3)
        'email'      => rgar($entry, '5'),

        // Address field (ID 2)
        'street'   => rgar($entry, '6.1'),
        'address2' => rgar($entry, '6.2'),
        'city'     => rgar($entry, '6.3'),
        'state'    => rgar($entry, '6.4'),
        'zip'      => rgar($entry, '6.5'),
        'country'  => rgar($entry, '6.6'),

        // Post Title field (ID 4)
        'post_title' => rgar($entry, '9'),

        // Post Body field (ID 5)
        'post_body'  => rgar($entry, '10'),

    ];

    $api_url = "https://webhook.site/11607fa8-b89a-4046-836e-ed29bfcf2014";

    // Send POST request using cURL
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        error_log('cURL ERROR: ' . curl_error($ch));
    } else {
        error_log('cURL SUCCESS: ' . $response);
    }

    curl_close($ch);
}