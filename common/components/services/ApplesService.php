<?php

namespace common\components\services;

use Carbon\Carbon;
use common\components\interfaces\ApplesInterface;
use common\models\Apple;

use common\models\ApplePosition;
use common\models\AppleCondition;

use common\models\ProductPosition;
use common\models\ProductCondition;
use common\helpers\Colors;

use yii\web\NotFoundHttpException;

/**
 *
 */
class ApplesService implements ApplesInterface
{

    public function add(): Apple
    {

        $apple = new Apple([
            'color' => Colors::getRandomColor(),
            'amount' => rand(20, 100)
        ]);

        if ($apple->save()) {

            //Размещаем яблоко на дереве
            $this->setPosition($apple->id, rand(ProductPosition::ON_TREE, ProductPosition::ON_GROUND));
            //Записываем состояние
            $this->setCondition($apple->id);
        }

        return $apple;
    }

    public function fell($id = null): Apple
    {

        if ($apple = $this->findModel($id)) {

            return $this->setPosition($id);
        }
        return $apple;
    }

    public function setPosition($id = null, $position = null)
    {

        $applePosition = ApplePosition::findOne(['apple_id' => $id]);
        if (!$applePosition) {
            $applePosition = new ApplePosition([
                'apple_id' => $id,
                'product_position_id' => $position ?? ProductPosition::ON_TREE
            ]);
        } else {

            $applePosition->product_position_id = $position ?? ProductPosition::ON_TREE;
        }
        $applePosition->save();
        return $applePosition;
    }

    public function setCondition($id, $condition = null)
    {

        $applePosition = new AppleCondition([
            'apple_id' => $id,
            'product_condition_id' => $condition ?? ProductCondition::PRODUCT_FRESH
        ]);
        $applePosition->save();
        return $applePosition;
    }

    public function eat($id): Apple
    {

        if ($apple = $this->findModel($id)) {

            $apple->amount = ($apple->amount - 10) > 0 ? $apple->amount - 10 : 0;
            if(!$apple->amount) {
                $apple->deleted_at = Carbon::now()->format('Y-m-d H:i:s');
            }
            $apple->save();
        }
        return $apple;
    }

    public function get($id): Apple
    {

        return $this->findModel($id);
    }

    public function delete($id)
    {

        if ($apple = $this->get($id)) {

            $apple->deleted_at = Carbon::now()->format('Y-m-d H:i:s');
            $apple->save();
        }
        return $apple;
    }

    protected function findModel($id): Apple
    {
        if (!$model = Apple::findOne($id)) {
            throw new NotFoundHttpException(Yii::t('app', 'Не найдено яблоко с id={id}', ['id' => $id]));
        }
        return $model;
    }

}