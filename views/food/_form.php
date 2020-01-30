<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper; 
use kartik\number\NumberControl;

use app\models\FoodCategory;

/* @var $this yii\web\View */
/* @var $model app\models\Food */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="food-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'foodName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'foodCategoryId')->dropDownList(
            ArrayHelper::map(FoodCategory::find()->all(),'categoryId','categoryName'),
            ['prompt'=>'Select Category']
       )?> 

    <?php
        $dispOptions = ['class' => 'form-control kv-monospace'];
        $saveCont = ['class' => 'kv-saved-cont'];
         
        $saveOptions = [
            'type' => 'text', 
            'label'=>'<label>Saved Value: </label>', 
            'class' => 'kv-saved',
            'readonly' => true, 
            'tabindex' => 1000
        ];

            
        echo $form->field($model, 'price')->widget(NumberControl::classname(), [
            'maskedInputOptions' => [
                
                'allowMinus' => false
            ],
            'options' => $saveOptions,
            'displayOptions' => $dispOptions
        ]);
        ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
