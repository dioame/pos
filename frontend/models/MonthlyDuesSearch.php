<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MonthlyDues;

/**
 * MonthlyDuesSearch represents the model behind the search form of `app\models\MonthlyDues`.
 */
class MonthlyDuesSearch extends MonthlyDues
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'mID'], 'integer'],
            [['month', 'year', 'jev'], 'safe'],
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
        $query = MonthlyDues::find();

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
            'mID' => $this->mID,
            'year' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'month', $this->month])
            ->andFilterWhere(['like', 'jev', $this->jev]);

        return $dataProvider;
    }
}
