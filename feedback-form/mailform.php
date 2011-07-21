<?php 
$dontsendemail = 0;
$possiblespam = FALSE;
$strlenmessage = "";
$email = $_REQUEST['email']; 
$message = $_REQUEST['message']; 

	$subject = array(); 
	$subject[1] = "Bug Report";
		$subject[2] = "Feature Request";
		$subjectindex = $_REQUEST['subject'];
	if ($subjectindex == 0 || !isset($_REQUEST['subject'])) die ("You did not choose a subject line. Please hit your browser back button and try again.");
	else $subject = $subject[$subjectindex];
	$emailaddress = "keithdevon@gmail.com"; /* NOTE: Although your email address 
	is visible here in this code, the person contacting you will never see this email address. 
	Your email address will remain on your server, and it will not be sent from your server 
	to the person contacting you. It will also remain invisible to spam bots. Your email address
	is also never stored on any of our servers. You can choose to delete or not delete this note
	when you publish this page. 
	It will not change the functionality of the contact form. 
	*/
function checkcaptcha() {
			session_start();
			if ($_SESSION["pass"] != $_POST["userpass"]) {
				die("Sorry, you failed the CAPTCHA. Note that the CAPTCHA is case-sensitive. Please hit your browser back button and try again.");
				return 1;
			}
		}
	
function checkemail($field) {
	// checks proper syntax
	if( !preg_match( "/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/", $field))
	{
		die("Improper email address detected. Please hit your browser back button and try again."); 
		return 1;
	}
}
function spamcheck($field) {
	if(eregi("to:",$field) || eregi("cc:",$field) || eregi("\r",$field) || eregi("\n",$field) || eregi("%0A",$field)){ 
		$possiblespam = TRUE;
	}else $possiblespam = FALSE;
	if ($possiblespam) {
		die("Possible spam attempt detected. If this is not the case, please edit the content of the contact form and try again.");
		return 1;
	}
}
function strlencheck($field,$minlength,$whichfieldresponse) {
	if (strlen($field) < $minlength){
		die($whichfieldresponse); 
		return 1;
	}
}

		if ($dontsendemail == 0) $dontsendemail = checkcaptcha($email);
	
if ($dontsendemail == 0) $dontsendemail = checkemail($email);
if ($dontsendemail == 0) $dontsendemail = spamcheck($email);
if ($dontsendemail == 0) $dontsendemail = spamcheck($subject);
if ($dontsendemail == 0) $dontsendemail = strlencheck($email,10,"The email address field is too short. Please hit your browser back button and check your entry.<br />");

if ($dontsendemail == 0) $dontsendemail = strlencheck($subject,1,"You did not choose a subject. Please hit your browser back button and check your entry.<br />");

if ($dontsendemail == 0) $dontsendemail = strlencheck($message,10,"The message field is too short. Please hit your browser back button and check your entry.<br />");
if ($dontsendemail == 0) $dontsendemail = strlencheck($emailaddress,8,"You have not selected a recipient of your message. Please hit your browser back button and check your entry.<br />");
if ($dontsendemail == 0) {mail($emailaddress,"Subject: $subject",$message,"From: $email" ); include "email_sent.php";}
?>