<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AccountsPayableInvoice;

/**
 * AccountsPayableInvoiceSearch represents the model behind the search form of `app\models\AccountsPayableInvoice`.
 */
class AccountsPayableInvoiceSearch extends AccountsPayableInvoice
{
    /**
     * {@inheritdoc}
     */
    public $date_from,$date_to;
    public function rules()
    {
        return [
            [['id', 'supplier', 'po_number', 'type_of_expense'], 'integer'],
            [['invoice_number', 'invoice_date', 'due_date', 'jev','date_from','date_to'], 'safe'],
            [['amount'], 'number'],
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
        $query = AccountsPayableInvoice::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder'=>['id'=>SORT_DESC]]
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
            'supplier' => $this->supplier,
            'due_date' => $this->due_date,
            'po_number' => $this->po_number,
            'type_of_expense' => $this->type_of_expense,
            'amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'invoice_number', $this->invoice_number])
            ->andFilterWhere(['>=', 'invoice_date', $this->date_from])
            ->andFilterWhere(['<=', 'invoice_date', $this->date_to])
            ->andFilterWhere(['like', 'jev', $this->jev]);

        return $dataProvider;
    }
}
