<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Capitals;

/**
 * CapitalsSearch represents the model behind the search form of `app\models\Capitals`.
 */
class CapitalsSearch extends Capitals
{
    /**
     * {@inheritdoc}
     */
    public $year,$month,$day,$arno;
    public function rules()
    {
        return [
            [['id', 'membersId', 'year', 'month', 'day'], 'integer'],
            [['amount'], 'number'],
            [['date_posted','arno'], 'safe'],
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
        $query = Capitals::find();
        //$query->joinWith('arNo0');

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
            'membersId' => $this->membersId,
            'amount' => $this->amount,
            //'or_ar_tracking.number' => $this->arno,
            //'date_posted' => $this->date_posted,
        ]);

        $query->andFilterWhere(['like', 'YEAR(date_posted)', $this->year]);
        $query->andFilterWhere(['like', 'MONTH(date_posted)', $this->month]);
        $query->andFilterWhere(['like', 'DAY(date_posted)', $this->day]);

        return $dataProvider;
    }
}
