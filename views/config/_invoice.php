<?php

use yii\helpers\Html;
use app\helper\AppHelper;

?>

<div class="configSection">

    <div class="form-group">
        <?php
            $checkboxId = 'autoSendInvoice';
            $checked = AppHelper::getAutoSendInvoice()== false ?false:true;
            $options = ['label' => 'Send invoice automatically'];
        ?>
        <?= Html::checkbox($checkboxId,$checked,$options); ?>
    
    </div>
    
    <div class="form-group">
    <?php
        $invoiceOptions = array(0 => 'Individu Order',
                                1 => 'Group By Person In Charge');
        $options = [];
    
    
        echo Html::label('Invoice Grouping');
  
        echo Html::radioList('invoiceGrouping', AppHelper::getInvoiceGrouping(), $invoiceOptions, $options);

        $invoiceSendOptions = [
            0 => 'Daily',
            1 => 'Weekly',
            2 => 'Bi Weekly',
            3 => 'Manual'
        ];

        echo Html::label('Send Invoice Frequency');
        echo Html::radioList('invoiceSendOption', AppHelper::getInvoiceSendOption(), $invoiceSendOptions, $options);

    ?>

    
    </div>
    <div class="form-group">
        <?php
            $invoiceNumberFormats = [
                1 => 'Running Number',
                2 => 'Start with Year/Month and Running Number'
            ];

            echo Html::label('Invoice Number Format');
            echo Html::radioList('invoiceNumberFormat', AppHelper::getInvoiceNumberFormat(), $invoiceNumberFormats, $options);
        ?>

    </div>
    <div class="form-group">
        <?= Html::label('Invoice Number Prefix ', 'invoiceNoPrefix') ?>
        <?= Html::textInput('invoiceNoPrefix', AppHelper::getInvoiceNumberPrefix(), ['class' => 'form-control']) ?>
    
   </div> 
   <div class="form-group">
        <?= Html::label('Invoice Number Digit Size ', 'invoiceNoDigitSize') ?>
        <?= Html::textInput('invoiceNoDigitSize', AppHelper::getInvoiceNoDigitSize(), ['class' => 'form-control']) ?>
    
   </div> 

    

</div>