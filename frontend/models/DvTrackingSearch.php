<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DvTracking;

/**
 * DvTrackingSearch represents the model behind the search form of `app\models\DvTracking`.
 */
class DvTrackingSearch extends DvTracking
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'payee', 'debit', 'credit', 'prepared_by', 'requested_by', 'approved_by', 'received_by'], 'integer'],
            [['dv_number', 'date_posted', 'type'], 'safe'],
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
        $query = DvTracking::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['id'=>SORT_DESC]]
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
            'date_posted' => $this->date_posted,
            'amount' => $this->amount,
            'payee' => $this->payee,
            'debit' => $this->debit,
            'credit' => $this->credit,
            'prepared_by' => $this->prepared_by,
            'requested_by' => $this->requested_by,
            'approved_by' => $this->approved_by,
            'received_by' => $this->received_by,
        ]);

        $query->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
