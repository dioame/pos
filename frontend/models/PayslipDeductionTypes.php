<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payslip_deduction_types".
 *
 * @property int $id
 * @property string $type
 */
class PayslipDeductionTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payslip_deduction_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
        ];
    }
}
