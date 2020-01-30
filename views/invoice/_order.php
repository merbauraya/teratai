<?php
use kartik\grid\GridView;



echo GridView::widget([
    'dataProvider' => $orderData,
    
    
    'pjax' => true,
    'striped' => true,
    'hover' => true,
    'panel' => ['type' => 'primary', 'heading' => 'Order List'],
    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],
        
        
        [
            'attribute' => 'orderPurpose', 
            'width' => '250px',
            'group' => true,

            
        ],
        'orderId',
        
        [
            'attribute' => 'orderDateTime',
            'value' => 'formattedDateTimeString',

            
            
        ],
        [
            'attribute' => 'locationId',
            'value' => function ($model, $key, $index, $widget) { 
                return $model->location->locationName;

            },
        ],
        'paxCount',
        'order.createdBy.profile.name',
        
        [

            'attribute' => 'serveTypeId',

            'value' => function($model,$key,$index,$widget){
                return $model->serveType->typeName;
            },
          

        ],
        
    ],
]);


?>