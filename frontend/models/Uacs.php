<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "uacs".
 *
 * @property string $classification
 * @property string $sub_class
 * @property string $grouping
 * @property string $object_code
 * @property int $uacs
 * @property string $status
 * @property string $isEnabled
 *
 * @property Expenses[] $expenses
 * @property JevEntries[] $jevEntries
 */
class Uacs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'uacs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uacs'], 'required'],
            [['uacs'], 'integer'],
            [['classification', 'sub_class', 'grouping'], 'string', 'max' => 100],
            [['object_code'], 'string', 'max' => 94],
            [['status'], 'string', 'max' => 6],
            [['isEnabled'], 'string', 'max' => 10],
            [['uacs'], 'unique'],
            [['payment_account'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'classification' => 'Classification',
            'sub_class' => 'Sub Class',
            'grouping' => 'Grouping',
            'object_code' => 'Object Code',
            'uacs' => 'Uacs',
            'status' => 'Status',
            'isEnabled' => 'Is Enabled',
            'payment_account' => 'Payment Account',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(Expenses::className(), ['type' => 'uacs']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJevEntries()
    {
        return $this->hasMany(JevEntries::className(), ['accounting_code' => 'uacs']);
    }
}
