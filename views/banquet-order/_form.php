<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use kartik\date\DatePicker;

use app\models\Location;
use app\models\ServingType;
use app\models\BanquetOrderDetail;

use kartik\datetime\DateTimePicker;
//use kartik\datetime\DatePicker;



/* @var $this yii\web\View */
/* @var $model app\models\BanquetOrder */
/* @var $form yii\widgets\ActiveForm */


    $locations = ArrayHelper::map(Location::find()->all(),'locationId','locationName');
?>


<div class="banquet-order-form">

    <?php $form = ActiveForm::begin(); ?>

     <?= $form->errorSummary($model); ?>
     

    
    
    

    <?php 
     $curDate = new DateTime();
    $curDate->add(new DateInterval('P3D'));
    $defaultDate = $curDate->format('d-M-Y');
    
    /*

    echo DatePicker::widget([
        'model' => $model,
        'attribute' => 'orderDate'
    ]);
*/
    //echo $defaultDate;
    echo '<label class="control-label">Event Time</label>';
    
    echo DatePicker::widget([
    'name' => 'orderDate',
    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    'value' => date_format (date_create($model->orderDate),'d-M-Y') /*$defaultDate'23-Feb-1982 10:01'*/,
    'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'dd-M-yyyy'
    ]
]);
    
   
   ?>

    <?= $form->field($model, 'orderPurpose')->textInput() ?>
    <?= $form->field($model, 'orderNote')->textarea(['rows' => 6]) ?>

    


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    if (!$model->isNewRecord)
    {

    

    echo GridView::widget([
    'dataProvider' => $orderDetailDP,
    'columns' => [
        'locationId',
        'note',
        'paxCount',
        'orderDateTime:datetime',
        
    ],
]) ;
    };
?>



<?php
/*
Modal::begin([

    'toggleButton' => [

        'label' => '<i class="glyphicon glyphicon-plus"></i> Add',

        'class' => 'btn btn-success'

    ],

    'closeButton' => [

      'label' => 'Close',

      'class' => 'btn btn-danger btn-sm pull-right',

    ],

    'size' => 'modal-lg',

]);
*/

?>





</div>
