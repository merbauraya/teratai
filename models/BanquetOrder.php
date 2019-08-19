<?php

namespace app\models;

use Yii;


use Da\User\Model\User;

/**
 * This is the model class for table "banquet_order".
 *
 * @property int $orderId
 * @property int $userId
 * @property string $createdDate
 * @property string $orderDate
 * @property string $orderPurpose
 * @property int $serviceTypeId
 * @property string $orderNote
 * @property int $orderStatus 0-new,1-completed,2-cancelled
 * @property int $approvedBy
 * @property string $approvedDate
 *
 * @property BanquetOrderDetail[] $banquetOrderDetails
 * @property BanquetOrderFood[] $banquetOrderFoods
 */
class BanquetOrder extends \yii\db\ActiveRecord
{
    
    public $paxCount;
    public $locationId;
    
    const ORDER_STATUS_DRAFT = 0;
    const ORDER_WAITING_APPROVAL = 1;
    const ORDER_STATUS_APPROVED = 2;
    const ORDER_STATUS_IN_PROGRESS = 3;
    const ORDER_STATUS_COMPLETED = 4;
    const ORDER_STATUS_CANCELLED = 5;
    const ORDER_STATUS_DENIED = 6;
    const ORDER_STATUS_VERIFIED = 7;
    const ORDER_STATUS_INVOICED = 8;
    const ORDER_STATUS_PAID = 9;
    const ORDER_STATUS_OTHER = 100;

    const NOTIFICATION_NOT_SENT = 0;
    const NOTIFICATION_SENT = 1;

    const INVOICE_NOT_SENT = 0;
    const INVOICE_SENT = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banquet_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'serviceTypeId', 'orderStatus', 'approvedBy','notificationSent',
            'invoiceSent','approvalRequestSent'], 'integer'],
            [['userId', 'createdDate', 'orderDate', 'orderStatus'], 'required'],
            [['createdDate', 'orderDate', 'approvedDate'], 'safe'],
            [['orderPurpose'], 'string', 'max' => 100],
        ];
    }

    public static function getOrderStatusText($status)
    {
        $statuses = self::orderStatusArray();
        return $statuses[$status];
    }
    
    public static function orderStatusArray()
    {
        return [
            self::ORDER_STATUS_DRAFT => 'Draft',
            self::ORDER_WAITING_APPROVAL => 'Waiting Approval',
            self::ORDER_STATUS_APPROVED => 'Order Approved',
            self::ORDER_STATUS_IN_PROGRESS => 'In Progress',
            self::ORDER_STATUS_COMPLETED => 'Order Completed',
            self::ORDER_STATUS_DENIED => 'Order Denied',
            self::ORDER_STATUS_INVOICED => 'Invoiced',
            self::ORDER_STATUS_PAID => 'Paid',
            self::ORDER_STATUS_VERIFIED => 'Verified',
            self::ORDER_STATUS_OTHER => 'Status Other'
            
        ];
    }
    
    
    
    /**
     * {@inheritdoc}
     */
    
    
    public function attributeLabels()
    {
        return [
            'orderId' => 'Order ID',
            'userId' => 'User ID',
            'createdDate' => 'Created Date',
            'orderDate' => 'Order Date',
            'orderPurpose' => 'Order Purpose',
            'serviceTypeId' => 'Service Type ID',
            'orderNote' => 'Order Note',
            'orderStatus' => 'Order Status',
            'approvedBy' => 'Approved By',
            'approvedDate' => 'Approved Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanquetOrderDetails()
    {
        return $this->hasMany(BanquetOrderDetail::className(), ['orderId' => 'orderId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanquetOrderFoods()
    {
        return $this->hasMany(BanquetOrderFood::className(), ['orderId' => 'orderId']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(),['id' =>'userId']);
    }

    public function getApprovedBy()
    {
        return $this->hasOne(User::className(),['id' =>'approvedBy']);
    }

   
    
}
