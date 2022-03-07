<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PpeDepreciation;

/**
 * PpeDepreciationSearch represents the model behind the search form of `app\models\PpeDepreciation`.
 */
class PpeDepreciationSearch extends PpeDepreciation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ppeID'], 'integer'],
            [['date_from', 'date_to', 'date_posted', 'jev1', 'jev2'], 'safe'],
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
        $query = PpeDepreciation::find();

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
            'ppeID' => $this->ppeID,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'amount' => $this->amount,
            'date_posted' => $this->date_posted,
        ]);

        $query->andFilterWhere(['like', 'jev1', $this->jev1])
            ->andFilterWhere(['like', 'jev2', $this->jev2]);

        return $dataProvider;
    }
}
