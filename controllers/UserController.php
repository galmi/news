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

	/**
	 * @return string|\yii\web\Response
	 */
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

	/**
	 * @return string
	 */
	public function actionNeedconfirm() {
		return $this->render( 'needconfirm' );
	}

	/**
	 * @param $id
	 *
	 * @return string|\yii\web\Response
	 */
	public function actionConfirm( $id ) {
		if ( ! \Yii::$app->user->isGuest ) {
			return $this->goHome();
		}
		$user = User::findIdentityByAuthKey( $id );
		if ( ! $user ) {
			return $this->goHome();
		}
		$model = new ConfirmForm();
		$model->user = $user;
		$model->authKey = $id;
		if ( $model->load( Yii::$app->request->post() ) && $model->confirmAuthKey() ) {
			return $this->redirect( [ 'user/login' ] );
		}

		return $this->render( 'confirm', [
			'model' => $model,
		] );
	}

	/**
	 * @return string|\yii\web\Response
	 */
	public function actionLogin() {
		if ( ! \Yii::$app->user->isGuest ) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ( $model->load( Yii::$app->request->post() ) && $model->login() ) {
			return $this->goHome();
		}

		return $this->render( 'login', [
			'model' => $model,
		] );
	}

	/**
	 * @return \yii\web\Response
	 */
	public function actionLogout() {
		Yii::$app->user->logout();

		return $this->goHome();
	}

	public function resendAction( $email ) {
		/** @var User $user */
		$user = User::findByEmail($email);
		if ($user->status == User::STATUS_NOT_CONFIRMED) {
			$user->sendConfirmationEmail();
			return $this->redirect(['user/needconfirm']);
		}
		return $this->goHome();
	}
}