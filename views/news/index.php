<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 12.09.15
 * Time: 16:53
 */

use app\widgets\NewsWidget;
use yii\helpers\Html;
use yii\helpers\StringHelper;

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-news">
	<h1><?= Html::encode( $this->title ) ?></h1>

	<?php if (Yii::$app->user->identity) { ?>
	<p><?= HTML::a( "Add news", [ 'news/create' ] ) ?></p>
	<?php } ?>
	<?php foreach ( $news as $row ) { ?>
		<?= NewsWidget::widget(['news' => $row, 'full' => false]); ?>
	<?php } ?>
</div>
