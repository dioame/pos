<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "officers_positions".
 *
 * @property int $id
 * @property string $title
 * @property double $honorarium
 */
class OfficersPositions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'officers_positions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'honorarium'], 'required'],
            [['honorarium'], 'number'],
            [['title'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'honorarium' => 'Honorarium',
        ];
    }
}
