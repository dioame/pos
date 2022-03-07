<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jev_tracking".
 *
 * @property int $id
 * @property string $jev
 * @property string $date_posted
 * @property string $remarks
 * @property string $source
 *
 * @property JevEntries[] $jevEntries
 */
class JevTracking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jev_tracking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jev', 'date_posted', 'remarks'], 'required'],
            [['date_posted', 'isClosingEntry', 'source'], 'safe'],
            [['jev'], 'string', 'max' => 30],
            [['remarks', 'source'], 'string', 'max' => 300],
            [['jev'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jev' => 'Jev',
            'date_posted' => 'Date Posted',
            'remarks' => 'Remarks',
            'source' => 'Source',
            'isClosingEntry' => 'Is Closing Entry',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJevEntries()
    {
        return $this->hasMany(JevEntries::className(), ['jev' => 'jev']);
    }

    public function createJev($date,$remarks,$type){
        $lastjev = JevTracking::find()->orderBy('id DESC')->one();
        if($lastjev){
            $pieces = explode("-",$lastjev->jev);
            $lastjev = (int)$pieces[1]+1;
        }else{
            $lastjev = 1;
        }

        $jevtracking = new JevTracking();
        $jevtracking->jev = date('Y-',strtotime($date)).$lastjev;
        $jevtracking->date_posted = $date;
        $jevtracking->remarks = $remarks;
        $jevtracking->source = $type;
        $jevtracking->isClosingEntry = 'no';
        $jevtracking->save();

        return $jevtracking->jev;
    }

    public function tempJev(){
        $lastjev = JevTracking::find()->orderBy('id DESC')->one();
        if($lastjev){
            $pieces = explode("-",$lastjev->jev);
            $lastjev = (int)$pieces[1]+1;
        }else{
            $lastjev = 1;
        }

        return date('Y-').$lastjev;
    }
}
