<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ConfirmForm is the model behind the login form.
 */
class ConfirmForm extends Model {

	/** @var User $user */
	public $user;
	public $authKey;
	public $password;

	/**
	 * @return array the validation rules.
	 */
	public function rules() {
		return [
			[ ['authKey', 'password'], 'required' ],
			[ 'authKey', 'validateAuthKey' ],
		];
	}

	public function attributeLabels() {
		return [
			'authKey' => 'Your auth key from email',
			'password' => 'New password'
		];
	}

	public function confirmAuthKey() {
		if ( $this->validate() ) {
			$this->user->scenario = User::SCENARIO_CONFIRM;
			$this->user->status = User::STATUS_CONFIRMED;
			$this->user->setPassword($this->password);
			$this->user->save( true, [ 'status', 'password' ] );

			return true;
		}
		return false;
	}

	public function validateAuthKey() {
		if ( ! $this->user->validateAuthKey( $this->authKey ) ) {
			$this->addError( 'authKey', 'Your auth key is wrong' );
		}
	}
}
