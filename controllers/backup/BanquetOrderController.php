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


use app\models\BanquetOrder;
use app\models\BanquetOrderSearch;
use app\models\BanquetOrderDetail;
use app\models\BanquetOrderDetailSearch;
use app\models\FoodCategory;
use app\models\BanquetOrderFood;

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
                    $orderDetail->orderStatus = BanquetOrder::ORDER_STATUS_NEW;
                    $foodSelections = $data['BanquetOrderFood'];
                    
               
                    $orderDetailId = $this->saveOrderandFood($orderSummary,$orderDetail,$foodSelections);
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
                    
                    $foodSelections = $data['BanquetOrderFood'];
                    
               
                    $orderDetailId = $this->saveOrderandFood($orderSummary,$orderDetail,$foodSelections);
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
                Yii::debug("**");
                Yii::debug($orderDetail->orderDateTime);
                $orderDateTime =  DateTime::createFromFormat('D d-M-Y h:i A',$orderDetail->orderDateTime);//new \DateTime($orderDetail->bod_date);
                $orderDateString = $orderDateTime->format('Y/m/d h:i'); 
                Yii::debug($orderDetail->orderDateTime);
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

    /*
    Helper function for saving/updating order detail and food selection
    */
    private function saveOrderandFood($orderSummary,$orderDetail,$foodSelections)
    {
        $transaction = Yii::$app->db->beginTransaction();
        
        try
        {
            if ($orderSummary->save())
            {
                $orderDetail->orderId = $orderSummary->orderId;
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
                            if (!$banquetOrderFood->save())
                            {
                                Yii::debug('not saving');
                                Yii::debug($banquetOrderFood->getErrors());
                                $msg='not sv';
                                break;
                            }
                        } else
                        {
                            $msg = 'Exy NOT';
                        }
                    }
                }
            }
            $transaction->commit();
            return $orderDetail->id;
            
        } catch (Exception $e)
        {
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

        $foodSelector = $this->loadFoodTab(0);

        $error = 'OK';
        $msg = 'start';
        if (Yii::$app->request->isPost) { /* the request method is POST */ 
            $orderDetail = new BanquetOrderDetail();
            $orderDetail->orderId = $orderId;
            if ($orderDetail->load(Yii::$app->request->post()))
            {
                $orderDateTime =  DateTime::createFromFormat('D d-M-Y h:i A',$orderDetail->orderDateTime);//new \DateTime($orderDetail->bod_date);
                $orderDateString = $orderDateTime->format('Y/m/d h:i'); 
                $orderDetail->orderDateTime = $orderDateString;

                $foodSelections = $data['BanquetOrderFood'];
               
                $this->saveOrderandFood($order,$orderDetail,$foodSelections);

                
                $url = Url::toRoute(['banquet-order/view', 'id' => $orderDetail->id]);
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

    /*

    */
    public function actionView($id)
    {
        
        $orderDetail = BanquetOrderDetail::findOne($id);
        $order = $orderDetail->order;
        $selectedFoods = $orderDetail->banquetOrderFoods;
        $query = BanquetOrderFood::find()->where(['orderDetailId' => $id]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
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
     * Deletes an existing BanquetOrder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

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
        $model->userId = Yii::$app->user->identity->id;
        $model->createdDate = new Expression('NOW()');
        $model->orderStatus = 0;
        
        return $model;
    }

    protected function findOrderDetail($id)
    {
        if (($model = BanquetOrderDetail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
