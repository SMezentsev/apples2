<?php

namespace app\common\components\rest;

use function in_array;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\filters\HttpCache;
use yii\rest\Controller as ControllerBase;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class Controller extends ControllerBase
{
    /**
     * @var array Список экшенов в которых не нужно производить авторизацию
     */
    public $noAuthActions = [];

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

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

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
