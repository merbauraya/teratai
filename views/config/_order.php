<?php

use yii\helpers\Html;
use yii\widgets\MaskedInput;
use kartik\number\NumberControl;
use app\helper\AppHelper;



?>
<div class="configSection">
    <div class="form-group">
        <?php
            $checkboxId = 'orderRequireApproval';
            $checked = AppHelper::orderRequireApproval() == false ?false:true;
            $options = ['label' => 'Require order approval'];
        ?>
        <?= Html::checkbox($checkboxId,$checked,$options); ?>
    </div>
    <div class="form-group">
        <?= Html::label('Approval Email Subject ', 'approvalEmailSubject') ?>
        <?= Html::textInput('approvalEmailSubject', AppHelper::getEmailApprovalSubject(), ['class' => 'form-control']) ?>
    
   
  
  
  </div>
  <div class="form-group">
        <?= Html::label('Approval Email From ', 'approvalEmailFrom') ?>
        <?= Html::textInput('approvalEmailFrom', AppHelper::getEmailApprovalFrom(), ['class' => 'form-control']) ?>
    
   </div> 
    
    <?php
        $checkboxId = 'orderRequireVerification';
        $checked = AppHelper::getRequireOrderVerification()== false ?false:true;
        $options = ['label' => 'Order Require customer verification'];
    ?>
    <div class="form-group">
        <?= Html::checkbox($checkboxId,$checked,$options); ?>
    </div>
    <div class="form-group" id="verficationSubject">
        <?= Html::label('Verification Email Subject ', 'orderVerificationEmailSubject') ?>
        <?= Html::textInput('orderVerificationEmailSubject', AppHelper::getEmailOrderVerificationSubject(), ['class' => 'form-control']) ?>
    </div>
    <div class="form-group">
        <?php
            
            echo  Html::label('Number of day order get automatically verified', 'approvalEmailFrom') ;

            echo MaskedInput::widget([
                'name' => 'dayOrderGetVerified',
                'mask' => '9',
                'value' => AppHelper::getDaysOrderAutoVerified(),
                'clientOptions' => ['repeat' => 10, 'greedy' => false]
            ]);

        ?>
    </div>

    

  </div>