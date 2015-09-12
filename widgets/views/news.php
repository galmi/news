<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 12.09.15
 * Time: 16:53
 */

use yii\helpers\Html;
use yii\helpers\StringHelper;
?>
<?php
if ($full) {
?>
	<h1><?= Html::encode( $news->title ) ?></h1>
<?php } else { ?>
	<h3><?= Html::encode( $news->title ) ?></h3>
<?php } ?>
<p><?= date( "d-M-Y H:i:s", strtotime( $news->creation_date ) ); ?> by <?= $news->getUser()->username . '/' . $news->getUser()->email; ?> |
	<?= (Yii::$app->user->identity && ($news->getUser()->id == Yii::$app->user->identity->id))?Html::a('Delete', ['news/delete', 'id' => $news->id]) . ' | ':''; ?>
	<?= Html::a('Download PDF', ['news/pdf', 'id' => $news->id]);?>
</p>
<p>
	<?php
	if ( $news->photo ) {
		echo Html::img( '/' . $news->photo, [ 'align' => 'left', 'width'=>100, 'height'=>100, 'hspace' => "20" ] );
	}
	if ($full) {
		echo nl2br(Html::encode( $news->news ));
	} else {
		echo Html::a(Html::encode( StringHelper::truncate($news->news, 100 )), ['news/read', 'id' => $news->id]);
	}
	?>
</p>
<div class="clearfix"></div>
<hr>
<?= $full?Html::a('Back', Yii::$app->request->referrer):'';?>
