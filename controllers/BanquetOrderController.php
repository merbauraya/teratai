<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use Da\User\Filter\AccessRuleFilter;



use app\models\BanquetOrder;
use app\models\BanquetOrderSearch;
use app\models\BanquetOrderDetail;
use app\models\BanquetOrderDetailSearch;
use app\models\FoodCategory;
use app\models\BanquetOrderFood;
use app\models\OrderStatus;
use app\helper\AppHelper;
use app\helper\MailHelper;
use DateTime;

/**
 * BanquetOrderController implements the CRUD actions for BanquetOrder model.
 */
class BanquetOrderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRuleFilter::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','approve'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','view','add-detail'],
                        'roles' => ['createOrder']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['updateOrder','createOrder']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['updateOrder','createOrder']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['updateOrder']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['order-summary','food-summary'],
                        'roles' => ['dataAdmin']
                    ],
                ],
               
            ]
        ];
    }

    

    /**
     * Lists all BanquetOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BanquetOrderDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    

    public function actionCreate()
    {
        $orderSummary = new BanquetOrder();
        $orderDetail = new BanquetOrderDetail();
        $submitAction = 'save';

        if (Yii::$app->request->isPost) { /* the request method is POST */ 
            $data = Yii::$app->request->post();
            $submitAction = Yii::$app->request->post('submit');

            if ($orderSummary->load(Yii::$app->request->post()))
            {
                $orderSummary = $this->initData($orderSummary);
                if ($orderDetail->load(Yii::$app->request->post()))
                {
                    $orderDateTime =  DateTime::createFromFormat('D d-M-Y h:i A',$orderDetail->orderDateTime);//new \DateTime($orderDetail->bod_date);
                    $orderDateString = $orderDateTime->format('Y/m/d G:i'); 
                    $orderDetail->orderDateTime = $orderDateString;
                    $orderDetail->orderStatus = AppHelper::getDefaultOrderStatus();
                    $orderSummary->orderStatus = AppHelper::getDefaultOrderStatus();
                    $orderSummary->latestEventDate = $orderDetail->orderDateTime;
                    $orderStatus = $this->createOrderStatus($orderSummary);
                    
                    $foodSelections = $data['BanquetOrderFood'];
                    
               
                    $orderDetailId = $this->saveOrderandFood($orderSummary,$orderDetail,
                    $foodSelections,$orderStatus);
                    

                    if(isset($_POST['approval']))
                    {

                    
                        if (AppHelper::orderRequireApproval())
                        {
                            MailHelper::sendForApproval($orderSummary);
                        }
                    }else //add suborder
                    {
                        $url = Url::toRoute([
                            'banquet-order/add-detail',
                            'id' => $orderSummary->orderId
                        ]);
                        return $this->redirect($url);
                    }
                    $url = Url::toRoute(['banquet-order/view', 
                        'id' => $orderDetail->id]);
                    return $this->redirect($url);

                }
               

            }
        }
        
        
        $foodSelector = $this->loadFoodTab(0);

        return $this->render('create', [
            'orderSummary' => $orderSummary,
            'orderDetail' => $orderDetail,
            'foodSelector' => $foodSelector

        ]);

    }
    /**
     * Update an order
     * $id refers to detail id. If successful, browser will be redirected to view-
     */
    public function actionUpdate($id)
    {
        $orderDetail = $this->findOrderDetail($id);
        if (!$orderDetail->isEditable())
        {
            throw new \yii\web\ForbiddenHttpException("You are not authorized to edit this order");
        }
        $orderSummary = $orderDetail->order;
        if (Yii::$app->request->isPost) { /* the request method is POST */ 
            $data = Yii::$app->request->post();
            if ($orderSummary->load(Yii::$app->request->post()))
            {
                $orderSummary = $this->initData($orderSummary);
                if ($orderDetail->load(Yii::$app->request->post()))
                {
                   
                    $orderDateTime =  DateTime::createFromFormat('D d-M-Y h:i A',$orderDetail->orderDateTime);//new \DateTime($orderDetail->bod_date);
                    $orderDateString = $orderDateTime->format('Y/m/d G:i'); 
                    $orderDetail->orderDateTime = $orderDateString;
                    
                    if ($orderSummary->latestEventDate < $orderDetail->orderDateTime)
                    {
                        $orderSummary->latestEventDate = $orderDetail->orderDateTime;
                    }

                    $foodSelections = $data['BanquetOrderFood'];
                    
                    $orderStatus = null;
                    $orderDetailId = $this->saveOrderandFood($orderSummary,$orderDetail,$foodSelections,$orderStatus);
                    $url = Url::toRoute(['banquet-order/view', 
                        'id' => $orderDetail->id]);
                    return $this->redirect($url);

                }
               

            }
        
        }
        $foodSelector = $this->loadFoodTab($id);
        return $this->render('update', [
            'orderSummary' => $orderSummary,
            'orderDetail' => $orderDetail,
            'foodSelector' => $foodSelector

        ]);

    }


    

    public function actionDetailUpdate($id)
    {
        $orderDetail = BanquetOrderDetail::findOne($id);
        $order = $orderDetail->order;
        $foodSelector = $this->loadFoodTab($id);

        if (Yii::$app->request->isPost) { /* the request method is POST */ 
            
            $data = Yii::$app->request->post();
            if ($orderDetail->load(Yii::$app->request->post()))
            {
                //Yii::debug("**");
                //Yii::debug($orderDetail->orderDateTime);
                $orderDateTime =  DateTime::createFromFormat('D d-M-Y h:i A',$orderDetail->orderDateTime);//new \DateTime($orderDetail->bod_date);
                $orderDateString = $orderDateTime->format('Y/m/d G:i'); 
                //Yii::debug($orderDetail->orderDateTime);
                $orderDetail->orderDateTime = $orderDateString;

                $foodSelections = $data['BanquetOrderFood'];
               
                $this->saveDetailAndFood($orderDetail,$foodSelections);
                $url = Url::toRoute(['banquet-order/view', 'id' => $id]);
                return $this->redirect($url);


            }

        }

        $orderFoods = [];
        // return $this->redirect(['view', 'id' => $model->orderId]);
        return $this->render('_formDetail.php', ['parent' => $order,
        'orderDetail'=>$orderDetail,'foodSelector' => $foodSelector,
        
        'foods'=> $orderFoods,
        ]);

    }

    public function actionApprove()
    {
        
        $orderId = Yii::$app->request->post('orderId');//, null);
        $managerId = Yii::$app->request->post('managerId');//, null);

        $orderSummary = AppHelper::validateOrderForApproval($orderId);
        

        
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            
            $orderSummary->approvedBy = $managerId;
            $orderSummary->approvedDate = new Expression("NOW()");
            $orderSummary->orderStatus = BanquetOrder::ORDER_STATUS_APPROVED;
            $orderStatus = $this->createOrderStatus($orderSummary);
            $orderSummary->save();
            $orderStatus->save();
            $cmd = Yii::$app->db->createCommand(
                "UPDATE banquet_order_detail
                 SET orderStatus = :newStatus
                WHERE  orderId = :orderId", 
                 [':newStatus' => BanquetOrder::ORDER_STATUS_APPROVED,
                 ':orderId' => $orderSummary->orderId])->execute();
            $transaction->commit();

            //update all 
        } catch (Exception $e)
        {
            $transaction->rollBack();

            Yii::error($e);
            Yii::$app->session->setFlash('error', "Error approving order");
        }
       



    }

    /*
    Helper function for saving/updating order detail and food selection
    */
    private function saveOrderandFood($orderSummary,$orderDetail,$foodSelections,$orderStatus)
    {
        $transaction = Yii::$app->db->beginTransaction();
        
        try
        {
            if ($orderSummary->save())
            {
                $orderDetail->orderId = $orderSummary->orderId;
                
                if ($orderDetail->orderDateTime > $orderSummary->latestEventDate)
                {
                    $orderSummary->latestEventDate = $orderDetail->orderDateTime ;
                }
                if ($orderStatus != null)
                {
                    $orderStatus->orderId = $orderSummary->orderId;
                    if (!$orderStatus->save())
                    {
                        Yii::error('Error saving order status');

                        Yii::error($orderStatus->getErrors());

                    }
                }
                if ($orderDetail->save())
                {
                    //delete all existing food selection for this order detail
                    BanquetOrderFood::deleteAll('orderDetailId=:orderDetailId', [':orderDetailId' => $orderDetail->id]);


                    foreach($foodSelections as $items)
                    {

                      
                        if (array_key_exists('foodId',$items)) //we process only selected food
                        {
                            $foodId = $items['foodId'];
                            $paxCount = $items['paxCount'];
                            if ($paxCount == null)
                            {
                                $paxCount = $orderDetail->paxCount;
                            }
                            $serveTypeId = $items['serveTypeId'];
                            if ($serveTypeId == null)
                            {
                                $serveTypeId = $orderDetail->serveTypeId;
                            }
                            $banquetOrderFood = new BanquetOrderFood();
                            $banquetOrderFood->orderId = $orderDetail->orderId;
                            $banquetOrderFood->foodId = $foodId;
                            $banquetOrderFood->paxCount = $paxCount;
                            $banquetOrderFood->orderDetailId = $orderDetail->id;
                            $banquetOrderFood->serveTypeId = $serveTypeId;
                            //$banquetOrderFood->orderStatus = AppHelper::getDefaultOrderStatus();
                            if (!$banquetOrderFood->save())
                            {
                                Yii::error('Error saving food detail');

                                Yii::error($banquetOrderFood->getErrors());
                                
                                break;
                            }
                        } 
                    }
                }else  //order detail not saved
                {
                   
                    Yii::error('Error saving order detail');
                    Yii::error($orderDetail->getErrors());

                    throw new \yii\base\UserException ('Error saving order detail. Please notify system administrator ');
                }
            } else
            {
                //order summary not saved
                throw new \yii\base\UserException ('error saving order . Please notify system administrator');

            }
            $transaction->commit();
            Yii::$app->session->setFlash('success', "Order saved successfully."); 
            return $orderDetail->id;
            
        } catch (Exception $e)
        {
            Yii::$app->session->setFlash('error', "Error saving order");
            $transaction->rollBack();
        }

    }

    public function actionAddDetail()
    {
        $data = Yii::$app->request->post();
        $orderId = Yii::$app->request->getQueryParam('id');
        $order = BanquetOrder::findOne($orderId);
        $foods = [];
        $orderDetail = new  BanquetOrderDetail();
        $msg = '';
        $foodSelector = $this->loadFoodTab(0);

       
        if (Yii::$app->request->isPost) { /* the request method is POST */ 
            $orderDetail = new BanquetOrderDetail();
            $orderDetail->orderId = $orderId;
            if ($orderDetail->load(Yii::$app->request->post()))
            {
                $orderDateTime =  DateTime::createFromFormat('D d-M-Y h:i A',$orderDetail->orderDateTime);//new \DateTime($orderDetail->bod_date);
                $orderDateString = $orderDateTime->format('Y/m/d G:i'); 
                $orderDetail->orderDateTime = $orderDateString;
                $orderDetail->orderStatus = AppHelper::getDefaultOrderStatus();

                //check for latest event date. if so, update summary order
                //to latest event date
                if ($order->latestEventDate < $orderDetail->orderDateTime)
                {
                    $order->latestEventDate = $orderDetail->orderDateTime;
                }

                $foodSelections = $data['BanquetOrderFood'];
               
                $this->saveOrderandFood($order,$orderDetail,$foodSelections,null);
                if(isset($_POST['approval']))

                {
                    $url = Url::toRoute(['banquet-order/view', 'id' => $orderDetail->id]);
                    if (AppHelper::orderRequireApproval())
                    {
                        MailHelper::sendForApproval($order);
                    }
                } else
                {
                    $url = Url::toRoute(['banquet-order/add-detail', 'id' => $order->orderId]);
                }
                
                
                return $this->redirect($url);
                
                
                
            }

            
        
        
        }

        $query = BanquetOrderDetail::find()->where(['bo_id' => $orderId]);
       
        $orderDetailDP = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'bod_date' => SORT_ASC,
                    'bod_time' => SORT_ASC, 
                ]
            ],
        ]);
        
        $orderFoods = [];
        // return $this->redirect(['view', 'id' => $model->orderId]);
        return $this->render('_formDetail.php', ['orderSummary' => $order,
        'orderDetail'=>$orderDetail,'foodSelector' => $foodSelector,
        'formData'=>$data,'msg' =>$msg,
        'foods'=> $orderFoods,
        'orderDetailDP' => $orderDetailDP]);


    }


    /**
     * View order summary for cafe staff
     */
    /*
   */

    public function actionOrderSummary()
    {
        $searchModel = new BanquetOrderDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('orderSummary', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * View order By food by date
     */
    public function actionFoodSummary()
    {
        $searchModel = new BanquetOrderDetailSearch();
        $dataProvider = $searchModel->searchWithFood(Yii::$app->request->queryParams);
        
        
        return $this->render('foodSummary', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionView($id)
    {
        
        $orderDetail = BanquetOrderDetail::findOne($id);
        $order = $orderDetail->order;
        $selectedFoods = $orderDetail->banquetOrderFoods;
        $query = BanquetOrderFood::find()->where(['orderDetailId' => $id]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'foodId' => SORT_ASC,
                    
                ]
            ],
        ]);
        return $this->render('viewDetail.php', ['order' => $order,
        'orderDetail'=>$orderDetail,'selectedFoodsDP' => $provider]);
    }

    /*
        Helper function for rendering food tab for selection
    */
    private function loadFoodTab($orderDetailId)
    {
        
        $foodCategories = FoodCategory::find()->all();
        $default = 1;
        foreach($foodCategories as $foodCategory)
        {
            $categoryId = $foodCategory->categoryId;
            $content = $this->loadFoodContent($foodCategory,$orderDetailId);

            $items[] = ['label' => $foodCategory->categoryName, 'content' => $content, 'active' => $default == 1];
            $default++;
        }

        return $items;
      }

    private function loadFoodContent($foodCategory,$orderDetailId)
    {
        $query = new Query;
        $query	->select([
            'food.foodId as id','food.foodName','banquet_order_food.*'
            ]
            )  
            ->from('food')
            ->leftjoin('banquet_order_food', 
                ['and','banquet_order_food.foodId =food.foodId','banquet_order_food.orderDetailId = :orderDetailId'])
            ->where('food.foodCategoryId = :foodCategoryId')
            ->addParams([':foodCategoryId' => $foodCategory->categoryId])
            ->addParams([':orderDetailId' => $orderDetailId])
            ->orderBy(['banquet_order_food.foodId' => SORT_DESC]);
            
           
            
            $command = $query->createCommand();
            $data = $command->queryAll();

            $dataProvider = new ArrayDataProvider([
                'key' => 'id',
                'allModels' => $data,

            ]);
            
            $content = $this->renderPartial('_foodSelector.php', ['data' => $data,'dataProvider' => $dataProvider]);


            return $content;
            //https://github.com/ivphpan/gwm/blob/a6bacb54c151bc9ec59433a3c22010eaab56e064/modules/core/widgets/LanguageTabs.php
    }

    

    /**
     * Deletes an existing order 
     * $id refers to order detail id
     * if order consits only this detail, order summary will also be deleted
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        
        $orderDetail = $this->findOrderDetail($id);
        //$orderFoods = $orderDetail->foods;
        $orderSummaryId =  $orderDetail->orderId;
        //allow only delete own order
        if ( Yii::$app->user->identity->id != $orderDetail->order->userId)
        {
            throw new \yii\web\ForbiddenHttpException("You are not authorized to delete this order");

        }
        $transaction = Yii::$app->db->beginTransaction();
        try
        {

            
            BanquetOrderFood::deleteAll(['orderDetailId' => $id]);
            //BanquetOrderFood::model()->deleteAllByAttributes(['orderDetailId' => $user->id]);
            $orderDetail->delete();
            //check if we have another order detail for this order summary
        
            if (BanquetOrderDetail::find()->where(['orderId' => $orderSummaryId]) == null)
            {
                BanquetOrder::findOne($orderSummaryId)->delete();
            } 
            
            $transaction->commit();
            Yii::$app->session->setFlash('success', "Order deleted");

        } catch (Exception $e)
        {
            Yii::$app->session->setFlash('error', "Error deleteting order");
            $transaction->rollBack();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the BanquetOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BanquetOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BanquetOrder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /**
     * Finds the BanquetOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BanquetOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findOrderSummary($id)
    {
        if (($model = BanquetOrder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) { // new record only, otherwise time is inserted every time this record is updated
        
        }
        parent::beforeSave($insert);
    }

    private function initData($model)
    {
        $orderDate =  new \DateTime(Yii::$app->request->post('orderDate'));
        $orderDateString = $orderDate->format('Y/m/d h:i'); 
        $model->orderDate = $orderDateString;
        //$model->latestEventDate = $model->orderDate;
        $model->userId = Yii::$app->user->identity->id;
        $model->createdDate = new Expression('NOW()');
        $model->orderStatus = 0;
        $model->notificationSent = BanquetOrder::NOTIFICATION_NOT_SENT;
        $model->approvalRequestSent = BanquetOrder::NOTIFICATION_NOT_SENT;
        $model->invoiceSent = BanquetOrder::INVOICE_NOT_SENT;

        
        return $model;
    }

    protected function findOrderDetail($id)
    {
        if (($model = BanquetOrderDetail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    private function createOrderStatus($orderSummary)
    {
         //create order status 
         $orderStatus = new OrderStatus();
         $orderStatus->orderId = $orderSummary->orderId;
         $orderStatus->orderStatus = $orderSummary->orderStatus;
         $orderStatus->status_date = new Expression("NOW()");
         $orderStatus->status_by = Yii::$app->user->identity->id;
         return $orderStatus;

    }
}
