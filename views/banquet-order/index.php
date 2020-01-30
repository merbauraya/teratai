<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use app\models\Location;
use app\models\ServingType;
use app\models\BanquetOrder;
use app\src\helper\AppHelper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BanquetOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banquet Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banquet-order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    

    <p>
        <?= Html::a('New Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
   
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsive'=>true,
        'panel' => ['type' => 'primary', 'heading' => 'Order Summary'],
        
        'floatHeaderOptions'=>['scrollingTop'=>'50'],
        'toolbar'=>[
            '{export}',
            '{toggleData}'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'order.orderPurpose',
                'group' => true,
            ],
            [
                'attribute' => 'orderDateTime',
                'value' => 'formattedDateTimeString',
                 'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'size' => 'xs',
                    'pluginOptions' => [
                        'format' => 'dd-M-yyyy',
                        'autoWidget' => true,
                        'autoclose' => true,
                        'todayHighlight' => true
                    ]
                ],
            ],
          
            [
                'label' => 'Location',
                'attribute' => 'locationId',
                'value' => function($model,$key,$index,$widget){
                    return $model->location->locationName;
                },
                'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(Location::find()->orderBy('locationName')->asArray()->all(), 'locationId', 'locationName'), 
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Any location'],
            'group' => false,  // enable grouping

            ],
            /* this also works
            [
                'attribute' => 'userId',
                'label' => 'User',
                'value' => function($model,$key,$index,$widget){
                    return $model->order->createdBy->profile->name;
                },
            ]
            */
            
            'order.createdBy.profile.name',
            'paxCount',

            
            [

                'attribute' => 'serveTypeId',

               'value' => function($model,$key,$index,$widget){
                   return $model->serveType->typeName;
               },
               'filterType' => GridView::FILTER_SELECT2,
               'filter' => ArrayHelper::map(ServingType::find()->orderBy('typeName')->asArray()->all(), 'typeId', 'typeName'), 
               'filterWidgetOptions' => [
                   'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Any type'],
               'group' => false,  // enable grouping

            ],
            [
                'attribute' =>'orderStatus',
                'value' => function($model,$key,$index,$widget){
                    return BanquetOrder::getOrderStatusText($model->orderStatus);
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => BanquetOrder::orderStatusArray(),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Any Status'],
                'group' => false,  // enable grouping
            ],
           
                   
            //'noOfPax',
            //'serviceTypeId',
            //'orderNote:ntext',
            //'orderStatus',
            //'orderTime:datetime',
            //'orderHour',
            //'orderMinute',
           

            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'template' => '{view} {update} {delere}',
                'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return $model->isEditable();
                    },
                    'delete' => function ($model, $key, $index) {
                        return $model->isEditable();
                    }
        
                ],
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
