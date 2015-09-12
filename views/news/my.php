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

$this->title = 'My news';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-news">
	<h1><?= Html::encode( $this->title ) ?></h1>

	<p><?= HTML::a( "Add news", [ 'news/create' ] ) ?></p>
	<?php foreach ( $news as $row ) { ?>
		<?= NewsWidget::widget(['news' => $row, 'full' => false]); ?>
		<hr>
	<?php } ?>
	<code><?= __FILE__ ?></code>
</div>
