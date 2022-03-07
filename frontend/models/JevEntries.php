<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jev_entries".
 *
 * @property int $id
 * @property string $jev
 * @property int $accounting_code
 * @property string $type
 * @property double $amount
 *
 * @property JevTracking $jev0
 * @property Uacs $accountingCode
 */
class JevEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jev_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jev', 'accounting_code', 'type', 'amount'], 'required'],
            [['accounting_code'], 'integer'],
            [['type'], 'string'],
            [['amount'], 'number'],
            [['jev'], 'string', 'max' => 30],
            [['jev'], 'exist', 'skipOnError' => true, 'targetClass' => JevTracking::className(), 'targetAttribute' => ['jev' => 'jev']],
            [['accounting_code'], 'exist', 'skipOnError' => true, 'targetClass' => Uacs::className(), 'targetAttribute' => ['accounting_code' => 'uacs']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jev' => 'Jev',
            'accounting_code' => 'Accounting Code',
            'type' => 'Type',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJev0()
    {
        return $this->hasOne(JevTracking::className(), ['jev' => 'jev']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountingCode()
    {
        return $this->hasOne(Uacs::className(), ['uacs' => 'accounting_code']);
    }

    public function getAssetBalance($code){
        $totalin = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>$code,'type'=>'debit'])->sum('amount');
        $balance = $totalin-JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>$code,'type'=>'credit'])->sum('amount');
        return $balance;
    }

    public function insertAccount($jev,$type,$account,$amount){
        $entry = new JevEntries();
        $entry->jev = $jev;
        $entry->type= $type;
        $entry->accounting_code = $account;
        $entry->amount= $amount;
        $entry->save();
    }

    
}
