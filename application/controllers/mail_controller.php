<?php
/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 14/09/2017
 * Time: 09:54
 */

define('ANIM_ALERT_MAIL', 1);
define('PW_RECOVERY_MAIL', 2);
define('ANIM_CREATED_ALERT_MAIL', 3);

class MailController {


    /**
     * invia la mail
     * @param integer $type
     * @param array $params
     * @return string
     */
    static function send($type, $params) {
        $from = CONTACT_MAIL;
        $recipients = self::get_recipients($_POST['recipients']);
        $message = self::generate_message($type, $params);
        $header = self::generate_header($from);
        foreach ($recipients as $recipient) {
            $result = mail($recipient, $message, $header);
            if(!$result) {
                return json_encode(['ok' => false, 'reason' => "Impossibile inviare l'email a $recipient", 'code' => 23]);
            }
        }
        return json_encode(['ok' => true]);
    }


    /**
     * @param integer $type
     * @param array $params
     * @param string $from
     * @return string
     */
    static function generate_header($from) {
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-type:text/html; charset=UTF-8";
        $headers[] = "From: ".APP_NAME.' <'.$from.'>';
        return implode("\r\n", $headers);
    }

    /**
     * @param string $recipient_list
     * @return array
     */
    static function get_recipients($recipient_list) {
        return explode(';', $recipient_list);
    }

    static function generate_subject($type, $params) {
        switch ($type) {
            case PW_RECOVERY_MAIL:
                return 'Richiesta di recupero password';
                break;
            case ANIM_ALERT_MAIL:
                return 'ANIMAZIONE: Festa';
            default:
                return $params['subject'];
                break;
        }
    }

    static function generate_message($type, $params) {
        switch($type) {
            case PW_RECOVERY_MAIL:
                $message = self::get_message_html('pw_recovery');
                $message = self::sub_string($message, 'server', 'http://www.'.$_SERVER['SERVER_NAME'].'/'.W_ROOT);
                $message = self::sub_string($message, 'token', $params['token']);
                return $message;
            default:
                return '';
        }
    }

    static function get_message_html($page) {
        $file = ABS_PATH."/application/views/templates/mails/$page.html";
        return file_get_contents($file);
    }

    static function sub_string($text, $param, $value) {
        return str_replace('['.strtoupper($param).']', $value, $text);
    }
}