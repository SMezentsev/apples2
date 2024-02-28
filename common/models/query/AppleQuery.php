<?php

namespace common\models\query;

use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;

class AppleQuery extends ActiveQuery
{

    public function notDeleted()
    {
        return $this->andWhere('deleted_at is NULL');
    }
}
