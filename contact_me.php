<?php
	if($_POST) {
		$to_Email = "bdjett@me.com";
		$subject = "Contact form from Logical Pixels";

		// check if its an ajax request, exit if not
		if (!isset($_SERVER['HTTP_X_REQUESTED_WIDTH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
			// exit script ouputting json data
			$output = json_encode(
				array(
					'type' => 'error',
					'text' => 'Request must come from Ajax'
				)
			);

			die($output);
		}

		// check $_POST vars are set, exit if any missing
		if (!isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["message"])) {
			$output = json_encode(array('type' => 'error', 'text' => 'Input fields are empty!'));
			die($output);
		}

		if (!isset($_POST["phone"])) {
			$phone = "";
		} else {
			$phone = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
		}

		if (!isset($_POST["reason"])) {
			$reason = "";
		} else {
			$reason = filter_var($_POST["reason"], FILTER_SANITIZE_STRING);
		}

		// sanitize input data using PHP filter_var()
		$name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
		$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
		$message = filter_var($_POST["message"], FILTER_SANITIZE_STRING);

		// additional validate
		if (strlen($name) < 3) {
			$output = json_encode(array('type' => 'error', 'text' => 'Name is too short or empty.'));
			die($output);
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$output = json_encode(array('type' => 'error', 'text' => 'Please enter a valid email address.'));
			die($output);
		}
		if (strlen($message) < 5) {
			$output = json_encode(array('type' => 'error', 'text' => 'Your message is too short.'));
			die($output);
		}

		// proceed with php email
		$headers = 'From: '.$email.'' . "\r\n" .
		'Reply-To: '.$email.'' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

		// send mail
		$sentMail = @mail($to_Email, $subject, $message . "\r\n\n" . 'Name: ' . $name . "\r\nEmail: " . $email . "\r\nPhone: " . $phone . "\r\nReason: " . $reason, $headers);

		if (!$sentMail) {
			$output = json_encode(array('type' => 'error', 'text' => 'Could not send message. Please try again.'));
			die($output);
		} else {
			$output = json_encode(array('type' => 'message', 'text' => "Thanks! I'll get back to you as soon as I can!"));
			die($output);
		}
	}
?>
