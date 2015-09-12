<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="alternate" type="application/rss+xml" title="RSS Feed for news publishing portal" href="<?= Url::toRoute(['news/rss'], true) ?>" />
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $navWidgetItems = [
        [ 'label' => 'Home', 'url' => [ '/news/index' ] ],
    ];
    $isGuest = Yii::$app->user->isGuest;
    if ( $isGuest ) {
	    $navWidgetItems[] = [ 'label' => 'Sign up', 'url' => [ '/user/signup' ] ];
	    $navWidgetItems[] = [ 'label' => 'Login', 'url' => [ '/user/login' ] ];
    } else {
	    if ( ! Yii::$app->getUser()->getIdentity()->isConfirmed() ) {
		    $navWidgetItems[] = [ 'label' => 'Confirm', 'url' => [ '/user/confirm' ] ];
	    } else {
            $navWidgetItems[] = [ 'label' => 'My news', 'url' => [ '/news/my' ] ];
        }
	    $navWidgetItems[] = [
		    'label'       => 'Logout (' . Yii::$app->user->identity->username . ')',
		    'url'         => [ '/user/logout' ],
		    'linkOptions' => [ 'data-method' => 'post' ]
	    ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $navWidgetItems,
    ]);

    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
