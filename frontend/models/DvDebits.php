<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dv_debits".
 *
 * @property int $id
 * @property int $dv_id
 * @property int $account_code
 * @property double $amount
 *
 * @property DvTracking $dv
 * @property Uacs $accountCode
 */
class DvDebits extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dv_debits';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dv_id', 'account_code', 'amount'], 'required'],
            [['dv_id', 'account_code'], 'integer'],
            [['amount'], 'number'],
            [['dv_id'], 'exist', 'skipOnError' => true, 'targetClass' => DvTracking::className(), 'targetAttribute' => ['dv_id' => 'id']],
            [['account_code'], 'exist', 'skipOnError' => true, 'targetClass' => Uacs::className(), 'targetAttribute' => ['account_code' => 'uacs']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dv_id' => 'Dv ID',
            'account_code' => 'Account Code',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDv()
    {
        return $this->hasOne(DvTracking::className(), ['id' => 'dv_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountCode()
    {
        return $this->hasOne(Uacs::className(), ['uacs' => 'account_code']);
    }
}
