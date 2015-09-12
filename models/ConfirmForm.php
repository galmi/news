<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ConfirmForm is the model behind the login form.
 */
class ConfirmForm extends Model {
	public $authKey;

	/**
	 * @return array the validation rules.
	 */
	public function rules() {
		return [
			[ 'authKey', 'required' ],
			[ 'authKey', 'validateAuthKey' ],
		];
	}

	public function attributeLabels() {
		return [
			'authKey' => 'Your auth key from email',
		];
	}

	public function confirmAuthKey() {
		if ( $this->validate() ) {
			/** @var User $user */
			$user = Yii::$app->getUser()->getIdentity();
			$user->scenario = User::SCENARIO_CONFIRM;
			$user->status = User::STATUS_CONFIRMED;
			$user->save( true, [ 'status' ] );

			return true;
		}
		return false;
	}

	public function validateAuthKey() {
		/** @var User $user */
		$user = Yii::$app->getUser()->getIdentity();
		if ( ! $user->validateAuthKey( $this->authKey ) ) {
			$this->addError( 'authKey', 'Your auth key is wrong' );
		}
	}
}
