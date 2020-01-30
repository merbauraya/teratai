<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Food]].
 *
 * @see Food
 */
class FoodQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Food[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Food|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
