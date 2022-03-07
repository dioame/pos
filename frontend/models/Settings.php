<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property string $attribute
 * @property string $value
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute', 'value'], 'required'],
            [['attribute', 'value'], 'string', 'max' => 300],
            [['attribute'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'attribute' => 'Attribute',
            'value' => 'Value',
        ];
    }
}
