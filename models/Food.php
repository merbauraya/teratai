<?php

namespace app\models;


use Yii;
use app\models\FoodCategory;
/**
 * This is the model class for table "food".
 *
 * @property int $foodId
 * @property int $foodCategoryId
 * @property string $foodName
 * @property string $price
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
            [['foodCategoryId', 'foodName'], 'required'],
            [['foodCategoryId'], 'integer'],
            [['price'], 'number'],
            [['foodName'], 'string', 'max' => 125],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'foodId' => 'Food ID',
            'foodCategoryId' => 'Food Category ID',
            'foodName' => 'Food Name',
            'price' => 'Price',
        ];
    }

    /**
     * {@inheritdoc}
     * @return FoodQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FoodQuery(get_called_class());
    }

    public function getFoodCategory()
    {
        return $this->hasOne(FoodCategory::className(), ['categoryId' => 'foodCategoryId']);

    }
}
