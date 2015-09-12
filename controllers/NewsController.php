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
use yii\filters\AccessControl;
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

	public function actionFeed() {

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
}