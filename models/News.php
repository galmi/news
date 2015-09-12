<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 12.09.15
 * Time: 16:30
 */

namespace app\models;


use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

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

	/** @var UploadedFile $file */
	public $file;

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
			],
			[
				'class'      => AttributeBehavior::className(),
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => 'photo'
				],
				'value'      => function ( $event ) {
					if ($this->file instanceof UploadedFile) {
						$path = '/uploads/' . $this->file->baseName . '.' . $this->file->extension;
						$this->file->saveAs( $path );
						return $path;
					}
					return null;
				}
			]
		];
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
			[ [ 'title', 'news' ], 'required' ],
			[
				'file',
				'file',
				'mimeTypes' => [
					'image/jpeg',
					'image/gif',
					'image/png'
				]
			]
		];
	}

	public function getUser() {
		return $this->hasOne(User::className(), ['id' => 'user_id'])->one();
	}
}