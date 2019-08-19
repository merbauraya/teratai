<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_status". 
 *
 * @property int $orderId
 * @property int $orderStatus
 * @property string $status_date
 * @property int $status_by
 */
class OrderStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orderId', 'orderStatus'], 'required'],
            [['orderId', 'orderStatus', 'status_by'], 'integer'],
            [['orderId', 'orderStatus'], 'unique', 'targetAttribute' => ['orderId', 'orderStatus']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'orderId' => 'Order ID',
            'orderStatus' => 'Order Status',
            'status_date' => 'Status Date',
            'status_by' => 'Status By',
        ];
    }

    /**
     * {@inheritdoc}
     * @return OrderStatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderStatusQuery(get_called_class());
    }
}
