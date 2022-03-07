<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Expenses;

/**
 * ExpensesSearch represents the model behind the search form of `app\models\Expenses`.
 */
class ExpensesSearch extends Expenses
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'supplier'], 'integer'],
            [['date', 'unit', 'jev', 'dv'], 'safe'],
            [['quantity', 'unit_cost', 'amount', 'amount_paid', 'balance'], 'number'],
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
        $query = Expenses::find();

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
            'date' => $this->date,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'unit_cost' => $this->unit_cost,
            'amount' => $this->amount,
            'amount_paid' => $this->amount_paid,
            'balance' => $this->balance,
            'supplier' => $this->supplier,
        ]);

        $query->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'jev', $this->jev])
            ->andFilterWhere(['like', 'dv', $this->dv]);

        return $dataProvider;
    }
}
