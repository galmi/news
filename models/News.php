<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 12.09.15
 * Time: 16:30
 */

namespace app\models;


use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class News
 * @package app\models
 *
 * @property integer $id
 * @property string $title
 * @property string $photo
 * @property string $news
 * @property integer $creation_date
 * @property integer $user_id
 */
class News extends ActiveRecord {
	const SCENARIO_CREATE = 'create';

	public function behaviors() {
		return [
			[
				'class'      => AttributeBehavior::className(),
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => 'user_id'
				],
				'value'      => function ( $event ) {
					return \Yii::$app->user->identity->getId();
				}
			]
		];
	}

	public function scenarios() {
		$scenarios = parent::scenarios();
		$scenarios[ self::SCENARIO_CREATE ] = [ 'title', 'photo', 'news', 'user_id' ];
		return $scenarios;
	}

	public function attributeLabels() {
		return [
			'title' => 'News title',
			'photo' => 'News photo',
			'news'  => 'News text'
		];
	}

	public function rules() {
		return [
			[ [ 'title', 'news' ], 'required', 'on' => self::SCENARIO_CREATE ],
		];
	}

	public function getUser() {
		return $this->hasOne(User::className(), ['id' => 'user_id'])->one();
	}
}