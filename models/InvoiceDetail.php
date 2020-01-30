<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice_detail".
 *
 * @property int $id
 * @property string $invoiceNumber
 * @property int $invoiceId
 * @property int $itemId
 * @property string $itemDescription
 * @property string $itemDescription2
 * @property string $unitPrice
 * @property int $quantity
 * @property string $totalAmount
 * @property string $note
 * @property int $itemSort
 * @property int $orderDetailId
 * @property string $itemDate
 *
 * @property Invoice $invoiceNumber0
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
            [['invoiceId', 'itemId', 'quantity', 'itemSort', 'orderDetailId','orderId'], 'integer'],
            [['unitPrice', 'totalAmount'], 'number'],
            [['note'], 'string'],
            [['itemDate'], 'safe'],
            [['invoiceNumber'], 'string', 'max' => 20],
            [['itemDescription', 'itemDescription2'], 'string', 'max' => 120],
            [['invoiceNumber'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoiceNumber' => 'invoiceNo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoiceNumber' => 'Invoice Number',
            'invoiceId' => 'Invoice ID',
            'itemId' => 'Item ID',
            'itemDescription' => 'Item Description',
            'itemDescription2' => 'Item Description2',
            'unitPrice' => 'Unit Price',
            'quantity' => 'Quantity',
            'totalAmount' => 'Total Amount',
            'note' => 'Note',
            'itemSort' => 'Item Sort',
            'orderDetailId' => 'Order Detail ID',
            'itemDate' => 'Item Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['invoiceNo' => 'invoiceNumber']);
    }

    public function getOrder()
    {
        return $this->hasOne(BanquetOrder::className(),['orderId'=>'orderId']);
    }


    /**
     * {@inheritdoc}
     * @return InvoiceDetailQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InvoiceDetailQuery(get_called_class());
    }
}
