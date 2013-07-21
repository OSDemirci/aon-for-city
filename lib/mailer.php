<?php


require_once('phpmailer/class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
function sendMail($receiver,$subject ,$body){
	$mail             = new PHPMailer();

	$body             = eregi_replace("[\]",'',$body);

	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = "mail.yourdomain.com"; // SMTP server
	$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
	                                           // 1 = errors and messages
	                                           // 2 = messages only
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
	$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
	$mail->Username   = "aon4city@gmail.com";  // GMAIL username
	$mail->Password   = "Authorization4Security";            // GMAIL password

	$mail->Subject    = $subject;

	$mail->MsgHTML($body);
	$mail->FromName = "Aon4City";
	$mail->AddAddress($receiver, "");
	$mail->Send();
	/*
	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
	}
	*/
}
//sendMail('st101101006@etu.edu.tr','TestMaili(Selcuktan Sevgilerle)','Napiyorsun????');
?>