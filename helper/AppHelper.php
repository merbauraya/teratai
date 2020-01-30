<?php

namespace app\helper;

use Yii;
use yii\web\NotFoundHttpException;
use app\models\BanquetOrder;


class AppHelper 
{
    
    
    public static function getDefaultOrderStatus()
    {
        return self::orderRequireApproval() ? BanquetOrder::ORDER_WAITING_APPROVAL : BanquetOrder::ORDER_STATUS_APPROVED;
    }

    public static function orderRequireApproval()
    {
        return Yii::$app->config->get('orderRequireApproval',1);
    }
    
    public static function getEmailApprovalFrom()
    {
        return Yii::$app->config->get('approvalEmailFrom');
    }

    public static function getEmailApprovalSubject()
    {
        return  Yii::t('app',Yii::$app->config->get('approvalEmailSubject'));

    }

    public static function getEmailOrderVerificationSubject()
    {
        return Yii::$app->config->get('orderVerificationEmailSubject');
    }

    public static function getRequireOrderVerification()
    {
        return Yii::$app->config->get('orderRequireVerification',0);

    }
    public static function getRequireInvoiceApproval()
    {
        return Yii::$app->config->get('RequireInvoiceApproval');

    }
    public static function getDaysOrderAutoVerified()
    {
        return Yii::$app->config->get('dayOrderGetVerified',5);
    }

    public static function getApprovalUniqueString()
    {
        return self::generateUniqueId(10);
    }

    public static function getAutoSendInvoice()
    {
        return Yii::$app->config->get('autoSendInvoice',0);
    }
    public static function getInvoiceGrouping()
    {
        return Yii::$app->config->get('invoiceGrouping',0);
        
    }
    
    public static function getInvoiceSendOption()
    {
        return Yii::$app->config->get('invoiceSendOption',0);
    }

    public static function getInvoiceNumberFormat()
    {
        return Yii::$app->config->get('invoiceNumberFormat',1);
 
    }
    public static function getInvoiceNumberPrefix()
    {
        return Yii::$app->config->get('invoiceNoPrefix');
    }
    public static function getInvoiceNoDigitSize()
    {
        return Yii::$app->config->get('invoiceNoDigitSize');
    }

    private static function generateUniqueId($limit)
    {
         return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }



    public static function validateOrderForApproval($orderId)
    {
        $order = BanquetOrder::findOne($orderId);
        if ($order == null)
        {
            //throw new \yii\base\UserException ('error saving order . Please notify system administrator');
            throw new NotFoundHttpException('Invalid Order.');

        }
        if ($order->orderStatus == BanquetOrder::ORDER_STATUS_CANCELLED)
        {
            throw new \yii\base\UserException ('Order already cancelled');

        }
        if ($order->orderStatus == BanquetOrder::ORDER_STATUS_APPROVED)
        {
            throw new \yii\base\UserException ('Order already approved');
        }
        if ($order->orderStatus == BanquetOrder::ORDER_STATUS_COMPLETED)
        {
            throw new \yii\base\UserException ('Order already completed');
        }

        return $order;


    }

    public static function newOrderStatus($orderId,$status)
    {
        $orderStatus = new OrderStatus();
        $orderStatus->orderId = $orderId;
        $orderStatus->orderStatus = $status;
        $orderStatus->status_date = new Expression("NOW()");
        $orderStatus->status_by = self::getDefaultUserId();
        return $orderStatus;
    }
    public static function getDefaultUserId()
    {
        return isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0;
    }

    
}
