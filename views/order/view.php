<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\BanquetOrder */

$this->title = Html::encode($title);

?>
<div class="banquet-order-view">

    <h4 class="text-center"><?= Html::encode($title) ?></h4>
    <?php
    $wgClass = $status ? 'alert-info' : 'alert-warning';
    echo Alert::widget([
    'options' => [
        'class' => $wgClass,
    ],
    'body' => $message,
]);

?>
    

</div>
