<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Uacs;

/**
 * UacsSearch represents the model behind the search form of `app\models\Uacs`.
 */
class UacsSearch extends Uacs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['classification', 'sub_class', 'grouping', 'object_code', 'status', 'isEnabled', 'payment_account'], 'safe'],
            [['uacs'], 'integer'],
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
        $query = Uacs::find();

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
            'uacs' => $this->uacs,
            'payment_account' => $this->payment_account,
        ]);

        $query->andFilterWhere(['like', 'classification', $this->classification])
            ->andFilterWhere(['like', 'sub_class', $this->sub_class])
            ->andFilterWhere(['like', 'grouping', $this->grouping])
            ->andFilterWhere(['like', 'object_code', $this->object_code])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'isEnabled', $this->isEnabled]);

        return $dataProvider;
    }
}
