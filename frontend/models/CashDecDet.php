<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cash_dec_det".
 *
 * @property int $id
 * @property string $type
 * @property int $count
 * @property int $cash_dec
 *
 * @property CashDec $cashDec
 */
class CashDecDet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cash_dec_det';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'cash_dec'], 'required'],
            [['count', 'cash_dec'], 'integer'],
            [['type'], 'string', 'max' => 300],
            [['cash_dec'], 'exist', 'skipOnError' => true, 'targetClass' => CashDec::className(), 'targetAttribute' => ['cash_dec' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'count' => 'Count',
            'cash_dec' => 'Cash Dec',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCashDec()
    {
        return $this->hasOne(CashDec::className(), ['id' => 'cash_dec']);
    }
}
