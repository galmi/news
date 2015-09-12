<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 12.09.15
 * Time: 16:43
 */

namespace app\controllers;

use app\models\News;
use app\models\User;
use app\widgets\NewsWidget;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;

class NewsController extends Controller {

	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only'  => [ 'my', 'create', 'delete' ],
				'rules' => [
					[
						'allow' => true,
						'roles' => [ '@' ],
					],
				],
			],
		];
	}

	public function actionIndex() {
		$news = News::find()->limit(10)->orderBy('creation_date desc')->all();
		return $this->render( 'index', array( 'news' => $news, 'title' => 'All news' ) );
	}

	public function actionRead( $id ) {
		/** @var News $news */
		$news = News::findOne($id);
		return $this->render( 'read', array( 'news' => $news ) );
	}

	public function actionMy() {
		/** @var User $user */
		$user = \Yii::$app->user->identity;
		$news = $user->getNews();

		return $this->render( 'index', array( 'news' => $news, 'title' => 'My news' ) );
	}

	public function actionCreate() {
		$model = new News();

		if ( $model->load( \Yii::$app->request->post() ) ) {
			$model->file = UploadedFile::getInstance($model, 'file');
			$model->insert();
			return $this->redirect( array( 'news/my' ) );
		}

		return $this->render( 'create', array( 'model' => $model ) );
	}

	public function actionDelete( $id ) {
		/** @var News $news */
		$news = News::findOne($id);
		if ($news && $news->user_id == \Yii::$app->user->identity->id) {
			$news->delete();
		}
		return $this->goBack(['news/my']);
	}

	public function actionRss() {
		$dataProvider = new ActiveDataProvider([
			'query' => News::find()->orderBy('creation_date desc'),
			'pagination' => [
				'pageSize' => 10
			],
		]);

		$response = \Yii::$app->getResponse();
		$headers = $response->getHeaders();

		$headers->set('Content-Type', 'application/rss+xml; charset=utf-8');

		$response->content = \Zelenin\yii\extensions\Rss\RssView::widget([
			'dataProvider' => $dataProvider,
			'channel' => [
				'title' => \Yii::$app->name,
				'link' => Url::toRoute('/', true),
				'description' => 'Posts ',
				'language' => \Yii::$app->language
			],
			'items' => [
				'title' => function ($model, $widget) {
					return $model->title;
				},
				'description' => function ($model, $widget) {
					return StringHelper::truncateWords($model->news, 50);
				},
				'link' => function ($model, $widget) {
					return Url::toRoute(['news/read', 'id' => $model->id], true);
				},
				'author' => function ($model, $widget) {
					return $model->getUser()->email . ' (' . $model->getUser()->username . ')';
				},
				'guid' => function ($model, $widget) {
					$date = \DateTime::createFromFormat('Y-m-d H:i:s', $model->creation_date);
					return Url::toRoute(['news/read', 'id' => $model->id], true) . ' ' . $date->format(DATE_RSS);
				},
				'pubDate' => function ($model, $widget) {
					$date = \DateTime::createFromFormat('Y-m-d H:i:s', $model->creation_date);
					return $date->format(DATE_RSS);
				}
			]
		]);
	}

	public function actionPdf($id)
	{
		/** @var News $news */
		$news = News::findOne($id);

		$pdf = new \mPDF();
		$response = \Yii::$app->getResponse();
		$headers = $response->getHeaders();

		$headers->set('Content-Type', 'application/pdf; charset=utf-8');

		$html = NewsWidget::widget(['news' => $news, 'pdf' => true]);
		$pdf->WriteHTML($html);
		$response->content = $pdf->Output();
	}
}