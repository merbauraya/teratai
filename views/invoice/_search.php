<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'orderId') ?>

    <?= $form->field($model, 'invoiceDate') ?>

    <?= $form->field($model, 'customerId') ?>

    <?= $form->field($model, 'invoiceNo') ?>

    <?php // echo $form->field($model, 'note') ?>

    <?php // echo $form->field($model, 'totalAmount') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'netAmount') ?>

    <?php // echo $form->field($model, 'invoiceStatus') ?>

    <?php // echo $form->field($model, 'dueDate') ?>

    <?php // echo $form->field($model, 'amountReceived') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
