<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customers".
 *
 * @property int $id
 * @property string $lastname
 * @property string $firstname
 * @property double $total_purchases
 *
 * @property Sales[] $sales
 */
class Customers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lastname', 'firstname'], 'required'],
            [['lastname', 'firstname','contactNo','address'], 'string', 'max' => 300],
            [['lastname', 'firstname'], 'unique', 'targetAttribute' => ['lastname', 'firstname']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lastname' => 'Lastname',
            'firstname' => 'Firstname',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sales::className(), ['customer_id' => 'id']);
    }

    public function getNames()
    {
        return $this->lastname.', '.$this->firstname;
    }

    /**
     * @inheritdoc
     * @return CustomersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CustomersQuery(get_called_class());
    }
}
