<?php

namespace common\components\rest\controllers;

use common\components\rest\interfaces\ApiControllerInterface;
use common\components\rest\serializer\model\interfaces\ResponseCollectionModelInterface;
use common\components\rest\serializer\model\interfaces\ResponseErrorModelInterface;
use common\components\rest\serializer\model\interfaces\ResponseModelInterface;
use common\components\rest\serializer\ResponseSerializerInterface;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\filters\HttpCache;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;

abstract class AbstractJsonRestController extends Controller implements ApiControllerInterface
{
    protected const DEFAULT_COLLECTION_ENVELOP = 'items';
    protected const DEFAULT_ENTITY_ENVELOP     = 'item';

    /**
     * @var array Список экшенов в которых не нужно производить авторизацию
     */
    public $noAuthActions = [];

    public $serializer = ResponseSerializerInterface::class;

    /**
     * Модель - используется для отдачи структурированного респонса - для одной объекта-модели или массива
     *
     * @see app\components\rest\serializer\ResponseSerializer
     */
    protected string $responseModel           = ResponseModelInterface::class;
    /**
     * Модель - используется для отдачи структурированного респонса - для нескольких объектов-моделей
     *
     * @see app\components\rest\serializer\ResponseSerializer
     */
    protected string $responseCollectionModel = ResponseCollectionModelInterface::class;
    /**
     * Модель - используется для отдачи структурированного респонса - для выдачи ошибок валидации
     *
     * @see app\components\rest\serializer\ResponseSerializer
     */
    protected string $responseErrorModel      = ResponseErrorModelInterface::class;

    /**
     * 	Ключ в который обернуть контент со списком сущностей
     *
     * @see  app\components\rest\serializer\ResponseSerializer
     */
    protected string $collectionEnvelop = self::DEFAULT_COLLECTION_ENVELOP;
    /**
     * 	Ключ в который обернуть контент с одной сущностью или массивом
     *
     * @see  app\components\rest\serializer\ResponseSerializer
     */
    protected string $entityEnvelop     = self::DEFAULT_ENTITY_ENVELOP;

    public function serializeData($data)
    {

        return $this->getSerializer()->serialize($data);
    }

    public function getSerializer(): ResponseSerializerInterface
    {

        return Yii::createObject([
            'class'                   => $this->serializer,
            'responseModel'           => $this->getResponseModel(),
            'responseCollectionModel' => $this->getResponseCollectionModel(),
            'responseErrorModel'      => $this->getErrorResponseModel(),
            'collectionEnvelope'      => $this->getCollectionEnvelope(),
            'entityEnvelop'           => $this->getEntityEnvelope(),
        ]);
    }

    public function getResponseModel(): string
    {
        return $this->responseModel;
    }

    public function getErrorResponseModel(): string
    {
        return $this->responseErrorModel;
    }

    public function getResponseCollectionModel(): string
    {
        return $this->responseCollectionModel;
    }

    public function getCollectionEnvelope(): string
    {
        return $this->collectionEnvelop;
    }

    public function getEntityEnvelope(): string
    {
        return $this->entityEnvelop;
    }

    /**
     * @param $action
     *
     * @return bool
     *
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        /*
         * Если action id в списке исключений, отключаем авторизацию
         */
        if ([] !== $this->noAuthActions && in_array($action->id, $this->noAuthActions)) {
            $this->detachBehavior('authenticator');
        }

        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if (isset($behaviors['rateLimiter'])) {
            unset($behaviors['rateLimiter']);
        }

        if (isset($behaviors['authenticator'])) {
            unset($behaviors['authenticator']);
        }

        $behaviors['cors'] = [
            'class' => Cors::class,
            'cors'  => [
                'Origin'                           => ['*'],
                'Access-Control-Request-Method'    => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers'   => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age'           => 86400,
                'Access-Control-Expose-Headers'    => [
                    'X-Client-Version',
                    'X-Api-Version',
                    'X-Api-Host',
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Total-Count',
                    'X-Pagination-Per-Page',

                    'X-Rate-Limit-Limit',
                    'X-Rate-Limit-Remaining',
                    'X-Rate-Limit-Reset',

                    'X-Auto-Version',
                ],
            ],
        ];

//        $behaviors['authenticator'] = [
//            'class' => HttpBearerAuth::class,
//        ];

        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => Response::FORMAT_JSON,
            'application/pdf'  => Response::FORMAT_RAW,
        ];

        $behaviors['httpCache'] = [
            'class'              => HttpCache::class,
            'cacheControlHeader' => 'must-revalidate, private, max-age=60',
            'lastModified'       => static function ($action, $params) {
                return time();
            },
        ];

        return $behaviors;
    }
}
