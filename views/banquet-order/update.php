<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BanquetOrder */

$this->title = 'Update Order';
$this->params['breadcrumbs'][] = ['label' => 'Banquet Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banquet-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formDetail', [
        'orderSummary' => $orderSummary,
        'orderDetail' => $orderDetail,
        'foodSelector' => $foodSelector
    ]) ?>

</div>
