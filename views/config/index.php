<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper; 
use yii\bootstrap\Tabs;
use app\models\FoodCategory;
use app\helper;


/* @var $this yii\web\View */
/* @var $model app\models\Food */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php
      
      $items[] = 
        ['label' => 'Order',
        'content' => $this->render('_order',['form' => $form]),
        'active' => true
      ];

      $items[] = 
      ['label' => 'Invoice',
      'content' => $this->render('_invoice',['form' => $form]),
      
    ];

    ?>
    
   
    
    <?= Tabs::widget([
        'items' => [
            [
                'label' => 'Order',
                'content' => $this->render('_order', ['form' => $form]),
                //'active' => true
            ],
            [
                'label' => 'Invoice',
                'content' => $this->render('_invoice', ['form' => $form]),
                //'active' => false
            ],
        ]]);
 ?>
    
    
    <div class="modal-footer">
       <?php echo Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    <?php ActiveForm::end(); ?>

</div>
