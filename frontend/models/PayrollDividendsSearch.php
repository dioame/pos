<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PayrollDividends;

/**
 * PayrollDividendsSearch represents the model behind the search form of `app\models\PayrollDividends`.
 */
class PayrollDividendsSearch extends PayrollDividends
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'emp_id'], 'integer'],
            [['date_from', 'date_to', 'date_created', 'dv', 'pcv', 'jev'], 'safe'],
            [['number_of_hours', 'hourly_rate'], 'number'],
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
        $query = PayrollDividends::find();

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
            'emp_id' => $this->emp_id,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'number_of_hours' => $this->number_of_hours,
            'hourly_rate' => $this->hourly_rate,
            'date_created' => $this->date_created,
        ]);

        $query->andFilterWhere(['like', 'dv', $this->dv])
            ->andFilterWhere(['like', 'pcv', $this->pcv])
            ->andFilterWhere(['like', 'jev', $this->jev]);

        return $dataProvider;
    }
}
