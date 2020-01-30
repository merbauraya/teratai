<?php


namespace app\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "banquet_order_detail".
 *
 * @property int $id
 * @property int $orderId Order Summary ID
 * @property string $orderDateTime
 * @property string $bod_time
 * @property int $locationId
 * @property int $paxCount
 * @property int $serveTypeId
 * @property string $note
 * @property int orderStatus
 *
 * @property BanquetOrder $order
 * @property Location $location
 * @property ServingType $serveType
 * @property BanquetOrderFood[] $banquetOrderFoods
 * @property Food[] $foods
 */
class BanquetOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    
    public $userId;
    public $approvedBy;
    public $orderPurpose;
    public $orderDate;
    public $createdDate;
    
    public static function tableName()
    {
        return 'banquet_order_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orderId', 'locationId', 'paxCount', 'serveTypeId','orderStatus','verified','userId'], 'integer'],
            [['pricePerPax'], 'number'],
            [['orderDateTime', 'bod_time','autoStatusDate'], 'safe'],
            [['note'], 'string', 'max' => 45],
            [['serveTypeId','locationId','paxCount'], 'required'],
            [['orderId'], 'exist', 'skipOnError' => true, 'targetClass' => BanquetOrder::className(), 'targetAttribute' => ['orderId' => 'orderId']],
            [['locationId'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['locationId' => 'locationId']],
            [['serveTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => ServingType::className(), 'targetAttribute' => ['serveTypeId' => 'typeId']],
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
            'orderDateTime' => 'Order Date Time',
            'bod_time' => 'Bod Time',
            'locationId' => 'Location ID',
            'paxCount' => 'Pax Count',
            'serveTypeId' => 'Serve Type ID',
            'note' => 'Note',
            'orderStatus' => 'Order Status'
        ];
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
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['locationId' => 'locationId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServeType()
    {
        return $this->hasOne(ServingType::className(), ['typeId' => 'serveTypeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanquetOrderFoods()
    {
        return $this->hasMany(BanquetOrderFood::className(), ['orderDetailId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFoods()
    {
        return $this->hasMany(Food::className(), ['foodId' => 'foodId'])->viaTable('banquet_order_food', ['orderDetailId' => 'id']);
    }

    public function getFormattedDateTimeString()
    {
        $orderDate = DateTime::createFromFormat('Y-m-d H:i:s',$this->orderDateTime);
        if ($orderDate)
        {
            $strDateTime = $orderDate->format('D d-M-Y ').$orderDate->format('g:i A') ;
        }

        return $strDateTime;
    }
    public function getFormattedTimeString()
    {
        $orderDate = DateTime::createFromFormat('Y-m-d H:i:s',$this->orderDateTime);
        if ($orderDate)
        {
            $strDateTime = $orderDate->format('g:i A') ;
        }

        return $strDateTime;
    }

    public function getFormattedDateString()
    {
        $orderDate = DateTime::createFromFormat('Y-m-d H:i:s',$this->orderDateTime);
        if ($orderDate)
        {
            $strDateTime = $orderDate->format('D d-M-Y') ;
        }

        return $strDateTime;
    }


    public function getFormattedFoodName()
    {
        $str='<ul>';

        foreach ($this->banquetOrderFoods as $items)
        {
            $food = Food::findOne($items['foodId']);
            $str = $str.'<li>'.$food->foodName;
            if ($items['paxCount'] != $this->paxCount)
            {
                $str = $str.' /'.$items['paxCount'] . ' pax ';
            }
            if ($items['serveTypeId'] != $this->serveTypeId)
            {
                $serveType = ServingType::findOne($items['serveTypeId']);
                $str = $str . ' /'.$serveType->typeName . '</li>';
            }
        }
/*
        foreach ($this->foods as $items)
        {
            $str = $str.'<li>'.$items['foodName'].'</li>';
        } */
        $str = $str.'</ul>';
        return $str;
    } 

    public function isEditable()
    {

       //ensure user can only edit his own order
        if ($this->order->userId != Yii::$app->user->id)
        {
            return false;
        }

        if ($this->orderStatus == BanquetOrder::ORDER_STATUS_DRAFT ||
            $this->orderStatus == BanquetOrder::ORDER_WAITING_APPROVAL)
        {
            return true;
        }

        if ($this->orderStatus == BanquetOrder::ORDER_STATUS_COMPLETED || 
            $this->orderStatus == BanquetOrder::ORDER_STATUS_CANCELLED ||
            $this->orderStatus == BanquetOrder::ORDER_STATUS_DENIED ||
            $this->orderStatus == BanquetOrder::ORDER_STATUS_IN_PROGRESS )
        {
            return false;
        }

        if ($this->orderStatus == BanquetOrder::ORDER_STATUS_APPROVED)
        {
            if (Yii::$app->config->get('orderRequireApproval') )
            {
                if (Yii::$app->config->get('allowApprovedEdit'))
                {
                    return true;
                }else
                {
                    return false;
                }
            } else //no approval required
            {
                return true;
            }
             
        }

       return false;
    }
}
