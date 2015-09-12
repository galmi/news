<?php

namespace app\controllers;

use app\models\ConfirmForm;
use app\models\LoginForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class UserController extends Controller {

	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only'  => [ 'logout' ],
				'rules' => [
					[
						'actions' => [ 'logout' ],
						'allow'   => true,
						'roles'   => [ '@' ],
					],
				],
			],
			'verbs'  => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'logout' => [ 'post' ],
				],
			],
		];
	}

	public function actionSignup() {
		if ( ! \Yii::$app->user->isGuest ) {
			return $this->goHome();
		}

		$model = new User();
		$model->scenario = User::SCENARIO_REGISTER;
		if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
			return $this->redirect( array( 'user/needconfirm' ) );
		}

		return $this->render( 'register', [
			'model' => $model,
		] );
	}

	public function actionNeedconfirm()
	{
		return $this->render('needconfirm');
	}

	public function actionConfirm($id) {
		if ( !\Yii::$app->user->isGuest ) {
			return $this->goHome();
		}
		$user = User::findIdentityByAuthKey($id);
		if (!$user) {
			return $this->goHome();
		}
		$model = new ConfirmForm();
		$model->user = $user;
		$model->authKey = $id;
		if ( $model->load( Yii::$app->request->post() ) && $model->confirmAuthKey() ) {
			return $this->redirect(['user/login']);
		}

		return $this->render( 'confirm', [
			'model' => $model,
		] );
	}

	public function actionLogin() {
		if ( ! \Yii::$app->user->isGuest ) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ( $model->load( Yii::$app->request->post() ) && $model->login() ) {
			if ( !\Yii::$app->user->identity->isConfirmed() ) {
				return $this->redirect( array( 'user/confirm' ) );
			}
			return $this->goBack();
		}

		return $this->render( 'login', [
			'model' => $model,
		] );
	}

	public function actionLogout() {
		Yii::$app->user->logout();

		return $this->goHome();
	}

}