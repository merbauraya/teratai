<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "food".
 *
 * @property int $foodId
 * @property string $foodName
 * @property int $foodCategoryId
 *
 * @property FoodCategory $foodCategory
 */
class Food extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'food';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['foodName', 'foodCategoryId'], 'required'],
            [['foodCategoryId'], 'integer'],
            [['foodName'], 'string', 'max' => 75],
            [['foodCategoryId'], 'exist', 'skipOnError' => true, 'targetClass' => FoodCategory::className(), 'targetAttribute' => ['foodCategoryId' => 'categoryId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'foodId' => 'Food ID',
            'foodName' => 'Food Name',
            'foodCategoryId' => 'Food Category ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFoodCategory()
    {
        return $this->hasOne(FoodCategory::className(), ['categoryId' => 'foodCategoryId']);
    }
}
