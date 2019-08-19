<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\BanquetOrder */

$this->title = 'Update Banquet Order: ' . $model->orderId;
$this->params['breadcrumbs'][] = ['label' => 'Banquet Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->orderId, 'url' => ['view', 'id' => $model->orderId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="banquet-order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'orderDetailDP' => $orderDetailDP
    ]) ?>

<p>Order Detail</p>
<?= GridView::widget([
    'dataProvider' => $orderDetailDP,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        
        [
            'label' => 'Location',       
            'value' => function ($model){
                return $model->location->locationName;
            }

        ],
        'bod_time',
        'paxCount',
        'orderDateTime:datetime',
        ['class' => 'yii\grid\ActionColumn',
        'template' => '{detailView}{detailUpdate}{detailDelete}',
        'headerOptions' => ['style' => 'color:#337ab7'],
        'header' => 'Actions',
        'buttons'  => [
            'detailView'   => function ($url, $model) {
                $url = Url::toRoute(['banquet-order/detail-view', 'id' => $model->id]);
               return Html::a('<span class="btn btn-sm btn-default"><b class="glyphicon glyphicon-eye-open"></b></span>', 
                ['detail-view', 'id' => $model['id']], ['title' => 'View Detail', 'id' => 'modal-btn-view']); 
              //return Html::a('<span class="fa fa-eye"></span>', $url, ['title' => 'view']);
            },
            'detailUpdate' => function ($url, $model) {
                $url = Url::to(['banquet-order/detail-update', 'id' => $model->id]);
                return Html::a('<span class="btn btn-sm btn-default"><b class="glyphicon glyphicon-pencil"></b></span>', 
                ['detail-update', 'id' => $model['id']], ['title' => 'Edit Detail', 'id' => 'modal-btn-view']); 

                return Html::a('<span class="fa fa-pencil"></span>', $url, ['title' => 'update']);
            },
            'detailDelete' => function ($url, $model) {
                $url = Url::to(['banquet-order/detail-delete', 'id' => $model->id]);
                return Html::a('<span class="btn btn-sm btn-default"><b class="glyphicon glyphicon-trash"></b></span>', 
                ['detail-update', 'id' => $model['id']], 
                    ['title' => 'Delete Detail', 
                    'id' => 'modal-btn-view',
                    'data-confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                    'data-method' => 'post']); 

                return Html::a('<span class="fa fa-trash"></span>', $url, [
                    'title'        => 'delete',
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method'  => 'post',
                ]);
            },
    
        ],

       
        
      ],

    ]
]) ?>




</div>
