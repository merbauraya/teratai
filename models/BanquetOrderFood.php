<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "banquet_order_food".
 *
 * @property int $bofId
 * @property int $orderId
 * @property int $foodId
 * @property string $note
 * @property int $orderDetailId
 * @property int $paxCount
 * @property int $serveTypeId
 *
 * @property BanquetOrderDetail $orderDetail
 * @property BanquetOrder $order
 * @property Food $food
 */
class BanquetOrderFood extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banquet_order_food';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orderId', 'foodId'], 'required'],
            [['orderId', 'foodId', 'orderDetailId', 'paxCount', 'serveTypeId'], 'integer'],
            [['note'], 'string'],
            [['foodId', 'orderDetailId'], 'unique', 'targetAttribute' => ['foodId', 'orderDetailId']],
            [['orderDetailId'], 'exist', 'skipOnError' => true, 'targetClass' => BanquetOrderDetail::className(), 'targetAttribute' => ['orderDetailId' => 'id']],
            [['orderId'], 'exist', 'skipOnError' => true, 'targetClass' => BanquetOrder::className(), 'targetAttribute' => ['orderId' => 'orderId']],
            [['foodId'], 'exist', 'skipOnError' => true, 'targetClass' => Food::className(), 'targetAttribute' => ['foodId' => 'foodId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bofId' => 'Bof ID',
            'orderId' => 'Order ID',
            'foodId' => 'Food ID',
            'note' => 'Note',
            'orderDetailId' => 'Order Detail ID',
            'paxCount' => 'Pax Count',
            'serveTypeId' => 'Serve Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetail()
    {
        return $this->hasOne(BanquetOrderDetail::className(), ['id' => 'orderDetailId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(BanquetOrder::className(), ['orderId' => 'orderId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFood()
    {
        return $this->hasOne(Food::className(), ['foodId' => 'foodId']);
    }
    
    public function getServeType()
    {
        return $this->hasOne(ServingType::className(),['typeId' => 'serveTypeId']);
    }

    /**
     * {@inheritdoc}
     * @return BanquetOrderFoodQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BanquetOrderFoodQuery(get_called_class());
    }
}
