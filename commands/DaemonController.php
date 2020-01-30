<?php

namespace app\commands; 
use Yii;
use yii\helpers\Url;
use yii\console\Controller;
use app\models\BanquetOrderDetail;
use app\models\BanquetOrder;


use app\helper\AppHelper;
use app\helper\MailHelper;
use app\helper\InvoiceHelper;
use DateTime;
 
/**
 * Daily job update controller
 */
class DaemonController extends Controller {


    public function actionDaily()
    {
        
        $date = date("Y-m-d",strtotime("yesterday"));
        $yesterDay = DateTime::createFromFormat('Y-m-d',$date);
        $yesterDay->setTime(1,0);

        $startDateString = $yesterDay->format('Y-m-d G:i');
        $yesterDay->setTime(23,59);
            
        $endDateString = $yesterDay->format('Y-m-d G:i');
        echo $endDateString;

        //get all approved order for yesterday

        //$viewURL = Url::base('http').'/banquet-order/verify?id=2';
       // echo $viewURL;

       //echo \Yii::$app->getUrlManager()->createUrl(['invoice/online-payment', 'invoice_number' => 'F00001', 'hash' => 'thisIsMyHash']);
        /*
        $orders = BanquetOrderDetail::find()
        ->where(['in', 'orderStatus', BanquetOrder::ORDER_STATUS_APPROVED,BanquetOrder::ORDER_STATUS_IN_PROGRESS])
        ->andWhere(['between','orderDateTime',$startDateString,$endDateString])
        ->all();
        */

        //auto verify if not yet verified after certain days

        //update all approved/in progress order to completed

        $now = new DateTime();
        $now = $now->format('Y-m-d G:i');
        Yii::debug('start:'. $now);
        Yii::info ('start string='.$startDateString);
        Yii::info('end date string='.$endDateString);

        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            //update all order detail to completed status for yesterday orders
            Yii::$app->db->createCommand("UPDATE banquet_order_detail 
                                         SET orderStatus=:newStatus,
                                         autoStatusDate = CURDATE()
                                        WHERE
                                        orderStatus in (:status1,:status2) 
                                        AND orderDateTime <= :endDate")
                                        
            ->bindValue(':newStatus', BanquetOrder::ORDER_STATUS_COMPLETED)
            ->bindValue(':endDate', $endDateString)
            ->bindValue(':status1', BanquetOrder::ORDER_STATUS_APPROVED)
            ->bindValue(':status2', BanquetOrder::ORDER_STATUS_IN_PROGRESS)
            ->execute();

            $orders = Yii::$app->db->createCommand('SELECT distinct orderId 
                                                    from banquet_order_detail 
                                                    where
                                                    autoStatusDate = CURDATE()')
            ->queryAll();

            Yii::info ('completed order='.sizeof($orders));
            foreach ($orders as $order){
                $orderId = $order['orderId'];

                //find all detail for this with status not equal to completed
                //if exist, this summary order is not yet completed

                $unfinishedCount =  Yii::$app->db->createCommand('SELECT count(*)  as unfinished 
                from banquet_order_detail 
                where orderId = :id and orderStatus != :status')
                ->bindValue(':id',$orderId)
                ->bindValue(':status',BanquetOrder::ORDER_STATUS_COMPLETED)
                ->queryScalar();

                if ($unfinishedCount == 0) // all detail completed
                {
                    if (AppHelper::getRequireOrderVerification())
                    {
                        //send mail notification
                        MailHelper::sendForOrderVerification($orderId);
                        Yii::$app->db->createCommand("UPDATE banquet_order
                                         SET orderStatus=:newStatus,
                                         notificationSent=1
                                        WHERE 
                                        orderid = :orderId")
                                        
                        ->bindValue(':newStatus', BanquetOrder::ORDER_STATUS_COMPLETED)
                        ->bindValue(':orderId',$orderId)
                        ->execute();
                    } else // no verification required
                    {
                        //verify the order automatically
                        Yii::$app->db->createCommand("UPDATE banquet_order
                                         SET orderStatus=:newStatus,
                                         notificationSent=1
                                        WHERE 
                                        orderid = :orderId")
                                            
                        ->bindValue(':newStatus', BanquetOrder::ORDER_STATUS_VERIFIED)
                        ->bindValue(':orderId',$orderId)
                        ->execute();

                    }

                    
                    
                }



            }

            self::autoVerifyOrder();

            self::generateInvoice();
            $transaction->commit();

        } catch (Exception $e)
        {
            Yii::error('Error saving order detail');
            Yii::error ($e->getMessage());
            $transaction->rollBack();
        }

        
        
    }

    /**
     * Invoice generation routine for verified order
     */
    private static function generateInvoice()
    {
        //find all orders that have been verified and
        //ready to be invoiced

        $userIds = BanquetOrder::find()
                    ->select('userId')
                    ->where(['orderStatus' => BanquetOrder::ORDER_STATUS_VERIFIED])
                    ->distinct()->asArray()->all();

        
        foreach ($userIds as $userId)
        {
            $id = $userId["userId"];
            Yii::info('xx='.$id);
            InvoiceHelper::generateForUser($id);
        }



    }

    /**
     * User has the option to manually verify completed order
     * If he/she does not verify the order after n day
     * the order will automatically verified by system
     */
    private static function autoVerifyOrder()
    {
        
        //get all order which are completed and not verified
        Yii::$app->db->createCommand('UPDATE banquet_order
                                         SET orderStatus=:statusVerified,
                                         notificationSent=1
                                        WHERE 
                                            orderStatus =:statusCompleted
                                        AND ADDDATE(latestEventDate,:autoVerifyDay) <= CURDATE() 
                                        ')
        ->bindValue(':statusVerified', BanquetOrder::ORDER_STATUS_VERIFIED)
        ->bindValue(':statusCompleted', BanquetOrder::ORDER_STATUS_COMPLETED)
        ->bindValue(':autoVerifyDay', AppHelper::getDaysOrderAutoVerified())
        
        ->execute();
        
        

    }

   
    private static function getStartDateString()
    {
        $date = date("Y-m-d",strtotime("yesterday"));
        $yesterDay = DateTime::createFromFormat('Y-m-d',$date);
        $yesterDay->setTime(1,0);

        return $yesterDay->format('Y-m-d G:i');

    }
    private static function getEndDateString()
    {
        $date = date("Y-m-d",strtotime("yesterday"));

        $yesterDay = DateTime::createFromFormat('Y-m-d',$date);
        return $yesterDay->format('Y-m-d G:i');
    }
 

}


?>