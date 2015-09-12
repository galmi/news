<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 12.09.15
 * Time: 18:00
 */

namespace app\widgets;


use app\models\News;
use yii\base\Widget;

class NewsWidget extends Widget {

	public $news;
	public $full = false;

	public function run() {
		return $this->renderFile(__DIR__ . '/views/news.php', ['news' => $this->news, 'full' => $this->full]);
	}

}