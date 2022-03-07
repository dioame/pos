<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PurchaseDetails;

/**
 * PurchaseDetailsSearch represents the model behind the search form of `app\models\PurchaseDetails`.
 */
class PurchaseDetailsSearch extends PurchaseDetails
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'purchase_id', 'product_id'], 'integer'],
            [['quantity', 'cost'], 'number'],
            [['unit'], 'safe'],
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
        $query = PurchaseDetails::find();

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
            'purchase_id' => $this->purchase_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'cost' => $this->cost,
        ]);

        $query->andFilterWhere(['like', 'unit', $this->unit]);

        return $dataProvider;
    }
}
