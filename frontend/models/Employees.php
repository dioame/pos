<?php

namespace app\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "employees".
 *
 * @property int $id
 * @property string $lastname
 * @property string $firstname
 * @property string $date_started
 * @property string $address
 * @property string $contact_number
 * @property string $email
 * @property string $position
 */
class Employees extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lastname', 'firstname', 'date_started', 'address', 'position'], 'required'],
            [['date_started'], 'safe'],
            [['lastname', 'firstname', 'address', 'email', 'position'], 'string', 'max' => 300],
            [['contact_number'], 'string', 'max' => 20],
            [['email'], 'unique'],
            [['lastname', 'firstname'], 'unique', 'targetAttribute' => ['lastname', 'firstname']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lastname' => 'Lastname',
            'firstname' => 'Firstname',
            'date_started' => 'Date Started',
            'address' => 'Address',
            'contact_number' => 'Contact Number',
            'email' => 'Email',
            'position' => 'Position',
        ];
    }

    public function getFullList()
    {
        return ucwords(strtolower($this->lastname.", ".$this->firstname)).' - '.$this->position;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['employee_id' => 'id']);
    }
}
