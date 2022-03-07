<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dv_tracking".
 *
 * @property int $id
 * @property string $dv_number
 * @property string $date_posted
 * @property double $amount
 * @property int $payee
 * @property string $particular
 * @property string $type
 * @property int $debit
 * @property int $credit
 * @property int $prepared_by
 * @property int $requested_by
 * @property int $approved_by
 * @property int $received_by
 *
 * @property AccountsPayablePayment[] $accountsPayablePayments
 * @property Expenses[] $expenses
 */
class DvTracking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $amount_paid, $balance;
    public static function tableName()
    {
        return 'dv_tracking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dv_number', 'date_posted', 'amount', 'type', 'debit', 'credit','particular'], 'required'],
            [['date_posted', 'requested_by', 'received_by','jev', 'payee', 'prepared_by', 'approved_by', 'amount_paid', 'balance'], 'safe'],
            [['amount'], 'number'],
            [['payee', 'debit', 'credit'], 'integer'],
            [['dv_number'], 'string', 'max' => 30],
            [['particular', 'type'], 'string', 'max' => 300],
            [['dv_number'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dv_number' => 'DV #',
            'date_posted' => 'Date Posted',
            'amount' => 'Amount',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'type' => 'Type',
            'debit' => 'Debit',
            'credit' => 'Credit',
            'prepared_by' => 'Prepared By',
            'requested_by' => 'Requested By',
            'approved_by' => 'Approved By',
            'received_by' => 'Received By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountsPayablePayments()
    {
        return $this->hasMany(AccountsPayablePayment::className(), ['dv_number' => 'dv_number']);
    }

    public function getDebit0()
    {
        return $this->hasOne(Uacs::className(), ['uacs' => 'debit']);
    }

    public function getPayee0()
    {
        return $this->hasOne(Suppliers::className(), ['id' => 'payee']);
    }

    public function getCredit0()
    {
        return $this->hasOne(Uacs::className(), ['uacs' => 'credit']);
    }

    public function getPrepared()
    {
        return $this->hasOne(Officers::className(), ['id' => 'prepared_by']);
    }

    public function getApproved()
    {
        return $this->hasOne(Officers::className(), ['id' => 'approved_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(Expenses::className(), ['dv' => 'dv_number']);
    }

    public function getNewdvnumber(){

        $lastdv = DvTracking::find()->orderBy('id DESC')->one();
        if($lastdv){
            $pieces = explode("-",$lastdv->dv_number);
            $lastdv = (int)$pieces[1]+1;
        }else{
            $lastdv = 1;
        }

        return date('Y-').$lastdv;
    }
}
