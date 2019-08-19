<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use Da\User\Model\User;
use app\models\Department;
use app\models\Profile;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 * @var dektrium\user\models\Profile $profile
 */
?>

<?php $this->beginContent('@Da/User/resources/views/admin/update.php', ['user' => $user]) ?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-9',
        ],
    ],
]); ?>



<?= $form->field($profile, 'name') ?>



<?php
	$userIds = Yii::$app->authManager->getUserIdsByRole(Yii::$app->params['staffManager']);
	$profiles = Profile::find()->where(['in', 'user_id', $userIds])->all();

?>
<?= $form->field($profile, 'managerId')->dropDownList(
        ArrayHelper::map(Profile::find()->where(['in', 'user_id', $userIds])->all(),'user_id','name'),
        ['prompt'=>'Select Manager']) 
    ?>


<?= $form->field($profile, 'departmentId')->dropDownList(
        ArrayHelper::map(Department::find()->all(),'id','departmentName'),
        ['prompt'=>'Select Department']) 
    ?>




<?= $form->field($profile,'officeNo') ?>
<?= $form->field($profile,'mobileNo') ?>
<?= $form->field($profile, 'public_email') ?>
<?= $form->field($profile, 'website') ?>

<?= $form->field($profile, 'gravatar_email') ?>
<?= $form->field($profile, 'bio')->textarea() ?>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9">
        <?= Html::submitButton(Yii::t('usuario', 'Update'), ['class' => 'btn btn-block btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
