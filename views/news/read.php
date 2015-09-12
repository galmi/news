<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 12.09.15
 * Time: 16:53
 */

use yii\helpers\Html;
use app\widgets\NewsWidget;

$this->title = $news->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-news">
	<?= NewsWidget::widget(['news' => $news, 'full' => true]); ?>
</div>
