<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "officers".
 *
 * @property int $id
 * @property int $pID
 * @property int $mID
 * @property string $start
 * @property string $end
 */
class Officers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'officers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pID', 'mID', 'start', 'end'], 'required'],
            [['pID', 'mID'], 'integer'],
            [['start', 'end'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pID' => 'Position',
            'mID' => 'Name',
            'start' => 'Date Started',
            'end' => 'Date Ended',
        ];
    }

    public function getP()
    {
        return $this->hasOne(OfficersPositions::className(), ['id' => 'pID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getM()
    {
        return $this->hasOne(Members::className(), ['id' => 'mID']);
    }

    public function getFullList()
    {
        return ucwords(strtolower($this->m->lastname.", ".$this->m->firstname." - ".$this->p->title));
    }

    public function getNameandposition()
    {
        return '<b>'.ucwords(strtolower($this->m->lastname.", ".$this->m->firstname))."</b><br>".$this->p->title;
    }
}
