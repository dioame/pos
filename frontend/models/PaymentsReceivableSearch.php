<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PaymentsReceivable;

/**
 * PaymentsReceivableSearch represents the model behind the search form of `app\models\PaymentsReceivable`.
 */
class PaymentsReceivableSearch extends PaymentsReceivable
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sales_id', 'orNo'], 'integer'],
            [['transaction_date', 'jev'], 'safe'],
            [['amount_paid'], 'number'],
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
        $query = PaymentsReceivable::find();

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
            'transaction_date' => $this->transaction_date,
            'amount_paid' => $this->amount_paid,
            'orNo' => $this->orNo,
        ]);

        $query->andFilterWhere(['like', 'jev', $this->jev]);

        return $dataProvider;
    }
}
