<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "or_ar_tracking".
 *
 * @property int $id
 * @property int $tracking
 *
 * @property Sales[] $sales
 */
class OrArTracking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'or_ar_tracking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tracking'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tracking' => 'Tracking',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sales::className(), ['orNo' => 'id']);
    }

    public function getNewor(){

        $lastor = OrArTracking::find()->count();
        if($lastor){
            $lastor = $lastor+1;
        }else{
            $lastor = 1;
        }

        return date('Y-').$lastor;
    }
}
