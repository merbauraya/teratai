<?php

namespace app\models;

use Yii;
use Da\User\Model\User;


/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property int $orderId
 * @property string $invoiceDate
 * @property int $customerId
 * @property string $invoiceNo
 * @property string $note
 * @property string $totalAmount
 * @property string $discount
 * @property string $netAmount
 * @property int $invoiceStatus
 * @property string $dueDate
 * @property string $amountReceived
 *
 * @property InvoiceDetail[] $invoiceDetails
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    const INVOICE_STATUS_OUTSTANDING = 0;
    const INVOICE_STATUS_PARTIAL_PAID = 1;
    const INVOICE_STATUS_PAID = 2;
    const INVOICE_STATUS_CANCELLED = 10;
    
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
            [['orderId', 'customerId', 'invoiceStatus'], 'integer'],
            [['invoiceDate', 'dueDate'], 'safe'],
            [['note'], 'string'],
            [['totalAmount', 'discount', 'netAmount', 'amountReceived'], 'number'],
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
            'totalAmount' => 'Total Amount',
            'discount' => 'Discount',
            'netAmount' => 'Net Amount',
            'invoiceStatus' => 'Invoice Status',
            'dueDate' => 'Due Date',
            'amountReceived' => 'Amount Received',
        ];
    }

    public static function getStatusText($status)
    {
        $statuses = self::invoiceStatusArray();
        return $statuses[$status];
    }

    public static function invoiceStatusArray()
    {
        return [
            self::INVOICE_STATUS_OUTSTANDING => 'Outstanding',
            self::INVOICE_STATUS_PARTIAL_PAID => 'Partial Paid',
            self::INVOICE_STATUS_PAID => 'Paid',
            self::INVOICE_STATUS_CANCELLED => 'Cancelled',
            
            
            
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

    public function getCustomer()
    {
        return $this->hasOne(User::className(),['id' =>'customerId']);
    }

    public static function findByInvoiceNo($invoiceNo)
    {
        return Invoice::find()->where(['invoiceNo' => $invoiceNo])->one(); // give single record based on id

    }

    public static function getOrders($invoiceNo)
    {
        $sql = 'SELECT A.*,B.* FROM banquet_order A,banquet_order_detail B WHERE A.invoiceNumber = :invoiceNo AND B.orderId = A.orderId';
        $command = Yii::$app->db->createCommand($sql);
        


    }




}
