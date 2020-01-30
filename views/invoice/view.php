<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\tabs\TabsX;


/* @var $this yii\web\View */
/* @var $model app\models\Invoice */

$this->title = 'Invoice :' .$invoice->invoiceNo;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="invoice-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $invoice->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $invoice->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $invoice,
        'attributes' => [
            'id',
            'orderId',
            'invoiceDate',
            'customerId',
            'invoiceNo',
            'note:ntext',
            'totalAmount',
            'discount',
            'netAmount',
            'invoiceStatus',
            'dueDate',
            'amountReceived',
        ],
    ]) ?>

</div>
<?php
// Small
    $items[] = 
    ['label' => 'Order',
    'content' => $this->render('_order',['invoice' => $invoice,'orderData' => $orderData]),
    'active' => true
    ];

    $items[] = 
    ['label' => 'Payment',
    'content' => $this->render('_payment',['invoice' => $invoice]),

    ];
    echo TabsX::widget([
        'items'=>$items,
        'position'=>TabsX::POS_ABOVE,
        'height'=>TabsX::SIZE_SMALL,
        'bordered'=>true,
        'encodeLabels'=>false
    ]);

?>