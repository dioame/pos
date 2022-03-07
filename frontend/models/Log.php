<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property string $date
 * @property string $description
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['description'], 'required'],
            [['description'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'description' => 'Description',
        ];
    }

    /**
     * {@inheritdoc}
     * @return LogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LogQuery(get_called_class());
    }

    public function addLog($description){
        $log = new Log();
        $log->description = $description;
        $log->save(false);
    }
}
