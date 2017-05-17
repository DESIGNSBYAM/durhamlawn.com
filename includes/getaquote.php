<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

$mail = new PHPMailer();


//$mail->SMTPDebug = 3;                               // Enable verbose debug output
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'ssl://email-smtp.us-east-1.amazonaws.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'AKIAJLUR3JPWBPBFT3XA';             // SMTP username
$mail->Password = 'AiLIsO+PIvJczVcLD8vugr60ksaRuMmdI5WqLymRLz1l';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to

$message = "";
$status = "false";

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if( $_POST['form_name'] != '' AND $_POST['form_email'] != '' ) {

        $name = $_POST['form_name'];
        $email = $_POST['form_email'];
        $phone = $_POST['form_phone'];
        $date = $_POST['form_date'];

        $service = $_POST['booking_service'];
        $message = $_POST['form_message'];

        $subject = isset($subject) ? $subject : 'New Message | Durham Lawn Quick Contact';

        $botcheck = $_POST['form_botcheck'];

        $toemail = 'contact@durhamlawn.com'; // Your Email Address
        $toname = 'Dustin Durham'; // Your Name

        if( $botcheck == '' ) {

            $mail->SetFrom( "no-reply@durhamlawn.com" , $name );
            $mail->AddReplyTo( $email , $name );
            $mail->AddAddress( $toemail , $toname );
            $mail->addBCC( "andrew@sorzery.com" , "Andrew at Sorzery" );
            $mail->Subject = $subject;

            $name = isset($name) ? "<strong>Name:</strong> $name" : '';
            $email = isset($email) ? "<strong>Email:</strong> $email" : '';
            $phone = isset($phone) ? "<strong>Phone:</strong> $phone" : '';
            $date = isset($date) ? "<strong>Date:</strong> $date" : '';
            $service = isset($service) ? "<strong>Service:</strong> $service" : '';
            $message = isset($message) ? "<hr>$message<br><br><hr><br>" : '';

            $referrer = $_SERVER['HTTP_REFERER'] ? 'This email was submitted from the <a href="' . $_SERVER['HTTP_REFERER'] . '">Quick Contact form</a> from a <a href="http://sorzery.com">Sorzery Web Solutions</a> website.' : '';
            $logo = '<img src="http://durhamlawn.com/images/logo-wide.png" alt="Durham Lawn Care"><br><br>';

            $body = "$logo<table><tr><td style='width:250px'>$name</td><td style='width:250px'>$phone</td></tr><tr><td colspan='2'>$email</td></tr><tr><td>$date</td><td>$service</td></tr></table>$message $referrer";

            $mail->MsgHTML( $body );
            $sendEmail = $mail->Send();

            if( $sendEmail == true ):
                $message = 'We have <strong>successfully</strong> received your message and will get back to you as soon as possible.';
                $status = "true";
            else:
                $message = 'Email <strong>could not</strong> be sent due to some unexpected error. Please try again later.<br /><br /><strong>Reason:</strong><br />' . $mail->ErrorInfo . '';
                $status = "false";
            endif;
        } else {
            $message = 'Bot <strong>detected</strong>! Clean yourself, botster!';
            $status = "false";
        }
    } else {
        $message = 'Please <strong>fill out</strong> all the fields and try again.';
        $status = "false";
    }
} else {
    $message = 'An <strong>unexpected error</strong> occured. Please try again later.';
    $status = "false";
}

$status_array = array( 'message' => $message, 'status' => $status);
echo json_encode($status_array);
?>