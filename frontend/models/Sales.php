<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sales".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $transaction_date
 * @property double $total
 * @property double $amount_paid
 * @property int $paid
 * @property double $sales_on_credit
 * @property string $jev
 * @property int $orNo
 *
 * @property PaymentsReceivable[] $paymentsReceivables
 * @property Customers $customer
 * @property SalesDetails[] $salesDetails
 */
class Sales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $tempor;
    public static function tableName()
    {
        return 'sales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'transaction_date', 'total', 'amount_paid'], 'required'],
            [['customer_id'], 'integer'],
            [['transaction_date','tempor'], 'safe'],
            [['total', 'amount_paid', 'sales_on_credit'], 'number'],
            [['jev'], 'string', 'max' => 30],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'transaction_date' => 'Transaction Date',
            'total' => 'Total Sales',
            'amount_paid' => 'Amount Received',
            'sales_on_credit' => 'Sales on Credit',
            'jev' => 'Jev',
            'orNo' => 'OR No.',
            'tempor' => 'Temporary OR',
        ];
    }

    public function getDetails()
    {
        $payments = PaymentsReceivable::find()->where(['sales_id'=>$this->id])->sum('amount_paid');
        return 'SOA#'.$this->id.' ('.$this->customer->lastname.', '.$this->customer->firstname.') ----> bal. P'.number_format(($this->sales_on_credit-$payments),2);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentsReceivables()
    {
        return $this->hasMany(PaymentsReceivable::className(), ['sales_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customers::className(), ['id' => 'customer_id']);
    }

    public function getCustomerfullname()
    {
        return $this->customer->firstname.' '.$this->customer->lastname;
    }

    public function getOr()
    {
        return $this->hasOne(OrArTracking::className(), ['id' => 'orNo']);
    }

    public function getJev0()
    {
        return $this->hasOne(JevTracking::className(), ['jev' => 'jev']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesDetails()
    {
        return $this->hasMany(SalesDetails::className(), ['sales_id' => 'id']);
    }

    public function getSalesDetails1()
    {
        return $this->hasOne(SalesDetails::className(), ['sales_id' => 'id']);
    }

    public function getTotalSales($month){
        $sales = $month==0?Sales::find()->all():Sales::find()->where(['MONTH(transaction_date)'=>$month])->all();
        $sum = 0;
        foreach ($sales as $sale) {
            $sum += $sale->total;
        }
        return $sum;
    }

    public function getTotalToday(){
        $sales =Sales::find()->where(['date(transaction_date)'=>date('Y-m-d')])->all();
        $sum = 0;
        foreach ($sales as $sale) {
            $sum += $sale->total;
        }
        return $sum;
    }

    public function getTotalCollectible($month){
        $sales = $month==0?Sales::find()->where(['paid'=>'0'])->all():Sales::find()->where(['MONTH(transaction_date)'=>$month,'paid'=>'0'])->all();
        $sum = 0;
        foreach ($sales as $sale) {
            $sum += $sale->total;
        }
        return $sum;
    }

    public function getSalesTrend(){
        $dates = Sales::find()->groupBy('date(transaction_date)')->all();
        $arr = [];
        $sum=0;
        foreach ($dates as $key) {
            $sum = Sales::find()->where(['date(transaction_date)'=>date('Y-m-d',strtotime($key->transaction_date))])->sum('total');
            array_push($arr, [date('U', strtotime($key->transaction_date))*1000,floatval($sum)]);
        }
        return $arr;
        //$sales = Sales::find()->groupBy('date(transaction_date)')->sum('total');
    }
}
