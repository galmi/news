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
				'only'  => [ 'logout', 'confirm' ],
				'rules' => [
					[
						'actions' => [ 'logout', 'confirm' ],
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

	public function actionRegister() {
		if ( ! \Yii::$app->user->isGuest ) {
			return $this->goHome();
		}

		$model = new User();
		$model->scenario = User::SCENARIO_REGISTER;
		if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
			if ( Yii::$app->getUser()->login( $model ) ) {
				return $this->redirect( array( 'user/confirm' ) );
			}
		}

		return $this->render( 'register', [
			'model' => $model,
		] );
	}

	public function actionConfirm() {
		if ( \Yii::$app->user->isGuest ) {
			return $this->redirect( array( 'user/login' ) );
		}

		$model = new ConfirmForm();
		if ( $model->load( Yii::$app->request->post() ) && $model->confirmAuthKey() ) {
			return $this->goHome();
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