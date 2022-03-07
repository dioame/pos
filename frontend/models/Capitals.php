<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "capitals".
 *
 * @property int $id
 * @property int $membersId
 * @property double $amount
 * @property string $type
 * @property string $date_posted
 * @property int $arNo
 * @property string $jev
 *
 * @property Members $members
 * @property OrArTracking $arNo0
 * @property JevTracking $jev0
 */
class Capitals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'capitals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['membersId', 'amount', 'type', 'date_posted'], 'required'],
            [['membersId'], 'integer'],
            [['amount'], 'number'],
            [['date_posted'], 'safe'],
            [['arNo'], 'string', 'max' => 300],
            [['jev'], 'string', 'max' => 30],
            [['arNo'], 'unique'],
            [['membersId'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['membersId' => 'id']],
            [['jev'], 'exist', 'skipOnError' => true, 'targetClass' => JevTracking::className(), 'targetAttribute' => ['jev' => 'jev']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'membersId' => 'Members ID',
            'amount' => 'Amount',
            'type' => 'Type',
            'date_posted' => 'Date Posted',
            'arNo' => 'Ar No',
            'jev' => 'Jev',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasOne(Members::className(), ['id' => 'membersId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJev0()
    {
        return $this->hasOne(JevTracking::className(), ['jev' => 'jev']);
    }
}
