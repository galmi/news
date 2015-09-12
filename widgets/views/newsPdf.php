<?php

use yii\helpers\Html;
?>
<h1><?= Html::encode( $news->title ) ?></h1>
<p><?= date( "d-M-Y H:i:s", strtotime( $news->creation_date ) ); ?> by <?= $news->getUser()->username . '/' . $news->getUser()->email; ?>
</p>
<table border="0">
	<tr>
		<?php
		if ( $news->photo ) {
			?>
			<td width="100px">
				<?= Html::img( $news->photo, [ 'align' => 'left', 'width'=>100, 'height'=>100, 'hspace' => "20" ] );?>
			</td>
		<?php } ?>
		<td valign="top">
			<?= nl2br(Html::encode( $news->news )); ?>
		</td>
	</tr>
</table>
<div class="clearfix"></div>
