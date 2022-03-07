<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pcv_tracking".
 *
 * @property int $id
 * @property string $pcv_number
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
 * @property string $received_by
 * @property string $jev
 *
 * @property JevTracking $jev0
 * @property Uacs $debit0
 * @property Uacs $credit0
 */
class PcvTracking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pcv_tracking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pcv_number', 'date_posted', 'amount', 'type', 'debit', 'credit'], 'required'],
            [['date_posted'], 'safe'],
            [['amount'], 'number'],
            [['payee', 'debit', 'credit', 'prepared_by', 'requested_by', 'approved_by'], 'integer'],
            [['pcv_number', 'jev'], 'string', 'max' => 30],
            [['particular', 'type', 'received_by'], 'string', 'max' => 300],
            [['pcv_number'], 'unique'],
            [['jev'], 'exist', 'skipOnError' => true, 'targetClass' => JevTracking::className(), 'targetAttribute' => ['jev' => 'jev']],
            [['debit'], 'exist', 'skipOnError' => true, 'targetClass' => Uacs::className(), 'targetAttribute' => ['debit' => 'uacs']],
            [['credit'], 'exist', 'skipOnError' => true, 'targetClass' => Uacs::className(), 'targetAttribute' => ['credit' => 'uacs']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pcv_number' => 'Dv Number',
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
            'jev' => 'Jev',
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
    public function getDebit0()
    {
        return $this->hasOne(Uacs::className(), ['uacs' => 'debit']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredit0()
    {
        return $this->hasOne(Uacs::className(), ['uacs' => 'credit']);
    }

    public function getPayee0()
    {
        return $this->hasOne(Suppliers::className(), ['id' => 'payee']);
    }

    public function getPrepared()
    {
        return $this->hasOne(Officers::className(), ['id' => 'prepared_by']);
    }

    public function getApproved()
    {
        return $this->hasOne(Officers::className(), ['id' => 'approved_by']);
    }

    /*public function createDv($date,$remarks,$type){
        $lastjev = JevTracking::find()->orderBy('id DESC')->one();
        if($lastjev){
            $pieces = explode("-",$lastjev->jev);
            $lastjev = (int)$pieces[1]+1;
        }else{
            $lastjev = 1;
        }

        $jevtracking = new JevTracking();
        $jevtracking->jev = date('Y-',strtotime($date)).$lastjev;
        $jevtracking->date_posted = $date;
        $jevtracking->remarks = $remarks;
        $jevtracking->source = $type;
        $jevtracking->isClosingEntry = 'no';
        $jevtracking->save();

        return $jevtracking->jev;
    }*/

    public function tempDv(){
        $lastdv = PcvTracking::find()->orderBy('id DESC')->one();
        if($lastdv){
            $pieces = explode("-",$lastdv->pcv_number);
            $lastdv = (int)$pieces[1]+1;
        }else{
            $lastdv = 1;
        }

        return date('Y-').$lastdv;
    }
}
