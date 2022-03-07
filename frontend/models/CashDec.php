<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cash_dec".
 *
 * @property int $id
 * @property string $date_posted
 */
class CashDec extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cash_dec';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_posted'], 'required'],
            [['date_posted'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_posted' => 'Date Posted',
        ];
    }
}
