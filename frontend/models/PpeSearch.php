<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ppe;

/**
 * PpeSearch represents the model behind the search form of `app\models\Ppe`.
 */
class PpeSearch extends Ppe
{
    /**
     * {@inheritdoc}
     */
    public $sub_class;
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['particular', 'unit', 'date_acquired', 'uacs', 'sub_class'], 'safe'],
            [['quantity', 'unit_cost', 'eul'], 'number'],
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
        $query = Ppe::find();
        $query->joinWith('uacs0');

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
            'quantity' => $this->quantity,
            'unit_cost' => $this->unit_cost,
            'date_acquired' => $this->date_acquired,
            'eul' => $this->eul,
        ]);

        $query->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'uacs.object_code', $this->uacs])
            ->andFilterWhere(['like', 'uacs.sub_class', $this->sub_class])
            ->andFilterWhere(['like', 'unit', $this->unit]);

        return $dataProvider;
    }
}
