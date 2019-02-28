
<?php
/**
 * Plugin Name: My First Pluginss
 * Plugin URI: http://localhost/coete/myplugin
 * Description: The very first plugin that I have ever created.
 * Version: 1.0
 * Author: Srujana
 * Author URI: http://localhost/coete
 */
?>

<?php
add_action('admin_menu', 'email_plugin_setup_menu');
 
function email_plugin_setup_menu(){
        add_menu_page( 'Email-Plugin page', 'My First Plugin', 'manage_options', 'email-plugin-page', 'uploads' );
}
function uploads(){
	if (!headers_sent()) {
  foreach (headers_list() as $header)
    header_remove($header);
}
  echo "this is my plugin..";
  $conn =mysqli_connect("localhost","root","","coete");
  if($conn)
	  echo "DB is CONNECTED";
  else{
	echo "db not connected";
	exit;
	}

$result = mysqli_query($conn,"select 1 from `emails` LIMIT 1");
if($result){
  echo "<table cellpadding='10' cellspacing='0' id='tbl' class='table table-striped table-bordered' style='width:100%'>
  <thead>
  <tr>
  <th>sno</th>
  <th>NAme</th>
  <th>Phone</th>
  <th>Message</th>
  <th colspan=2>Email status</th>
 </tr>
 </thead>";
  $no=1;
  $status;
  $results = mysqli_query($conn,"select * from `emails` ");
  while($row = mysqli_fetch_array($results))
  {
  echo "<tbody>";
  echo "<tr>";
  echo "<td>" . $no . "</td>";
  echo "<td>" . $row[1] . "</td>";
  echo "<td>" . $row[2] . "</td>";
  echo "<td>" . $row[3] . "</td>";
  $status = $row[4]  ."</td>";
  if($status == 1){
    echo "<td>" . "email has been received" . "</td>";
  }
  else{
    echo "<td>" . "email sent failed" . "</td>";
  }
  echo "</tr>";
  $no++;
  }
  echo "</tbody>";
  echo "</table>";
}
 else{
   $query="CREATE TABLE emails(rec_id INT AUTO_INCREMENT PRIMARY KEY, rec_name VARCHAR(30), rec_phone BIGINT(10), rec_message VARCHAR(100), emailstatus BOOLEAN)";
   $result= mysqli_query($conn,$query) or die(mysqli_error($conn));
   if($result){
     echo " table created successfully";
   }else{
     echo "table creation failed";
   }
 }
}
// wp_register_script( 'js', 'http://localhost/coete/wp-content/plugins/sendemails/js/validate.js');
// wp_enqueue_script( 'jquery' );
// wp_enqueue_script( 'js' );
// wp_register_style( 'style', 'http://localhost/coete/wp-content/plugins/sendemails/css/style.css');
// wp_enqueue_style( 'style' );
add_action('wp_enqueue_scripts', 'qg_enqueue');
function qg_enqueue() {
    wp_register_script(
      'qgjs',
      plugin_dir_url(__FILE__).'js/validate.js'
  );
  wp_enqueue_script(
    'qgjs'
);
  wp_register_style(
    'tmrs',
    plugin_dir_url(__FILE__).'css/style.css'
  );
  wp_enqueue_style(
    'tmrs'
  );
}
 //datatable---//
wp_register_script( 'datatbl', 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js');
wp_enqueue_script( 'datatbl' );

 wp_register_style('data','https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css');
 wp_enqueue_style('data');
 //datatable---//

function form_creation(){
  $html = "<form id='formid' method='post'>";
  $html .= "<input type='text' name='nam' size='30' placeholder='enter name'>".'<br>';
  $html .= "<input type='text' maxlength='10' name='phone' size='30' placeholder='enter phone'>".'<br>';
  $html .= "<textarea name='msg' placeholder='enter message'>";
  $html .= "</textarea>".'<br>';
  $html .= "<button name='btn' type='submit'>Send";
  $html .="</button>";
  $html .= "</form>".'<br>';
  return $html;
}
emails();
?>
<?php
global $wpdb;
  function emails(){
	  $conn= mysqli_connect("localhost","root","","coete");
	  if($conn)
		  echo "connected";
	  else
	  {
		  echo "not connected";
		  exit;
	  }
    $to="siddigarisru@gmail.com";
    //echo $to;
  // Always set content-type when sending HTML email
  
$headers = "From: bhoomi@gkblabs.com" . "\r\n";
 if(isset($_POST['btn'])){
    $name=$_POST['nam']; 
    echo $name;
    $phone=$_POST['phone'];
    echo $phone;
    $usrmsg=$_POST['msg'];
//    include 'connectdb.php';
    if(mail($to,"some subject",$txt,$headers))
    {
      echo "mail sent";
      $status=1;
    }
    else
    {
      echo "mail failed";
      $status=0;
    }
    echo $status; 
    $sql = "INSERT INTO emails(rec_name, rec_phone, rec_message, emailstatus)
    VALUES ('$name', '$phone', '$usrmsg','$status')";
    $sql_exe=mysqli_query($conn,$sql) or die(mysqli_error);
}
  }
add_shortcode("test", "form_creation");
?>

