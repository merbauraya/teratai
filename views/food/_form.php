<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
 use yii\helpers\ArrayHelper; 
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

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
