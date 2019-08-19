<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "food_category".
 *
 * @property int $categoryId
 * @property string $categoryName
 *
 * @property Food[] $foods
 */
class FoodCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'food_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoryName'], 'required'],
            [['categoryName'], 'string', 'max' => 75],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'categoryId' => 'Category ID',
            'categoryName' => 'Category Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFoods()
    {
        return $this->hasMany(Food::className(), ['foodCategoryId' => 'categoryId']);
    }
}
