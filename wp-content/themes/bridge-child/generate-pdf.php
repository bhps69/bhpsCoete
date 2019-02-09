<?php
// include an AWS S3 library to upload files to S3 Bucket
require(__DIR__.'/../../../S3Class/S3ClientConnection.php');
// include dompdf library
require(__DIR__.'/../../../dompdf/autoload.inc.php');

require(__DIR__.'/../../../wp-load.php' );

global $pdf;

global $wpdb;

$current_user = wp_get_current_user();

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$expId = $_POST['experience'];
$make = get_user_meta($current_user->ID, 'mepr_make',true).','.get_user_meta($current_user->ID,'mepr_model',true);
$config = get_user_meta($current_user->ID,'mepr_configuration',true);
$mainboom = get_user_meta($current_user->ID,'mepr_main_boom_length',true);
$jib = get_user_meta($current_user->ID,'mepr_jib_length',true);
$slift = get_user_meta($current_user->ID,'mepr_superlift',true);
$cweight = get_user_meta($current_user->ID,'mepr_counterweight',true);
$control = get_user_meta($current_user->ID,'mepr_controls',true);
$lmi = get_user_meta($current_user->ID,'mepr_lmi_safety_system',true);
$jtype = array_keys(unserialize(get_user_meta($current_user->ID,'mepr_job_type',true)));
$comp = get_user_by('login',get_user_meta($current_user->ID,'mepr_company_name',true));
$company = get_user_by('login',get_user_meta($comp->ID,'mepr_company_name',true));
$branch = get_user_by('login',get_user_meta($comp->ID,'mepr_branch_name',true));
if ($current_user->roles[0] == 'trainer') {
    $title = '<span style="font-size:40px;">T</span>raining';
} else {
    $title = '<span style="font-size:40px;">E</span>valuation';
}
$html = '<html>';
$html .= '<head>';
$html .= '</head>';
$html .= '<body style="background-color:#ADFF2F; padding: 0 20px;">';
$html .= '<h1 style="text-align:center; margin:0;"><span style="font-size:40px;">C</span>ERTIFICATE OF '.$title.'</h1>';
$html .= '<h3 style="text-align:center; margin:0;"><span style="font-size:36px;">T</span>O <span style="font-size:36px;">C</span>ERTIFY <span style="font-size:36px;">T</span>HAT</h3>';
$html .= '<p style="text-align:center; border-bottom: 1px solid #ddd;">(Crane Operator '.$current_user->display_name.')</p>';
$html .= '<h3 style="text-align:center;"><span style="font-size:36px;">H</span>AS <span style="font-size:36px;">C</span>OMPLETED <span style="font-size:36px;">E</span>valuation FOR</h3>';
$html .= '<div style="width:49%; float:left; height:130px;">';
if ($make != '') {
    $html .= '<p style="text-align:left; margin:0;">('.$make.')</p>';
}
if ($config != '') {
    $html .= '<p style="text-align:left; margin:0;">('.$config.')</p>';
}
if ($mainboom != '') {
    $html .= '<p style="text-align:left; margin:0;">('.$mainboom.')</p>';
}
if ($slift != '') {
    $html .= '<p style="text-align:left; margin:0;">('.$slift.')</p>';
}
if ($cweight != '') {
    $html .= '<p style="text-align:left; margin:0;">('.$cweight.')</p>';
}
if ($control != '') {
    $html .= '<p style="text-align:left; margin:0;">('.$control.')</p>';
}
if ($lmi != '') {
    $html .= '<p style="text-align:left; margin:0;">('.$lmi.')</p>';
}
$html .= '</div>';
$html .= '<div style="width:49%; float:right; height:130px;">';

foreach ($jtype as $type) {
    if ($type == 'standard-lift') {
        $html .= '<p style="text-align:left; margin:0;">(Standard Lift)</p>';
    } elseif ($type == 'critical-lift') {
        $html .= '<p style="text-align:left; margin:0;">(Critical Lift)</p>';
    } elseif ($type == 'near-power-lines') {
        $html .= '<p style="text-align:left; margin:0;">(Near Power Lines)</p>';
    } elseif ($type == 'multiple-crane-lift') {
        $html .= '<p style="text-align:left; margin:0;">(Multiple Crane Lift)</p>';
    } elseif ($type == 'lifting-personnel') {
        $html .= '<p style="text-align:left; margin:0;">(Lifting Personnel)</p>';
    } else {
        $html .= '<p style="text-align:left; margin:0;">(Heavy Cycle Work)</p>';
    }
}
$html .= '</div>';
$html .= '<h3 style="text-align:center; margin:0;">AWARDED THE_________DAY OF____________20____.</h3>';
$html .= '<div style="width:52%; float:left; text-align:left;"><h4>('.$company->display_name.' / '.$branch->display_name.')</h4></div>';
$html .= '<div style="width:45%; float:right; text-align:left;">';
$html .= '<p style="border-bottom: 1px solid;">'.$role.' '.$current_user->display_name.'</p>';
$html .= '<p style="margin:0;">('.$role.' '.$current_user->display_name.')</p></div>';
$html .= '<h5 style="text-align:left; margin:0;">Certificate provided through <a href="http://www.coete.us">www.COETE.US</a> (c) 2019</h5>';
$html .= '</body>';
$html .= '</html>';

$dompdf->loadHtml($html);

// Setup the paper size and orientation
$dompdf->setPaper('A5', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF and upload to S3 bucket
$output = $dompdf->output();

$filename = time().'_'.$current_user->user_login.'.pdf';

file_put_contents($filename, $output);

//save files to S3 bucket
$filepath = 'public/'.$current_user->user_login.'_'.date('Ymd').'/'.$filename;
                
$s3 = new S3();
                
$s3 = $s3->S3Connection();

$result = $s3->putObject([
    'Bucket' => 'coete-dev',
    'Key'    => $filepath,
    'SourceFile' => basename(__DIR__.'/'.$filename)			
]);
		
//get the url of the uploaded file
$file = $result['ObjectURL'];

//save link of certificate
update_user_meta($current_user->ID,'certificate_link',$file);
$wpdb->query("UPDATE `experience_table` SET `certificate` = "
        . "'".$file."' WHERE `id` = '".$expId."'");

//remove file from local
fclose($filename);
unlink(__DIR__.'/'.$filename);

$wpdb->query("UPDATE `selected_trainer_evaluator` SET `status` = "
    . "'1', `certificate` = '".$file."' WHERE "
    . " `id` = '".$_POST['operator']."'");

$data['file'] = 'You are approved. Certificate : '.$file; 

echo json_encode($data); 