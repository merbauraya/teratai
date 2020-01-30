<?php

namespace app\helper;
use Yii;
use app\models\Invoice;
use app\models\InvoiceDetail;
use app\models\BanquetOrder;
use app\models\BanquetOrderDetail;
use mdm\autonumber\AutoNumber;
use yii\db\Expression;
use DateTime;
use DateInterval;


class InvoiceHelper
{
    /**
     * Create new invoice for multiple orders
     * $orders is an array of orders to be included in the invoice
     * return new Invoice created
     */
    public static function createForManyOrder($customerId,$orders)
    {
        $invoice = new Invoice();
        $invoice->invoiceNo = AutoNumber::generate(self::getNumberFormat());
        $invoice->invoiceDate = new Expression("NOW()");
        $dueDate = new DateTime();
        $dueDate->add(new DateInterval('P14D'));

        $invoice->dueDate = $dueDate->format('Y/m/d h:i'); 
        $invoice->totalAmount=0;
        $invoice->customerId = $customerId;
        //find all orders for this invoice
        /*$orders = BanquetOrder::find()
                  ->where(['orderId' => $orderIds])
                  ->all();
        */

        $transaction = Yii::$app->db->beginTransaction();


        try
        {
            $invoice->save();
            foreach ($orders as $order)
            {
                //find all order details
                $orderDetails = BanquetOrderDetail::find()
                        ->where(['orderId' => $order->orderId])
                        ->andWhere(['orderStatus' => BanquetOrder::ORDER_STATUS_COMPLETED])
                        ->orderBy(['orderDateTime' => SORT_ASC])
                        ->all();

                foreach ($orderDetails as $orderDetail)
                {
                    $invoiceDetail = new InvoiceDetail();
                    $invoiceDetail->invoiceId = $invoice->id;
                    $invoiceDetail->invoiceNumber = $invoice->invoiceNo;
                    $invoiceDetail->itemDescription = $order->orderPurpose;
                    $invoiceDetail->orderDetailId = $orderDetail->id;
                    $invoiceDetail->itemDescription2 = $orderDetail->getFormattedTimeString();
                    $invoiceDetail->quantity = $orderDetail->paxCount;
                    $invoiceDetail->unitPrice = $orderDetail->pricePerPax;
                    $invoiceDetail->totalAmount = $invoiceDetail->quantity * $invoiceDetail->unitPrice ;
                    $invoiceDetail->itemDate = $orderDetail->orderDateTime;
                    $invoiceDetail->orderId = $order->orderId;
                    $invoiceDetail->save();
                    $invoice->totalAmount = $invoice->totalAmount + $invoiceDetail->totalAmount;
                    $orderDetail->orderStatus = BanquetOrder::ORDER_STATUS_INVOICED;

                    $orderDetail->save();


                }
                

            }
            //set order to invoiced status
            $order->orderStatus = BanquetOrder::ORDER_STATUS_INVOICED;
            $prder->invoiceNumber = $invoice->invoiceNo;
            $orderStatus = AppHelper::newOrderStatus($order->orderId,BanquetOrder::ORDER_STATUS_INVOICED);
            $orderStatus->save();
            $invoice->save();
            $transaction->commit();

        }catch (Exception $e)
        {
            Yii::error($e);
            $transaction->rollBack();
            return null;
        } 

        return $invoice;

    }

    public static function generateForUser($userId)
    {
        Yii::info('inv user='.$userId);
        $orders = BanquetOrder::find()
        ->where(['userId' => $userId,'orderStatus' => BanquetOrder::ORDER_STATUS_VERIFIED])
        ->all();

        return self::createForManyOrder($userId,$orders);


    }

    public static function createForSingleOrder($order)
    {
        $invoice = new Invoice();
        $invoice->invoiceNo = AutoNumber::generate(self::getNumberFormat());
        $invoice->invoiceDate = new Expression("NOW()");
        $dueDate = new DateTime();
        $dueDate->add(new DateInterval('P14D'));

        $invoice->dueDate = $dueDate->format('Y/m/d h:i'); 
        $invoice->totalAmount=0;
        $invoice->customerId = $order->userId;

        try
        {
            $invoice->save();
            $invoiceDetail = new InvoiceDetail();
            $invoiceDetail->itemDescription = $order->orderPurpose();
            $invoiceDetail->invId = $invoice->id;





        }catch (Exception $e)
        {
            Yii::error($e);
            $transaction->rollBack();
            return null;
        } 

        return $invoice;


    }

    private static function getNumberFormat()
    {
        return 'INV/{Y/m}/????';
    }

}


?>