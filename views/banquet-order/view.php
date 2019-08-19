<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BanquetOrder */

$this->title = $model->orderId;
$this->params['breadcrumbs'][] = ['label' => 'Banquet Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banquet-order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->orderId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->orderId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'orderId',
            'locationId',
            'userId',
            'createdDate',
            'orderPurpose',
            'orderDate',
            'noOfPax',
            
            'orderNote:ntext',
            'orderStatus',
            'orderTime:datetime',
            'orderHour',
            'orderMinute',
        ],
    ]) ?>

</div>
