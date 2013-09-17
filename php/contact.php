<?php
include('contact-config.php');
$contact_form_version=strtolower($contact_form_version);
if(empty($my_email_address) || empty($contact_form_version) || ($contact_form_version!='php' && $contact_form_version!='html')) {
	die('Error: The contact form has not been setup correctly.');
} else {
	if($contact_form_version=='php') {
		$formLocation='../contact.php';
	} else if($contact_form_version=='html') {
		$formLocation='../contact.html';
	}
}
if(!empty($_POST['ajax']) && $_POST['ajax']=='1') {
	$ajax=true;
} else {
	$ajax=false;
}
if(empty($_POST['contactName']) || empty($_POST['contactEmail']) || empty($_POST['contactMessage'])) {
	if($ajax || $contact_form_version=='html') {
		die('Error: Missing variables');
	} else {
		header("Location: $formLocation?msg=missing-variables");
		exit;
	}
}
if (!preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,8})$/", $_POST['contactEmail'])) {
	if($ajax || $contact_form_version=='html') {
		die('Error: Invalid email address');
	} else {
		header("Location: $formLocation?msg=invalid-email-address");
		exit;
	}
}
$name=$_POST['contactName'];
$email=$_POST['contactEmail'];
$subject=$_POST['contactSubject'];
$message=$_POST['contactMessage'];
$to=$my_email_address;
$headers = 'From: '.$name.' <'.$email.'>'." \r\n";
$headers .= 'Reply-To: '.$email."\r\n";
if(isset($cc) && $cc!='') {
	$headers .= 'Cc: '.$cc."\r\n";
}
if(isset($bcc) && $bcc!='') {
	$headers .= 'Bcc: '.$bcc."\r\n";
}
$headers .= 'X-Mailer: PHP/' . phpversion();
$subject = $subject;
$message = wordwrap($message, 70);
if(mail($to, $subject, $message, $headers)) {
	if($ajax || $contact_form_version=='html') {
		die('Mail sent');
	} else {
		header("Location: $formLocation?msg=mail-sent");
		exit;
	}
} else {
	if($ajax || $contact_form_version=='html') {
		die('Error: Mail failed');
	} else {
		header("Location: $formLocation?msg=mail-failed");
		exit;
	}
}
?>