<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "monthly_dues".
 *
 * @property int $id
 * @property int $mID
 * @property string $month
 * @property string $year
 * @property double $amount
 * @property string $jev
 *
 * @property Members $m
 * @property JevTracking $jev0
 */
class MonthlyDues extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'monthly_dues';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mID', 'month', 'year', 'amount'], 'required'],
            [['mID'], 'integer'],
            [['month'], 'string'],
            [['year'], 'safe'],
            [['amount'], 'number'],
            [['jev'], 'string', 'max' => 30],
            [['mID'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['mID' => 'id']],
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
            'mID' => 'Members',
            'month' => 'Month',
            'year' => 'Year',
            'amount' => 'Amount',
            'jev' => 'Jev',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getM()
    {
        return $this->hasOne(Members::className(), ['id' => 'mID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJev0()
    {
        return $this->hasOne(JevTracking::className(), ['jev' => 'jev']);
    }
}
