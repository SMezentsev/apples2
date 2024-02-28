<?php

namespace backend\modules\api\v1\controllers;

use common\components\rest\controllers\AbstractJsonRestController;
use common\models\search\TreeSearch;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\interfaces\ApplesInterface;
use common\components\interfaces\TreeInterface;
use common\models\Tree;

use yii\base\Module;

/**
 * Tree controller
 */
class TreeController extends AbstractJsonRestController
{

    protected string $collectionEnvelop = 'trees';
    protected string $entityEnvelop = 'tree';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => [
                                'add',
                                'index',
                                'eat-apple',
                                'delete-apple',
                                'fell-apple',
                                'get-tree',
                                'set-apple-condition',
                            ],
                            'allow' => true,
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'index' => ['GET'],
                        'eat-apple' => ['PUT'],
                        'delete-apple' => ['DELETE'],
                        'fell-apple' => ['PUT'],
                        'set-apple-condition' => ['PUT'],
                        'get-tree' => ['GET'],
                        'add' => ['POST'],
                    ],
                ],
            ]
        );
    }

    protected $treeService;
    protected $applesService;

    public function __construct(
        $id,
        Module $module,
        TreeInterface $treeService,
        ApplesInterface $applesService,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->request = Yii::$app->request;
        $this->treeService = $treeService;
        $this->applesService = $applesService;
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {

        return $this->treeService->getAll();
    }

    /**
     * @return mixed
     */
    public function actionAdd()
    {

        return $this->treeService->add();
    }

    /**
     * @return mixed
     */
    public function actionDeleteApple()
    {

        $this->entityEnvelop = 'apple';
        $params = json_decode($this->request->getRawBody(), true);
        if($apple_id = $params['apple_id']??false) {

            return $this->treeService->deleteApple($apple_id);
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function actionEatApple()
    {

        $this->entityEnvelop = 'apple';
        $params = json_decode($this->request->getRawBody(), true);

        if($apple_id = $params['apple_id']??false) {

            return $this->treeService->eatApple($apple_id);
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function actionGetTree()
    {

        $params = $this->request->queryParams;
        if($tree_id = $params['tree_id']??false) {

            return $this->treeService->get($tree_id);
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function actionFellApple()
    {
        $this->entityEnvelop = 'apple';
        $params = json_decode($this->request->getRawBody(), true);

        if($apple_id = $params['apple_id']??false) {
            return $this->treeService->fellApple($apple_id);
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function actionSetAppleCondition()
    {
        $this->entityEnvelop = 'condition';
        $params = json_decode($this->request->getRawBody(), true);
        $apple_id = $params['apple_id']??false;
        $condition_id = $params['condition_id']??false;

        if($apple_id && $condition_id) {
            return $this->applesService->setCondition($apple_id, $condition_id);
        }
        return false;
    }
}
