<?php

namespace app\helper;
use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

use Da\User\Model\User;
use app\models\BanquetOrder;



use app\exceptions\EmailNotConfiguredException;

class MailHelper
{
    public static function sendForApproval($orderSummary)
    {


        $user = User::findOne($orderSummary->userId);

        //$user = User::findOne($orderDetail->order->userId);
        $managerProfile = $user->profile->managerProfile;
        if ($managerProfile == null)
        {
            throw new UserProfileException(Yii::t('app','Profile setup not completed. Please set user manager'));
        }
        $to = $managerProfile->user->email;
        
        $urlParam = 'orderId='.$orderSummary->orderId .'&managerId='.$managerProfile->user_id;
        $approveUrl = Url::base('http').'/order/approve?'.$urlParam;
       /*
        $approveUrl =  Url::base('http').Html::a('Approve',['/banquet-order/approve'], [
                'data-method' => 'POST',
                'data-params' => [
                    'orderId' => $orderSummary->orderId,
                    'managerId' => $managerProfile->user_id
                ],
            ]) ;
                */
        //$approvedUrl = Url::base('http').'/banquet-order/approve/'.$orderSummary->orderId;
        $rejectUrl = Url::base('http').'/banquet-order/reject?'.$urlParam;
        $params = [
            'orderSummary' => $orderSummary,
            'managerProfile' => $managerProfile,
            'userProfile' => $user->profile,
            'approveUrl' => $approveUrl,
            'rejectUrl' => $rejectUrl

        ];

       

        MailService::send(self::getEmailFrom(),$to,self::getApprovalSubject(),'approval',$params);
    }


   

    public static function sendForOrderVerification($orderId)
    {
        $orderSummary = BanquetOrder::findOne($orderId);
        $user = User::findOne($orderSummary->userId);
        $to = $user->email;
        $viewURL =  \Yii::$app->getUrlManager()->createUrl(['banquet-order/view', 'id' => $orderId]);

        //$viewURL = Url::base('http').'/banquet-order/verify?id='.$orderId;
        $verifyURL = \Yii::$app->getUrlManager()->createUrl(['order/verify', 'id' => $orderId]);

        //$verifyURL = Url::base('http').'/order/verify?id='.$orderId;
        $subject = AppHelper::getEmailOrderVerificationSubject();
        $params = [
            'orderSummary' => $orderSummary,
            'userProfile' => $user->profile,
            'viewURL' => $viewURL,
            'verifyURL' => $verifyURL,
            'subject' => $subject


        ];


        MailService::send(self::getEmailFrom(),$to,self::getOrderVeficationSubject(),'verification',$params);


    }

    public static function getEmailFrom()
    {
        $fromEmail = AppHelper::getEmailApprovalFrom();
        if ($fromEmail == null)
        {
            throw new EmailNotConfiguredException(Yii::t('app','Email admin is not configured'));
        }

        return $fromEmail;
    }

    public static function getApprovalSubject()
    {
        return AppHelper::getEmailApprovalSubject();

    }

    public static function getOrderVeficationSubject()
    {
        return AppHelper::getEmailOrderVerificationSubject();
    }

}


?>