<?php

use app\models\Invoice;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Invoice', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
            [
                'attribute' => 'invoiceNo',
                'label' => 'Invoice No.',
                'format' => 'raw',
                'value' => function($data)
                {
                    //return Html::a($data['invoiceNo'], ['invoice/detail']);
                    return Html::a($data->invoiceNo, ['invoice/view','id' => $data->invoiceNo], ['data-pjax' => 0]);


                }
            ],
            
            'invoiceDate',
            [
                'label' => 'Customer',
                'attribute' => 'customer.profile.name',
            ],
            
            
            'totalAmount',
            'amountReceived',
            'discount',
            'netAmount',
            [
                'label' => 'Status',
                'attribute' => 'invoiceStatus',
                'value' => function($model,$key,$index,$widget){
                    return Invoice::getStatusText($model->invoiceStatus);
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => Invoice::invoiceStatusArray(),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Any Status'],
                'group' => false,  // enable grouping

            ],
            //'note:ntext',
            //'totalAmount',
            //'discount',
            //'netAmount',
            //'invoiceStatus',
            //'dueDate',
            //'amountReceived',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
