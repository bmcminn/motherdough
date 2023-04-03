<?php
/**
 * Email class
 *
 * Static class that helps generate emails and sends them via the mailer library of choice (PhpMailer)
 *
 *
 *
 * @author     bmcminn <labs@gbox.name>
 * @since      2023
 */

namespace App\Helpers;

use App\Helpers\Config;
use App\Helpers\Template;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


class Email {


    private static function newMessage() : PHPMailer {

        $smtp = Config::get('emails.smtp');

        // Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        // Send using SMTP
        $mail->isSMTP();

        // Enable verbose debug output
        if ($smtp['debug']) { $mail->SMTPDebug = SMTP::DEBUG_SERVER; }
        if ($smtp['secure']) { $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; }

        $mail->Host     = $stmp['host'];        // Set the SMTP server to send through
        $mail->SMTPAuth = $stmp['auth'];        // Enable SMTP authentication
        $mail->Username = $smtp['username'];    // SMTP username
        $mail->Password = $smtp['password'];    // SMTP password
        $mail->Port     = $smtp['port'];        // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        // $mail->isHTML(true);    //Set email format to HTML
        // $mail->Subject = 'Here is the subject';
        // $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        return $mail;
    }




    public static function sendVerificationEmail(array $model = []) : bool {
        $success = true;

        $company = $model['company'];
        $user = $model['user'];

        $mail = self::newMessage();

        try {

            $mail->isHTML(true);

            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress($user['email'], $user['fullname']);     //Add a recipient
            // $mail->addAddress('ellen@example.com');               //Name is optional
            $mail->addReplyTo('contact@example.com', 'Information');
            $mail->addCC('cc@example.com');
            $mail->addBCC('bcc@example.com');


            $content = Template::render('emails/verification-email', $model);

            print_r($content);
            exit;

            $mail->Subject = 'Please verify your email address!';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            // $mail->send();
            return true;

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }

        return $success;
    }




    public static function sendChangeEmail(string $email, $token) {

    }




    public static function sendForgotPassword(string $email, $token) {
        $content = "Reset token: {$token}";
        $headers = [
            'from' => 'info@site.com',
        ];
        $success = self::composeEmail($email, 'Password reset', $content, $headers);
        return $success;
    }


    private static function composeEmail(string $to, string $subject, string $content, array $headers = []) {

        // TODO: figure out some form of content composition
        $html = $content;

        mail($to, $subject, $content, $headers);
    }


}
