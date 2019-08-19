<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper; 
use app\models\FoodCategory;
use app\helper;


/* @var $this yii\web\View */
/* @var $model app\models\Food */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
    <?php
        $checkboxId = 'orderRequireApproval';
        $checked = Yii::$app->config->get('orderRequireApproval', 0)== false ?false:true;
        $options = ['label' => 'Require order approval'];
    ?>
    <?= Html::checkbox($checkboxId,$checked,$options); ?>
  </div>
  <div class="form-group">
  <?= Html::label('Approval Email Subject ', 'approvalEmailSubject') ?>
    <?= Html::textInput('approvalEmailSubject', Yii::$app->config->get('approvalEmailSubject'), ['class' => 'form-control']) ?>
    
   
  
  
  </div>
  <div class="form-group">
    <?= Html::label('Approval Email From ', 'approvalEmailFrom') ?>
    <?= Html::textInput('approvalEmailFrom', Yii::$app->config->get('approvalEmailFrom'), ['class' => 'form-control']) ?>
    
   </div> 
    
      <?php
        $checkboxId = 'invoiceRequireVerification';
        $checked = Yii::$app->config->get('invoiceRequireVerification', 0)== false ?false:true;
        $options = ['label' => 'Invoice Require customer verification'];
    ?>
    <?= Html::checkbox($checkboxId,$checked,$options); ?>
    <div class="form-group" id="verficationSubject">
    <?= Html::label('Verification Email Subject ', 'orderVerificationEmailSubject') ?>
    <?= Html::textInput('orderVerificationEmailSubject', Yii::$app->config->get('orderVerificationEmailSubject'), ['class' => 'form-control']) ?>
  

    </div>
    <div class="form-group">
    <?php
        $checkboxId = 'autoSendInvoice';
        $checked = Yii::$app->config->get('autoSendInvoice', 0)== false ?false:true;
        $options = ['label' => 'Send invoice automatically'];
    ?>
    <?= Html::checkbox($checkboxId,$checked,$options); ?>
    
    </div>
    
    
    <div class="modal-footer">
       <?php echo Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    <?php ActiveForm::end(); ?>

</div>
