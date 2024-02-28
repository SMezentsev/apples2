<?php


namespace common\components\rest;

use function call_user_func;
use function get_class;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;

class Action extends \yii\base\Action
{

    public $modelClass;
    public $findEntity;
    public $checkAccess;

    /**
     * {@inheritdoc}
     *
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        if (null === $this->modelClass && null === $this->findEntity) {
            throw new InvalidConfigException(sprintf('%s::$modelClass OR %s::$findEntity must be set.', get_class($this), get_class($this)));
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     *
     * @throws NotFoundHttpException
     */
    public function findEntity($id)
    {
        if (null !== $this->findEntity) {
            return call_user_func($this->findEntity, $id, $this);
        }

        if (null !== $id) {
            $class = $this->modelClass;
            $model = $class::find()->byId($id)->one();
        }

        if (isset($model) && null !== $model) {
            return $model;
        }

        throw new NotFoundHttpException('Object not found.');
    }
}
