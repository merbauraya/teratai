<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Contact', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/user/registration/register']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/user/security/login']];
    } else {
        if (Yii::$app->user->can(Yii::$app->params['dataAdmin']))
        {
			$menuItems[] =  [
            'label' => 'Master Data',
            'items' => [
                 ['label' => 'Location', 'url' => '/location'],
                 ['label' => 'Serving Type', 'url' => '/serving-type'],
                 ['label' => 'Food Category', 'url' => '/food-category'],
                 ['label' => 'Food', 'url' => '/food'],
                 ['label' => 'Department', 'url' => '/department'],
                 ['label' => 'Settings', 'url' => '/config'],
                 
            ]];
            
            $menuItems[]= [
            
            'label' => 'Order Management',
            'items' => [
                 ['label' => 'Order Summary', 'url' => '/banquet-order/order-summary'],
                 ['label' => 'Food Summary', 'url' => '/banquet-order/food-summary']
             ]
             
             
             
            ];
            
            
			
			
		}
		if (Yii::$app->user->can(Yii::$app->params['canOrderPermission']))
		{
			$menuItems[] = ['label' => 'Order',
            'items' => [
                 ['label' => 'Create Order', 'url' => '/banquet-order/create'],
                 ['label' => 'My Order', 'url' => '/banquet-order/']]
             ];
		}
		/*
		if (\Da\User\Helper\AuthHelper->isAdmin(Yii::$app->user->identity->username))
		{
			$menuItems[] = ['label' => 'User Management','url' => '/user/admin/index'];
		}
		*/
        $menuItems[] = ['label' => 'Sign out (' . Yii::$app->user->identity->username . ')',
					'url' => ['/user/security/logout'],
					'linkOptions' => ['data-method' => 'post']];
      
        
    }
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        
        /*
        [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ?
				['label' => 'Sign in', 'url' => ['/user/security/login']] :
				['label' => 'Sign out (' . Yii::$app->user->identity->username . ')',
					'url' => ['/user/security/logout'],
					'linkOptions' => ['data-method' => 'post']],
				['label' => 'Register', 
					'url' => ['/user/registration/register'], 
					'visible' => Yii::$app->user->isGuest]
        ],*/
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Howzat Creation <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
