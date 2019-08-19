<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ServingTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Serving Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="serving-type-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Serving Type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'typeId',
            'typeName',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
