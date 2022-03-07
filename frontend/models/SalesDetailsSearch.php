<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SalesDetails;

/**
 * SalesDetailsSearch represents the model behind the search form of `app\models\SalesDetails`.
 */
class SalesDetailsSearch extends SalesDetails
{
    /**
     * @inheritdoc
     */
    public $paid,$lastname,$firstname;
    public function rules()
    {
        return [
            [['id', 'sales_id', 'product_id','paid'], 'integer'],
            [['lastname','firstname'], 'safe'],
            [['quantity', 'product_price', 'buying_price', 'sub_total'], 'number'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = SalesDetails::find();
        $query->joinWith('sales.customer');

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
            'id' => $this->id,
            'sales_id' => $this->sales_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'product_price' => $this->product_price,
            'buying_price' => $this->buying_price,
            'sub_total' => $this->sub_total,
            'sales.paid' => $this->paid,
        ]);

        $query->andFilterWhere(['AND',
            ['like','customers.lastname',$this->lastname],
            ['like','customers.firstname',$this->firstname]
            
        ]);

        return $dataProvider;
    }
}
