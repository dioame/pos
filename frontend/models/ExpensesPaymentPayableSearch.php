<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExpensesPaymentPayable;

/**
 * ExpensesPaymentPayableSearch represents the model behind the search form of `app\models\ExpensesPaymentPayable`.
 */
class ExpensesPaymentPayableSearch extends ExpensesPaymentPayable
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'expense_id'], 'integer'],
            [['amount_paid'], 'number'],
            [['jev', 'transaction_date'], 'safe'],
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
        $query = ExpensesPaymentPayable::find();

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
            'expense_id' => $this->expense_id,
            'amount_paid' => $this->amount_paid,
            'transaction_date' => $this->transaction_date,
        ]);

        $query->andFilterWhere(['like', 'jev', $this->jev]);

        return $dataProvider;
    }
}
