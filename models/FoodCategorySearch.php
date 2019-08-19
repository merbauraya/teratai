<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FoodCategory;

/**
 * FoodCategorySearch represents the model behind the search form of `app\models\FoodCategory`.
 */
class FoodCategorySearch extends FoodCategory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoryId'], 'integer'],
            [['categoryName'], 'safe'],
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
        $query = FoodCategory::find();

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
            'categoryId' => $this->categoryId,
        ]);

        $query->andFilterWhere(['like', 'categoryName', $this->categoryName]);

        return $dataProvider;
    }
}
