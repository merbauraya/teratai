<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property int $orderId
 * @property string $invoiceDate
 * @property int $customerId
 * @property string $invoiceNo
 * @property string $note
 *
 * @property InvoiceDetail[] $invoiceDetails
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orderId', 'customerId'], 'integer'],
            [['invoiceDate'], 'safe'],
            [['note'], 'string'],
            [['invoiceNo'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderId' => 'Order ID',
            'invoiceDate' => 'Invoice Date',
            'customerId' => 'Customer ID',
            'invoiceNo' => 'Invoice No',
            'note' => 'Note',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::className(), ['invoiceId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return InvoiceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InvoiceQuery(get_called_class());
    }
}
