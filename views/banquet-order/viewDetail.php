<?php

use DateTime;

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\BanquetOrder */

$this->title = $orderDetail->id;
$this->params['breadcrumbs'][] = ['label' => 'Banquet Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banquet-order-view">

    
    
        <h1>Order Id: <?= Html::encode($this->title) ?></h1>
    <p>

        <?= Html::a('Edit Order', ['banquet-order/update', 'id' => $orderDetail->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Add Sub Order', ['banquet-order/add-detail', 'id' => $order->orderId], ['class' => 'btn btn-success']) ?>

        <?= Html::a('Delete Order', ['delete', 'id' => $orderDetail->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

   

    <div>
        
    <?= DetailView::widget([
        'model' => $orderDetail,
        'attributes' => [
            [
                'label' => 'Name',
                'value' => function ($model){
                    return $model->order->createdBy->profile->name;
                }
            ],
            [
                'label' => 'Department',
                'value' => function ($model){
                    return $model->order->createdBy->profile->department->departmentName;
                }
            ],
            
            
            [
                'label' => 'Purpose',
                'value' => $orderDetail->order->orderPurpose,
                
            ],
            
           
            [
                'attribute' => 'bod_date',
                'value' => $orderDetail->formattedDateString,
                'label' => 'Order Date'
            ],
            
            [
                'attribute' => 'bod_date',
                'value' => $orderDetail->formattedTimeString,
                'label' => 'Order Time'
            ],
            
            [
                'attribute' => 'bod_location_id',
                'value' =>  $orderDetail->location->locationName,
                'label' => 'Location'
                
            ],
            [
                'attribute' => 'paxCount',
                
                'label' => 'Pax Count'
                
            ],
            [
                'attribute' => 'bod_served_type',
                'value' =>  $orderDetail->serveType->typeName,
                'label' => 'Serving Type'
                
            ],
            [
                'attribute' => 'note',
                'label' => 'Note'
                
            ],
            [
                'label' => 'Created Date',
                'value' => $orderDetail->order->createdDate,
                
            ],
            [
                'label' => 'Manager',
                'value' => function ($model){
                    $model->order->createdBy->profile->managerProfile->user_id;
                }
                
            ],
            
            
           
        

        ],
    ]) ?>
    
    </div>
    <div>
    

    <p><b>Food Summary</b></p>
    <?= GridView::widget([
        'dataProvider' => $selectedFoodsDP,
        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           
            [
                'value' => function($model){
                    return $model->food->foodName;
                }
            ],

            'paxCount',

            
            [
                'label' => 'Serving Type',
                'value' => function($model){
                    return $model->serveType->typeName;
                }
            ],
            
            //'noOfPax',
            //'serviceTypeId',
            //'orderNote:ntext',
            //'orderStatus',
            //'orderTime:datetime',
            //'orderHour',
            //'orderMinute',

        ],
    ]); ?>
    
    </div>


</div>
