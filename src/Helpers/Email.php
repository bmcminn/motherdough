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

    private static string $altBodySplit = "\n-----\n";


    public static function setup(array $options = []) {

    }


    private static function newMessage(array $model) : PHPMailer {

        $model = array_replace_recursive(Config::get(), $model);

        // $smtp = Config::get('emails.smtp');
        $smtp       = array_query('emails.smtp', $model); // $model['emails']['smtp'];
        $company    = array_query('company', $model);
        $user       = array_query('user', $model);

        // Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        // Send using SMTP
        $mail->isSMTP();

        // Enable verbose debug output
        if ($smtp['debug']) { $mail->SMTPDebug = SMTP::DEBUG_SERVER; }

        $mail->SMTPSecure = $smtp['secure']
            ? PHPMailer::ENCRYPTION_SMTPS
            : 'Off'
            ;

        $mail->Host         = $smtp['host'];        // Set the SMTP server to send through
        $mail->SMTPAuth     = $smtp['auth'];        // Enable SMTP authentication
        $mail->SMTPAutoTls  = $smtp['autotls'];

        // $mail->Username = $smtp['username'];    // SMTP username
        // $mail->Password = $smtp['password'];    // SMTP password
        $mail->Port     = $smtp['port'];        // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`


        $mail->isHTML(true);


        $mail->setFrom($company['emailFrom'], $company['name']);
        $mail->addAddress($user['email'], $user['fullname']);     //Add a recipient
        // $mail->addAddress('ellen@example.com');               //Name is optional
        $mail->addReplyTo($company['emailReply'], 'Contact');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');


        // $mail->isHTML(true);    //Set email format to HTML
        // $mail->Subject = 'Here is the subject';
        // $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        return $mail;
    }



    protected static function render($name, $model=[]) {
        $template = Template::render($name, $model);

        $template = explode(self::$altBodySplit, $template);

        return $template;
    }





    public static function sendVerificationEmail(array $model = []) : bool {
        $success = true;

        $company    = $model['company'];
        $user       = $model['user'];

        $mail = self::newMessage($model);

        try {

            [$html, $altBody] = self::render('emails/verification-email', $model);

            $mail->Subject = 'Please verify your email address!';
            $mail->Body    = $html;     // 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = $altBody;  // 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }

        return $success;
    }



    public static function sendLoginOTP(array $model) {
        $success = true;

        $mail = self::newMessage($model);

        try {

            $template = self::render('emails/one-time-password', $model);

            [$html, $altBody] = $template;

            $mail->Subject = 'Please verify your email address!';
            $mail->Body    = $html;     // 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = $altBody;  // 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
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
