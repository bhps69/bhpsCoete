<?php
require(__DIR__.'/../../../wp-load.php' );
global $wpdb;

if (isset($_POST['user_email'])) {
    $email = $_POST['user_email'];
} else {
    $email = $_POST['mepr_primary_administrator_email'];
}

if (email_exists($email)) {
    echo 'false';
} else {
    echo 'true';
}