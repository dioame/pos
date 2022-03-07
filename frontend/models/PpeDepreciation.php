<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ppe_depreciation".
 *
 * @property int $id
 * @property int $ppeID
 * @property string $date_from
 * @property string $date_to
 * @property double $amount
 * @property string $date_posted
 * @property string $jev1
 * @property string $jev2
 *
 * @property Ppe $ppe
 * @property JevTracking $jev10
 * @property JevTracking $jev20
 */
class PpeDepreciation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ppe_depreciation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ppeID', 'date_from', 'date_to', 'amount', 'date_posted'], 'required'],
            [['ppeID'], 'integer'],
            [['date_from', 'date_to', 'date_posted'], 'safe'],
            [['amount'], 'number'],
            [['jev1', 'jev2'], 'string', 'max' => 30],
            [['ppeID'], 'exist', 'skipOnError' => true, 'targetClass' => Ppe::className(), 'targetAttribute' => ['ppeID' => 'id']],
            [['jev1'], 'exist', 'skipOnError' => true, 'targetClass' => JevTracking::className(), 'targetAttribute' => ['jev1' => 'jev']],
            [['jev2'], 'exist', 'skipOnError' => true, 'targetClass' => JevTracking::className(), 'targetAttribute' => ['jev2' => 'jev']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ppeID' => 'Depreciation Type',
            'date_from' => 'Date From',
            'date_to' => 'Date To',
            'amount' => 'Amount',
            'date_posted' => 'Date Posted',
            'jev1' => 'JEV',
            'jev2' => 'Jev2',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPpe()
    {
        return $this->hasOne(Ppe::className(), ['id' => 'ppeID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJev10()
    {
        return $this->hasOne(JevTracking::className(), ['jev' => 'jev1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJev20()
    {
        return $this->hasOne(JevTracking::className(), ['jev' => 'jev2']);
    }
}
