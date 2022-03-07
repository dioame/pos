<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Officers;

/**
 * OfficersSearch represents the model behind the search form of `app\models\Officers`.
 */
class OfficersSearch extends Officers
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'pID', 'mID'], 'integer'],
            [['start', 'end'], 'safe'],
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
        $query = Officers::find();

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
            'pID' => $this->pID,
            'mID' => $this->mID,
            'start' => $this->start,
            'end' => $this->end,
        ]);

        return $dataProvider;
    }
}
