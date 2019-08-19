<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BanquetOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banquet-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'orderId') ?>

    <?= $form->field($model, 'locationId') ?>

    <?= $form->field($model, 'userId') ?>

    <?= $form->field($model, 'createdDate') ?>

    <?= $form->field($model, 'orderDate') ?>

    <?php // echo $form->field($model, 'noOfPax') ?>

    <?php // echo $form->field($model, 'serviceTypeId') ?>

    <?php // echo $form->field($model, 'orderNote') ?>

    <?php // echo $form->field($model, 'orderStatus') ?>

    <?php // echo $form->field($model, 'orderTime') ?>

    <?php // echo $form->field($model, 'orderHour') ?>

    <?php // echo $form->field($model, 'orderMinute') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
