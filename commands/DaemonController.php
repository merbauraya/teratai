<?php

namespace app\commands; 
use Yii;
use yii\helpers\Url;
use yii\console\Controller;
use app\models\BanquetOrderDetail;
use app\models\BanquetOrder;


use app\helper\AppHelper;
use app\helper\MailHelper;
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

        $viewURL = Url::base('http').'/banquet-order/verify?id=2';
       // echo $viewURL;

       echo \Yii::$app->getUrlManager()->createUrl(['invoice/online-payment', 'invoice_number' => 'F00001', 'hash' => 'thisIsMyHash']);

        $orders = BanquetOrderDetail::find()
        ->where(['between', 'orderDateTime', $startDateString, $endDateString])
        ->andWhere(['in', 'orderStatus', BanquetOrder::ORDER_STATUS_APPROVED,BanquetOrder::ORDER_STATUS_IN_PROGRESS])
        ->all();

        //update all approved/in progress order to completed



        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            Yii::$app->db->createCommand("UPDATE banquet_order_detail 
                                         SET orderStatus=:newStatus,
                                         autoStatusDate = CURDATE()
                                        WHERE 
                                        orderDateTime between :startDate and :endDate
                                        AND orderStatus in (:status1, :status2)")
            ->bindValue(':newStatus', BanquetOrder::ORDER_STATUS_COMPLETED)
            ->bindValue(':startDate', $startDateString)
            ->bindValue(':endDate', $endDateString)
            
            ->bindValue(':status1', BanquetOrder::ORDER_STATUS_APPROVED)
            ->bindValue(':status2', BanquetOrder::ORDER_STATUS_IN_PROGRESS)
            ->execute();

            $orders = Yii::$app->db->createCommand('SELECT distinct orderId 
                                                    from banquet_order_detail 
                                                    where
                                                    autoStatusDate = CURDATE()')
            ->queryAll();
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
                    } 

                    Yii::$app->db->createCommand("UPDATE banquet_order
                                         SET orderStatus=:newStatus,
                                         notificationSent=1
                                        WHERE 
                                        orderid = :orderId")
                                        
                    ->bindValue(':newStatus', BanquetOrder::ORDER_STATUS_COMPLETED)
                    ->bindValue(':orderId',$orderId)
                    ->execute();
                    
                }


            }

            
            $transaction->commit();

        } catch (Exception $e)
        {
            Yii::error('Error saving order detail');
            Yii::error ($e->getMessage());
            $transaction->rollBack();
        }

        
        
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