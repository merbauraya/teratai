<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice_detail".
 *
 * @property int $ivdId
 * @property int $invoiceId
 * @property int $itemId
 * @property string $itemDecription
 * @property string $unitPrice
 * @property int $quantity
 * @property string $totalAmount
 * @property string $note
 * @property int $itemSort
 *
 * @property Invoice $invoice
 */
class InvoiceDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoiceId', 'itemId', 'quantity', 'itemSort'], 'integer'],
            [['unitPrice', 'totalAmount'], 'number'],
            [['note'], 'string'],
            [['itemDecription'], 'string', 'max' => 100],
            [['invoiceId'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoiceId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ivdId' => 'Ivd ID',
            'invoiceId' => 'Invoice ID',
            'itemId' => 'Item ID',
            'itemDecription' => 'Item Decription',
            'unitPrice' => 'Unit Price',
            'quantity' => 'Quantity',
            'totalAmount' => 'Total Amount',
            'note' => 'Note',
            'itemSort' => 'Item Sort',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoiceId']);
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
