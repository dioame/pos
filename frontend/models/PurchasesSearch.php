<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Purchases;

/**
 * PurchasesDetails represents the model behind the search form of `app\models\Purchases`.
 */
class PurchasesSearch extends Purchases
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'payee', 'ap_invoice'], 'integer'],
            [['date_posted', 'dv_number', 'jev'], 'safe'],
            [['total', 'amount_paid', 'accounts_payable', 'discount'], 'number'],
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
        $query = Purchases::find();

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
            'payee' => $this->payee,
            'date_posted' => $this->date_posted,
            'total' => $this->total,
            'amount_paid' => $this->amount_paid,
            'accounts_payable' => $this->accounts_payable,
            'ap_invoice' => $this->ap_invoice,
            'discount' => $this->discount,
        ]);

        $query->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'jev', $this->jev]);

        return $dataProvider;
    }
}
