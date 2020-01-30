<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[InvoiceDetail]].
 *
 * @see InvoiceDetail
 */
class InvoiceDetailQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return InvoiceDetail[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return InvoiceDetail|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
