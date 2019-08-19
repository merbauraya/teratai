<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ServingType]].
 *
 * @see ServingType
 */
class ServingTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ServingType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ServingType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
