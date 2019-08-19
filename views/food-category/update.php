<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FoodCategory */

$this->title = 'Update Food Category: ' . $model->categoryId;
$this->params['breadcrumbs'][] = ['label' => 'Food Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->categoryId, 'url' => ['view', 'id' => $model->categoryId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="food-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
