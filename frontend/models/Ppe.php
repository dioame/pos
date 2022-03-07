<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ppe".
 *
 * @property int $id
 * @property int $uacs
 * @property string $particular
 * @property double $quantity
 * @property string $unit
 * @property double $unit_cost
 * @property string $date_acquired
 * @property double $eul
 *
 * @property Uacs $uacs0
 */
class Ppe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $fund_source;
    public static function tableName()
    {
        return 'ppe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uacs', 'particular', 'quantity', 'unit_cost', 'date_acquired', 'eul','fund_source'], 'required'],
            [['uacs'], 'integer'],
            [['quantity', 'unit_cost', 'eul'], 'number'],
            [['date_acquired','warranty_period','receipt_number'], 'safe'],
            [['particular', 'unit'], 'string', 'max' => 300],
            [['uacs'], 'exist', 'skipOnError' => true, 'targetClass' => Uacs::className(), 'targetAttribute' => ['uacs' => 'uacs']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uacs' => 'Type',
            'particular' => 'Particular/Specification',
            'quantity' => 'Quantity',
            'unit' => 'Unit',
            'unit_cost' => 'Unit Cost',
            'date_acquired' => 'Date Acquired',
            'eul' => 'Estimated Useful Life',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUacs0()
    {
        return $this->hasOne(Uacs::className(), ['uacs' => 'uacs']);
    }
}
