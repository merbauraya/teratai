<?php
    namespace app\controllers;

    use Yii;
    use yii\rest\ActiveController;
    use yii\db\Expression;


    use app\models\BanquetOrder;
    use app\models\OrderStatus;
    


    class OrderController extends ActiveController
    {
        public $modelClass = 'app\models\BanquetOrder';  


        public function actionApprove()
        {
            \Yii::$app->response->format = \yii\web\Response:: FORMAT_HTML;     
            $this->layout = 'status';   
            //$orderId = Yii::$app->request->post('orderId');
            $orderId = Yii::$app->request->get('orderId');
     


            $managerId = Yii::$app->request->get('managerId');

            return $this->updateOrderStatus($orderId,$managerId,BanquetOrder::ORDER_STATUS_APPROVED,
            'Order successfully approved','Approving Order');

            




        }

        public function actionReject()
        {

            \Yii::$app->response->format = \yii\web\Response:: FORMAT_HTML;     
            $this->layout = 'status';

            $orderId = Yii::$app->request->get('orderId');
            $managerId = Yii::$app->request->get('managerId');


            return $this->updateOrderStatus($orderId,$managerId,BanquetOrder::ORDER_STATUS_DENIED,
            'Order successfully denied','Denying Order');
 



        }

        public function actionVerify()
        {
            \Yii::$app->response->format = \yii\web\Response:: FORMAT_HTML;     
            $this->layout = 'status';

            $orderId = Yii::$app->request->get('id');
            //$managerId = Yii::$app->request->get('managerId');


            return $this->updateOrderStatus($orderId,0,BanquetOrder::ORDER_STATUS_VERIFIED,
            'Order successfully Verified','Veryfying Order');


        }

        private function updateOrderStatus($orderId,$managerId,$newStatus,$successMessage,$title)
        {
            $orderStatus = $this->validateOrder($orderId,$newStatus);
            $order = $orderStatus['order'];

            /*
            if ($orderStatus['status'] == false)
            {
                return array('status' => $orderStatus->status,
                'message' => $orderStatus->message);
            }
            */
            //order is valid for further action
             //all set
            
             $transaction = Yii::$app->db->beginTransaction();
             $msg = $orderStatus['message'];
             if ($orderStatus['status'] == true)
             {
                try
                {
                    if ($managerId == 0)
                    {
                        $managerId = $order->userId;
                    }
                    $order->approvedBy = $managerId;
                    $order->approvedDate = new Expression("NOW()");
                    $order->orderStatus = $newStatus;
                    $order->save();
                    $cmd = Yii::$app->db->createCommand(
                        "UPDATE banquet_order_detail
                        SET orderStatus = :newStatus
                        WHERE  orderId = :orderId", 
                        [':newStatus' => $newStatus,
                        ':orderId' => $order->orderId])->execute();

                    $orderStatusLog = $this->createOrderStatus($order,$managerId);
                    $orderStatusLog->save();

                    $transaction->commit();
                    $msg = $successMessage;
                    //return array('status'=>true,'message'=>'Order denied!');
        
                    //update all 
                } catch (Exception $e)
                {
                    $transaction->rollBack();
                    $orderStatus['status'] = false;
                    $msg = 'System Error. Unable to complete your action'.
                    Yii::error($e);
                    
                    //array('status'=>false,'data'=>'Error approving Order');
                }
            }
             return $this->render('view',[
                'status'=> $orderStatus['status'],
                'message' => $msg,
                'title' => $title
            ]);

        }

        
        /**
         * Retrieve and validate Order based on order Id
         * Return array of order and order status
         */
        private function validateOrder($orderId,$newStatus)
        {
            $order = BanquetOrder::findOne($orderId);
            $status = false;
            $message = 'Order Unknown';
            if ($order == null)
            {
                $message =  'No Order Found';
                return array(
                    'order' => $order,
                    'status' => $status,
                    'message' => $message
                );
            }
            if ($order->orderStatus == BanquetOrder::ORDER_STATUS_CANCELLED)
            {
                $message = 'Order already cancelled';

            }
            if ($order->orderStatus == BanquetOrder::ORDER_STATUS_APPROVED)
            {
                $message = 'Order already approved';
            }
            if ($order->orderStatus == BanquetOrder::ORDER_STATUS_COMPLETED)
            {
                if ($newStatus == BanquetOrder::ORDER_STATUS_VERIFIED)
                {
                    $message = 'Order verified';
                    $status = true;
                } else
            
                {
                    $message = 'Order already completed';
                }
            }

            if ($order->orderStatus == BanquetOrder::ORDER_WAITING_APPROVAL)
            {
                $status = true;
                $message = 'Order Rejected';
            }

            return array(
                'order' => $order,
                'status' => $status,
                'message' => $message
            );
            
        }

        private function createOrderStatus($orderSummary,$statusBy)
        {
             //create order status 
             $orderStatus = new OrderStatus();
             $orderStatus->orderId = $orderSummary->orderId;
             $orderStatus->orderStatus = $orderSummary->orderStatus;
             $orderStatus->status_date = new Expression("NOW()");
             $orderStatus->status_by = $statusBy;
             return $orderStatus;
    
        }


    }



?>