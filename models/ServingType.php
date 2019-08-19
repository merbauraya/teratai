<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "serving_type".
 *
 * @property int $typeId
 * @property string $typeName
 *
 * @property BanquetOrderDetail[] $banquetOrderDetails
 */
class ServingType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'serving_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['typeName'], 'required'],
            [['typeName'], 'string', 'max' => 75],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'typeId' => 'Type ID',
            'typeName' => 'Type Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanquetOrderDetails()
    {
        return $this->hasMany(BanquetOrderDetail::className(), ['serveTypeId' => 'typeId']);
    }

    /**
     * {@inheritdoc}
     * @return ServingTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ServingTypeQuery(get_called_class());
    }
}
