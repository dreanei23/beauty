<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use \yii\db\Expression;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "salon".
 *
 * @property int $id
 * @property int $city_id
 * @property int $user_id
 * @property string $name
 * @property string $slug
 * @property string $adress
 * @property double $latitude
 * @property double $longitude
 * @property string $logo
 * @property string $gallery
 * @property int $created_at
 * @property int $updated_at
 */
class Salon extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'immutable' => true,
                'ensureUnique'=>true,
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'salon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'adress', 'city_id'], 'required'],
            [['name'], 'string', 'max' => 256],
            [['logo'], 'file', 'extensions' => ['png', 'jpg'], 'maxSize' => 5120*1024],
            [['gallery'], 'file', 'extensions' => ['png', 'jpg'], 'maxSize' => 5120*1024, 'maxFiles' => 10],
            [['adress'], 'string', 'max' => 255],
            [['latitude', 'longitude'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'adress' => 'Адрес',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'logo' => 'Логотип',
            'gallery' => 'Доп. фото',
            'city_id' => 'Город'
        ];
    }

}
