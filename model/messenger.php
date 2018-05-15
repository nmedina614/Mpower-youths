<?php
/**
 * Created by PhpStorm.
 * User: scottmedlock
 * Date: 4/13/18
 * Time: 1:00 PM
 */

class Messenger
{
    /**
     * Method for sending emails to a given recipient.
     *
     * @param $recipient String email address to send to.
     * @param $subject String displayed at the top of the email.
     * @param $message String representing the body of the message.
     */
    public static function sendMessage($recipient, $subject, $message)
    {

        $subject = $subject;
        $txt = $message;
        $headers = 'From: M-Power Youth <noreply@'.$_SERVER['HTTP_HOST'].'>';

        mail($recipient,$subject,$txt,$headers);
    }
}