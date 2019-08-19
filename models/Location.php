<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property int $locationId
 * @property string $locationName
 *
 * @property BanquetOrderDetail[] $banquetOrderDetails
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['locationName'], 'required'],
            [['locationName'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'locationId' => 'Location ID',
            'locationName' => 'Location Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanquetOrderDetails()
    {
        return $this->hasMany(BanquetOrderDetail::className(), ['locationId' => 'locationId']);
    }
}
