<?php

namespace app\models;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\Url;
use yii\base\InvalidParamException;

/**
 * Class User
 * @package app\models
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $authKey
 * @property string $authToken
 * @property string $status
 * @property News[] $news
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface {
	const SCENARIO_LOGIN = 'login';
	const SCENARIO_REGISTER = 'register';
	const SCENARIO_CONFIRM = 'confirm';

	const STATUS_NOT_CONFIRMED = 0;
	const STATUS_CONFIRMED = 1;

	public $password2;

	public function behaviors() {
		return [
			[
				'class'      => AttributeBehavior::className(),
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => 'authKey'
				],
				'value'      => function ( $event ) {
					return \Yii::$app->security->generateRandomString();
				}
			]
		];
	}

	public function scenarios() {
		$scenarios = parent::scenarios();
		$scenarios[ self::SCENARIO_LOGIN ] = [ 'email', 'password' ];
		$scenarios[ self::SCENARIO_REGISTER ] = [ 'username', 'email' ];
		$scenarios[ self::SCENARIO_CONFIRM ] = [ 'authKey', 'status', 'password' ];

		return $scenarios;
	}

	public function attributeLabels() {
		return [
			'name'      => 'Your name',
			'email'     => 'Your email address',
			'password'  => 'Your password',
			'password2' => 'Repeat your password',
			'authKey'   => 'Your auth key'
		];
	}

	public function rules() {
		return [
			[ [ 'username', 'email' ], 'required', 'on' => self::SCENARIO_REGISTER ],
			[ 'email', 'validateEmail', 'on' => self::SCENARIO_REGISTER ],
			[ 'email', 'email' ],
//			[ 'password', 'compare', 'compareAttribute' => 'password2', 'on' => self::SCENARIO_REGISTER ],
			[ [ 'email', 'password' ], 'required', 'on' => self::SCENARIO_LOGIN ],
			[ [ 'authToken', 'password' ], 'required', 'on' => self::SCENARIO_CONFIRM ],
		];
	}

	public function validateEmail() {
		if ( User::findByEmail( $this->email ) ) {
			$this->addError( 'email', 'User with this email already registered' );
		}
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey( $authKey ) {
		return $this->authKey === $authKey;
	}

	public function beforeSave( $insert ) {
		if ( parent::beforeSave( $insert ) ) {

			if ( $insert ) {
				$this->sendConfirmationEmail();
			}

			return true;
		}

		return false;
	}

	/**
	 * Validates password
	 *
	 * @param  string $password password to validate
	 *
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword( $password ) {
		try {
			return \Yii::$app->security->validatePassword( $password, $this->password );
		} catch ( InvalidParamException $e ) {
			return false;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey() {
		return $this->authKey;
	}

	public function getNews() {
		return $this->hasMany( News::className(), [ 'user_id' => 'id' ] )->orderBy( 'creation_date desc' )->all();
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentity( $id ) {
		return self::findOne( $id );
	}

	/**
	 * Finds user by username
	 *
	 * @param  string $username
	 *
	 * @return static|null
	 */
	public static function findByUsername( $username ) {
		return self::findOne( array(
			'username' => $username
		) );
	}

	public static function findByEmail( $email ) {
		return self::findOne( array(
			'email' => $email
		) );
	}

	/**
	 * Finds an identity by the given token.
	 *
	 * @param mixed $token the token to be looked for
	 * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
	 * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
	 *
	 * @return IdentityInterface the identity object that matches the given token.
	 * Null should be returned if such an identity cannot be found
	 * or the identity is not in an active state (disabled, deleted, etc.)
	 */
	public static function findIdentityByAccessToken( $token, $type = null ) {
		return self::findOne( array(
			'accessToken' => $token
		) );
	}

	public static function findIdentityByAuthKey( $authKey ) {
		return self::findOne( array(
			'authKey' => $authKey
		) );
	}


	public function isConfirmed() {
		return $this->status == self::STATUS_CONFIRMED;
	}

	public function setPassword( $password ) {
		$this->password = \Yii::$app->security->generatePasswordHash( $password );
	}

	public function sendConfirmationEmail() {
		$link = Url::to( [ 'user/confirm', 'id' => $this->authKey ], true );
		\Yii::$app->mailer->compose()
		                  ->setTo( $this->email )
		                  ->setFrom( [ \Yii::$app->params['adminEmail'] => 'Admin' ] )
		                  ->setSubject( 'Confirmation email' )
		                  ->setTextBody( "For confirm email click this link: \n" . $link )
		                  ->send();
	}
}
