<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ServingType */

$this->title = 'Update Serving Type: ' . $model->typeId;
$this->params['breadcrumbs'][] = ['label' => 'Serving Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->typeId, 'url' => ['view', 'id' => $model->typeId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="serving-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
