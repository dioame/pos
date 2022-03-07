<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JevEntries;

/**
 * JevEntriesSearch represents the model behind the search form of `app\models\JevEntries`.
 */
class JevEntriesSearch extends JevEntries
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'accounting_code'], 'integer'],
            [['jev', 'type'], 'safe'],
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
        $query = JevEntries::find();

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
            'accounting_code' => $this->accounting_code,
            'amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'jev', $this->jev])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
