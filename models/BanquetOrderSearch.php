<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BanquetOrder;

/**
 * BanquetOrderSearch represents the model behind the search form of `app\models\BanquetOrder`.
 */
class BanquetOrderSearch extends BanquetOrder
{
    /**
     * {@inheritdoc}
     */

    public $paxCount;
    public $locationId;
    public function rules()
    {
        return [
            [['orderId', 'locationId', 'userId', 'serviceTypeId', 'orderStatus','paxCount'], 'integer'],
            [['createdDate', 'orderDate', 'orderNote','dateFilterOperate','locationId','paxCount'], 'safe'],
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

    public function searchWithOrderDetail($params)
    {
        $query = BanquetOrder::find()->select('*');
        $this->load($params);

        $query->joinWith(['banquetOrderDetails']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        
        $query->andFilterWhere(['=', 'banquetOrderDetails.orderId', $this->orderId]);
        $query->andFilterWhere(['=', 'banquetOrderDetails.locationId', $this->locationId]);


        $sql = $query->createCommand()->getRawSql();
       // Yii::debug("MZMZ");
        //Yii::debug($sql);


        return $dataProvider;
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
        $query = BanquetOrder::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'orderId' => $this->orderId,
            'locationId' => $this->locationId,
            'userId' => $this->userId,
            'createdDate' => $this->createdDate,
            'orderDate' => $this->orderDate,
            'serviceTypeId' => $this->serviceTypeId,
            'orderStatus' => $this->orderStatus,
        
        ]);

        $query->andFilterWhere(['like', 'orderNote', $this->orderNote]);

        return $dataProvider;
    }
}
