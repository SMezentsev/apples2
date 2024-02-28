<?php

namespace common\components\services;

use Carbon\Carbon;
use common\components\interfaces\ApplesInterface;
use common\components\interfaces\TreeInterface;
use common\models\ProductPosition;
use common\models\Tree;
use common\models\TreeApple;
use common\models\search\TreeSearch;
use yii\base\Module;
use yii\db\ActiveRecord;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * @param object $applesService
 */
class TreeService implements TreeInterface
{

    protected $applesService;

    /**
     * @param $id
     * @param Module $module
     * @param ApplesInterface $applesService
     * @param array $config
     */
    public function __construct(
        ApplesInterface $applesService
    )
    {

        $this->applesService = $applesService;
    }

    /**
     * @param $tree_id
     * @param $apple_id
     * @return TreeApple
     */
    public function addApples($tree_id, $apple_id): TreeApple
    {

        $treeApples = new TreeApple([
            'tree_id' => $tree_id,
            'apple_id' => $apple_id
        ]);
        $treeApples->save();

        return $treeApples;
    }

    /**
     * @param $apple_id
     */
    public function deleteApple($apple_id)
    {

        return $this->applesService->delete($apple_id);
    }

    /**
     * @param $apple_id
     */
    public function eatApple($apple_id)
    {

        return $this->applesService->eat($apple_id);
    }

    /**
     * @param $apple_id
     */
    public function fellApple($apple_id)
    {

        return $this->applesService->setPosition($apple_id, ProductPosition::ON_GROUND);
    }

    /**
     * @param \common\components\interfaces\integer|int|null $id
     * @return Tree
     */
    public function get($id): Tree
    {

        return $this->findModel($id);
    }


    public function getAll()
    {

        try {
            return (new TreeSearch())->search(Yii::$app->request->queryParams);
        } catch (ValidationErrorException $ex) {
            throw $ex;
        }
    }

    /**
     * @return Tree
     */
    public function add(): Tree
    {

        $transaction = Yii::$app->db->beginTransaction();
        try {

            $tree = new Tree();
            $tree->save();

            foreach (range(0,10) as $item) {

                if ($apple = $this->applesService->add()) {

                    $this->addApples($tree->id, $apple->id);
                }
            }

            $transaction->commit();

        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $tree;
    }

    public function remove($id): bool
    {

        return false;
    }

    protected function findModel($id): Tree
    {
        if (!$model = Tree::findOne($id)) {
            throw new NotFoundHttpException(Yii::t('app', 'Не найдено дерево с id={id}', ['id' => $id]));
        }

        return $model;
    }

}