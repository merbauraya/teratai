<?php
    use yii\helpers\Html;
    use app\models\BanquetOrderFood;
    use yii\helpers\ArrayHelper;
    use yii\grid\GridView;

    use app\models\ServingType;
    $model = new BanquetOrderFood;
 
    $items = ArrayHelper::map($data, 'id', 'foodName');
    //var_dump($items);
?>

<div class="foodSelector">


    <table class='table table-striped table-bordered'>
        <thead>
            <tr>
            <th>Food</th>
            <td>Pax</td>
            <td>Serving Type</td>
            </tr>
    </thead>
   
    <?php foreach ($data as $item){
        
        $checked = $item['foodId'] != null ? true : false;
        //$checkboxId = 'BanquetOrderFood['. $item['id'] .'][foodId]';
        $checkboxId = 'BanquetOrderFood['.$item['id'].'][foodId]';
        //$paxCountId = 'BanquetOrderFood['. $item['id'] .'][pax_count]';
        $paxCountId = 'BanquetOrderFood['.$item['id'].'][paxCount]';
        $serveTypeId = 'BanquetOrderFood['.$item['id'].'][serveTypeId]';
        
    ?>
        <tr>
            <td>
                <?php
                    $options = ['label' => $item['foodName'],
                    'value' => $item['id'],
                    'checked' => $item['bofId'] !=null ? true:false
                ];
                echo Html::checkbox($checkboxId,$checked,$options); ?>
            </td>
            <td>
                <?php
                echo Html::textInput($paxCountId, $item['paxCount'])
                ?>
            </td>
            <td>
                <?php
                $selected = $item['serveTypeId'];
                
                echo Html::dropDownList($serveTypeId,$selected,
                ArrayHelper::map(ServingType::find()->all(),'typeId','typeName'),
                ['text' => 'Select Serving Type']);
                ?>
            </td>
        </tr>
    <?php } ?>
</table>






</div>