<?php

session_start();

$gCF_abspath = dirname(__FILE__);
$gCF_abspath_1 = str_replace('wp-content/plugins/simple-contact-form', '', $gCF_abspath);
$gCF_abspath_1 = str_replace('wp-content\plugins\simple-contact-form', '', $gCF_abspath_1);

require_once($gCF_abspath_1 .'wp-config.php');
	
$gcf_table = get_option('gCF_table');
$gcf_name = $_POST['gcf_name'];
$gcf_email = $_POST['gcf_email'];
$gcf_email_check = mysql_real_escape_string(trim($gcf_email));
$gcf_message = $_POST['gcf_message'];
$gcf_message_check = mysql_real_escape_string(trim($gcf_message));
$gcf_captcha = $_POST['gcf_captcha'];
if(isset($_POST['readygraph_insert']) && $_POST['readygraph_insert'] == "true"){
$readygraph_insert = $_POST['readygraph_insert'];
$_SESSION['security_code'] = "3ab7e1049519ada9fbacf0f1fc59a6b0";
}else{
}

if( $_SESSION['security_code'] == mysql_real_escape_string(trim($gcf_captcha)) && !empty($_SESSION['security_code'] ) ) 
{
	if(isset($readygraph_insert) && $readygraph_insert == "true"){
	$sqlcheck = "select * from $gcf_table where gCF_email='$gcf_email_check' and gCF_message='$gcf_message_check'";
	$check = $wpdb->get_results($sqlcheck);
	if ($wpdb->num_rows != 0)
	{
	}
	else{
	$sql = "insert into $gcf_table"
	. " set `gCF_name`='" . mysql_real_escape_string(trim($gcf_name))
	. "', `gCF_email`='" . mysql_real_escape_string(trim($gcf_email))
	. "', `gCF_message`='" . mysql_real_escape_string(trim($gcf_message))
	. "', `gCF_ip`='" . $_SERVER['REMOTE_ADDR']
	. "', `gCF_date`=NOW();";
	$wpdb->get_results($sql);
	}
	}
	else{
	$sql = "insert into $gcf_table"
	. " set `gCF_name`='" . mysql_real_escape_string(trim($gcf_name))
	. "', `gCF_email`='" . mysql_real_escape_string(trim($gcf_email))
	. "', `gCF_message`='" . mysql_real_escape_string(trim($gcf_message))
	. "', `gCF_ip`='" . $_SERVER['REMOTE_ADDR']
	. "', `gCF_date`=NOW();";
	
	$wpdb->get_results($sql);
	}
	unset($_SESSION['security_code']);
	
	$gCF_On_SendEmail = get_option('gCF_On_SendEmail');
	$gCF_On_MyEmail = get_option('gCF_On_MyEmail');
	$gCF_title = get_option('gCF_title');
	$gCF_On_Subject = get_option('gCF_On_Subject');
	
	if( $gCF_On_SendEmail == "YES" )
	{
		if($gCF_On_MyEmail <> "youremail@simplecontactform.com")
		{
			$sender_email = mysql_real_escape_string(trim($gcf_email));
			$subject = $gCF_On_Subject;
			$subject = $gCF_title;
			$message = $gcf_message;				
	
			$message = preg_replace('|&[^a][^m][^p].{0,3};|', '', $message);
			$message = preg_replace('|&amp;|', '&', $message);
			$mailtext = wordwrap(strip_tags($message), 80, "\n");
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
			$headers .= "From: \"$sender_name\" <$sender_email>\n";
			$headers .= "Return-Path: <" . mysql_real_escape_string(trim($gcf_email)) . ">\n";
			$headers .= "Reply-To: \"" . mysql_real_escape_string(trim($gcf_name)) . "\" <" . mysql_real_escape_string(trim($gcf_email)) . ">\n";
			//$headers .= "To: \"" . $gCF_On_MyEmail . "\" <" . $gCF_On_MyEmail . ">\n";
			$mailtext = str_replace("\r\n", "<br />", $mailtext);
			//@wp_mail($sender_email, $subject, $mailtext, $headers);
			@wp_mail($gCF_On_MyEmail, $subject, $mailtext, $headers);
		}
	}
	//echo "Message sent successfully.";
	_e('Message sent successfully.', 'simple-contact-form');
} 
else 
{
	echo 'Invalid security code.';
}
?>