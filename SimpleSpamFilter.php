<?php

/**
 * Plugin Name: Simple Spam Filter
 * Plugin URI: http://rocketflare.net/wordpress/simple_spam_filter
 * Description: Add an easy-to-use spam filter to your comment form.
 * Version: 1.0
 * Author: Chris Paul
 * Author Email: dev@chrispaul.info
 * Author URI: http://rocketflare.net
 * License: GPLv2 or later
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

if ( !function_exists( 'add_action' ) ) { ?>
    <h3>Oops! This page cannot be accessed directly.</h3>
    <p><a href="http://www.rocketflare.net/wordpress/simple_spam_filter">rocketflare.net/wordpress/simple_spam_filter</a></p>
    <?php
	exit;
}

function SimpleSpamFilter_insert_honeypot() {
    echo '<div id="easy_captcha" style="position:absolute;top:-99999px;left:-99999px;"><p>What is 3 + 5?</p><p><input name="captcha_answer" /></p></div>';
}

add_action( 'comment_form_logged_in_after', 'SimpleSpamFilter_insert_honeypot' );
add_action( 'comment_form_after_fields', 'SimpleSpamFilter_insert_honeypot' );

function SimpleSpamFilter_check_for_spam( $commentdata ) {
    if ( $_POST['captcha_answer'] != '' ) {
        $commentdata['comment_type'] = 'spam';
    }
    
    return $commentdata;
}

add_filter( 'preprocess_comment', 'SimpleSpamFilter_check_for_spam' );

function SimpleSpamFilter_mark_as_spam( $approved , $commentdata ) {
    if ($commentdata['comment_type'] == 'spam') {
        $approved = 'spam';
    }
    return $approved;
}

add_filter( 'pre_comment_approved' , 'SimpleSpamFilter_mark_as_spam' , '99', 2 );
