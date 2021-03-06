<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Tabs;
use yii\widgets\DetailView;

use app\models\Location;
use app\models\ServingType;
use app\models\BanquetOrder;

use kartik\datetime\DateTimePicker;
?>

<div class="banquet-order-form">
<?php $form = ActiveForm::begin([
    'enableClientValidation' => true

]); ?>


<?= DetailView::widget([
        'model' => $orderSummary,
        'attributes' => [
            'orderId',
                      
            'userId',
            'createdDate',
            'orderPurpose',
            'orderDate',
            
            
            'orderNote:ntext',
            
            [
            'label'  => 'Status',
            'value'  => function ($data) {
                return BanquetOrder::getOrderStatusText($data->orderStatus);
            },
            
        ],
            
    
  
        ],
    ]) ?>    
    
    
    <?= $form->field($orderSummary, 'orderPurpose')->textInput() ?>
<?php
    
    
    
  //  var_dump($msg);
   // var_dump($foods);
   // echo 'KK';
    $locations = ArrayHelper::map(Location::find()->all(),'locationId','locationName');

    $curDate = new DateTime();
    $orderDate = $curDate->add(new DateInterval('P3D'));
    $defaultDate = $curDate->format('d-M-Y H:G');
    
    if (!$orderDetail->isNewRecord)
    {
        $orderDate = DateTime::createFromFormat('Y-m-d H:i:s',$orderDetail->orderDateTime);

    }
    
    
    //echo $defaultDate;
    echo '<label class="control-label">Event Time</label>';
    echo DateTimePicker::widget([
       
    'name' => 'BanquetOrderDetail[orderDateTime]',
    'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
    'value'=> $orderDate->format('D d-M-Y h:i A'),
    'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'D dd-M-yyyy HH:ii P'
    ]
]);

    ?>

  
 
 
 
 
<?= $form->field($orderDetail, 'locationId')->dropDownList(
        ArrayHelper::map(Location::find()->all(),'locationId','locationName'),
        ['prompt'=>'Select Location']) 
    ?>


<?= $form->field($orderDetail, 'paxCount')->textInput() ?>

<?= $form->field($orderDetail, 'serveTypeId')->dropDownList(
    ArrayHelper::map(ServingType::find()->all(),'typeId','typeName'),
    ['prompt'=>'Serving Type']) 
?>

<?= $form->field($orderDetail, 'note')->textarea(['rows' => 4]) ?>

<?php echo Tabs::widget([
    'items' => $foodSelector]);
    ?>
<div class="modal-footer">
       <?php echo Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>




<?php ActiveForm::end(); ?>

</div>


