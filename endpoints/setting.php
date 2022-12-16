<?php
require_once( '../../../../wp-load.php' );

$secret_key = $_POST['secret_key'];
update_option( 'chatGPT_secret_key', $secret_key );
