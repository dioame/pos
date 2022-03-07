<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "suppliers".
 *
 * @property int $id
 * @property string $supplier_name
 * @property string $contact_number
 * @property string $email_address
 * @property string $tin
 *
 * @property Expenses[] $expenses
 */
class Suppliers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'suppliers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplier_name'], 'required'],
            [['supplier_name', 'email_address'], 'string', 'max' => 300],
            [['contact_number'], 'string', 'max' => 20],
            [['tin'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_name' => 'Supplier Name',
            'contact_number' => 'Contact Number',
            'email_address' => 'Email Address',
            'tin' => 'Tin',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(Expenses::className(), ['supplier' => 'id']);
    }
}
