<?php

namespace backend\modules\api;

use Yii;

class Module extends \yii\base\Module
{

    public function init(): void
    {
        parent::init();

        Yii::configure($this, require __DIR__ . '/config.php');

    }
}
