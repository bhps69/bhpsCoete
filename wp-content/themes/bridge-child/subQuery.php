<?php /** Template Name: subQuery*/
$con = mysqli_connect("localhost","root","","coete");
if(!$con)
	die("the server could not connect");
$query="select display_name from `wp_users' where user_login=SELECT meta_value FROM `wp_usermeta` where user_id=116 and meta_key='mepr_company_name'";
$result = $con->query($query);
print_r($result);
?>
