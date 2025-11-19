<?php
/**
 * Plugin Name:  Sample Plugin
 * Description: Testing a simple plugin
 * Version: 1.0.0
 * Author: Ronaldo
 * Author URI: https://ryne.sblik.com
 * Plugin URI: https://ryne.sblik.com
 *
 * @author Ronaldo
 * @version 1.0.0
 */

// Version update test for pull request

function fcGetSuccesQuotePhrase(): string
{

    $quotes = [
        "Success is not final; failure is not fatal: It is the courage to continue that counts.",
        "The most certain way to succeed is always to try just one more time, 
        Success is the sum of small efforts, repeated day in and day out, 
        If there is no struggle, there is no progress, 
        and Success is how high you bounce when you hit bottom",
        "The successful man will profit from his mistakes and try again in a different way."
    ];

    // Randomly choose a line from the quote
    return wptexturize($quotes[mt_rand(0, count($quotes)-1)]);
}

function showQuotePhrase(): void
{
    echo "<div class='notice notice-info is-dismissible'><p id='koi-quote'>".fcGetSuccesQuotePhrase()."</p></div>";
}

// Some CSS styling for the quote phrase
function fcQuoteCss(): void
{
    $side = is_rtl() ? 'right' : 'left';

    echo "<style>
        #koi-quote{
          display:block;
          margin-{$side}:15px;
          padding-top:5px;
          font-size:14px;
          font-style:italic;
          font-weight:bold;
          text-align:{$side};
        }
    </style>";
}

add_action('admin_head', 'fcQuoteCss');
add_action('admin_notices', 'showQuotePhrase');