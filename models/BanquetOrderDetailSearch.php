<?php

namespace app\models;

use Yii;
use DateTime;
use DateInterval;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BanquetOrderDetail;

/**
 * BanquertOrderDetailSearch represents the model behind the search form of `app\models\BanquetOrderDetail`.
 */
class BanquetOrderDetailSearch extends BanquetOrderDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'orderId', 'locationId', 'paxCount', 'serveTypeId'], 'integer'],
            [['orderDateTime', 'bod_time', 'note','orderStatus','userId'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }



    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = BanquetOrderDetail::find()->joinWith('order as summaryOrder');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'orderDateTime' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);
         

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'orderId' => $this->orderId,
            
            'bod_time' => $this->bod_time,
            'locationId' => $this->locationId,
            'paxCount' => $this->paxCount,
            'serveTypeId' => $this->serveTypeId,
            'summaryOrder.orderStatus' => $this->orderStatus
        ]);
        if (!empty($this->orderDateTime))
        {
            
         
            $fiterDate = DateTime::createFromFormat('d-M-Y',$this->orderDateTime);
            $fiterDate->setTime(1,0);
            $startDateString = $fiterDate->format('Y-m-d G:i');
            
            $fiterDate->setTime(23,59);
            
            $endDateString = $fiterDate->format('Y-m-d G:i');
            
            $query->andFilterWhere(['>=','orderDateTime',$startDateString]);
            $query->andFilterWhere(['<=','orderDateTime',$endDateString]);
            
        }
      

      

        $query->andFilterWhere(['like', 'note', $this->note]);
        
        return $dataProvider;
    }

    public function searchUnverifiedOrder($fromDate,$toDate)
    {
        return BanquetOrderDetail::find()
            ->andFilterWhere([
                'verified' => 0,
                'orderDateTime' => [$fromDate,$toDate],
                'orderStatus' => [BanquetOrder::ORDER_STATUS_APPROVED,BanquetOrder::ORDER_STATUS_IN_PROGRESS]

            ]);
    }

    public function searchWithFood($params)
    {
        $query = BanquetOrderDetail::find();//->joinWith('banquetOrderFoods'); 
        //$query->joinWith('food');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (empty($this->orderDateTime)){
            $defaultDate = new DateTime('tomorrow');
            $this->orderDateTime = $defaultDate->format('d-M-Y');
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'orderId' => $this->orderId,
            
            'bod_time' => $this->bod_time,
            'locationId' => $this->locationId,
            //'paxCount' => $this->paxCount,
            'serveTypeId' => $this->serveTypeId,
            'orderStatus' => BanquetOrder::ORDER_STATUS_APPROVED
        ]);
        if (!empty($this->orderDateTime))
        {
            
            
            //Yii::debug($this->orderDateTime);
            $fiterDate = DateTime::createFromFormat('d-M-Y',$this->orderDateTime);
            $fiterDate->setTime(1,0);
            $startDateString = $fiterDate->format('Y-m-d G:i');
            
            $fiterDate->setTime(23,59);
            
            $endDateString = $fiterDate->format('Y-m-d G:i');
            
            $query->andFilterWhere(['>=','orderDateTime',$startDateString]);
            $query->andFilterWhere(['<=','orderDateTime',$endDateString]);
            
        }
      

      

        $query->andFilterWhere(['like', 'note', $this->note]);
        $query->orderBy([
            'orderDateTime' => SORT_ASC
        ]);
        $query->select(['*']);
        //Yii::debug($query->createCommand()->sql);
        
        return $dataProvider;
    }
}
