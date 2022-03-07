<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SalesDetails]].
 *
 * @see SalesDetails
 */
class SalesDetailsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SalesDetails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SalesDetails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
