<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expense_types".
 *
 * @property int $id
 * @property string $name
 * @property double $amount
 * @property string $recommended_supplier
 *
 * @property Expenses[] $expenses
 */
class ExpenseTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expense_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'amount', 'recommended_supplier'], 'required'],
            [['amount'], 'number'],
            [['name', 'recommended_supplier'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'amount' => 'Amount',
            'recommended_supplier' => 'Recommended Supplier',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(Expenses::className(), ['type' => 'id']);
    }
}
