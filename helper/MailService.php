<?php
namespace app\helper;

use Yii;

class MailService
{
    public static function send ($from,$to,$subject,$view,$params)
    {
        Yii::$app->mailer
        ->compose(['html' => $view, 'text' => "{$view}-text"], $params)
        ->setFrom($from)
        ->setTo($to)
        ->setSubject($subject)
        ->send();
    }
}

?>