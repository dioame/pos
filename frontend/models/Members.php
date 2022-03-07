<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "members".
 *
 * @property int $id
 * @property string $lastname
 * @property string $firstname
 * @property string $gender
 * @property string $date_started
 *
 * @property Capitals[] $capitals
 */
class Members extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'members';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lastname', 'firstname', 'gender', 'date_started'], 'required'],
            [['gender'], 'string'],
            [['date_started'], 'safe'],
            [['lastname', 'firstname'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lastname' => 'Lastname',
            'firstname' => 'Firstname',
            'gender' => 'Gender',
            'date_started' => 'Date Started',
        ];
    }

    public function getFullList()
    {
        return ucwords(strtolower($this->lastname.", ".$this->firstname));
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapitals()
    {
        return $this->hasMany(Capitals::className(), ['membersId' => 'id']);
    }
}
