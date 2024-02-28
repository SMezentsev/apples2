<?php

namespace common\components\rest\serializer;

use common\components\rest\serializer\model\interfaces\CollectionEnvelopeInterface;
use common\components\rest\serializer\model\interfaces\ResponseCollectionModelInterface;
use common\components\rest\serializer\model\interfaces\ResponseErrorModelInterface;
use common\components\rest\serializer\model\interfaces\ResponseModelInterface;
use common\components\helper\ArrayHelper;
use JsonSerializable;
use Yii;
use yii\base\Arrayable;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\rest\Serializer;

class ResponseSerializer extends Serializer implements ResponseSerializerInterface
{
    public const VALIDATE_STATUS_CODE = 422;

    public string $responseModel           = ResponseModelInterface::class;
    public string $responseCollectionModel = ResponseCollectionModelInterface::class;
    public string $responseErrorModel      = ResponseErrorModelInterface::class;

    /**
     * @var string the name of the envelope (e.g. `items`) for returning the resource objects in a collection.
     *             This is used when serving a resource collection. When this is set and pagination is enabled, the serializer
     *             will return a collection in the following format:
     *
     * ```php
     * [
     *     'items' => [...],  // assuming collectionEnvelope is "items"
     *     '_links' => {  // pagination links as returned by Pagination::getLinks()
     *         'self' => '...',
     *         'next' => '...',
     *         'last' => '...',
     *     },
     *     '_meta' => {  // meta information as returned by Pagination::toArray()
     *         'totalCount' => 100,
     *         'pageCount' => 5,
     *         'currentPage' => 1,
     *         'perPage' => 20,
     *     },
     * ]
     * ```
     *
     * If this property is not set, the resource arrays will be directly returned without using envelope.
     * The pagination information as shown in `_links` and `_meta` can be accessed from the response HTTP headers.
     */
    public $collectionEnvelope = 'items';
    public $entityEnvelop      = 'item';
    /**
     * @var string the name of the envelope (e.g. `_links`) for returning the links objects.
     *             It takes effect only, if `collectionEnvelope` is set.
     *
     * @since 2.0.4
     */
    public $linksEnvelope = 'links';
    /**
     * @var string the name of the envelope (e.g. `_meta`) for returning the pagination object.
     *             It takes effect only, if `collectionEnvelope` is set.
     *
     * @since 2.0.4
     */
    public $metaEnvelope = 'meta';

    /**
     * @throws InvalidConfigException
     *
     * @param mixed $data
     */
    public function serialize($data)
    {
        if ($data instanceof Model) {
            if ($data->hasErrors()) {
                return $this->serializeModelErrors($data);
            }

            return $this->serializeModel($data);
        }

        if ($data instanceof Arrayable) {
            return $this->serializeModel($data);
        }

        if ($data instanceof JsonSerializable) {
            return $this->serializeArray($data->jsonSerialize());
        }

        if ($data instanceof DataProviderInterface) {
            return $this->serializeDataProvider($data);
        }

        if (is_array($data)) {
            return $this->serializeArray($data);
        }

        return parent::serialize($data);
    }

    /**
     * {
     * "status_code": 422,
     *     "message": "Ошибки валидации",
     *     "meta": null,
     *     "data": null,
     *     "errors": [
     *       {
     *        "field": "items",
     *        "message": "Items must be an integer."
     *       }
     *    ]
     * }
     *
     * @throws InvalidConfigException
     *
     * @param mixed $model
     */
    protected function serializeModelErrors($model)
    {
        $result = [];
        foreach ($model->getErrors() as $name => $messages) {
            if (is_array($messages)) {
                foreach ($messages as $mess) {
                    $result[] = [
                        'field'   => $name,
                        'message' => $mess,
                    ];
                }
            } else {
                $result[] = [
                    'field'   => $name,
                    'message' => $messages,
                ];
            }
        }

		$code = isset($model->status_code) && !empty($model->status_code) ? $model->status_code : self::VALIDATE_STATUS_CODE;

        return Yii::createObject(
            $this->responseErrorModel,
            [$result, Yii::t('app', 'Ошибки валидации'), $code]
        );
    }

    /**
     * @throws InvalidConfigException
     *
     * @param mixed $model
     */
    public function serializeModel($model)
    {
        $data = parent::serializeModel($model);

        if (!$this->entityEnvelop) {
            throw new InvalidConfigException(Yii::t('app', 'Требуется задать значение свойству {entityEnvelop}'));
        }

        return Yii::createObject($this->responseModel, [[$this->entityEnvelop => $data]]);
    }

    /**
     * @throws InvalidConfigException
     *
     * @param mixed $dataProvider
     */
    public function serializeDataProvider($dataProvider)
    {
        $data = parent::serializeDataProvider($dataProvider);

        if (!$this->collectionEnvelope) {
            throw new InvalidConfigException(Yii::t('app', 'Требуется задать значение свойству {collectionEnvelope}'));
        }

        return Yii::createObject($this->responseCollectionModel, [
            $dataProvider->getTotalCount(),
            $dataProvider->getPagination()->offset??0,
            $dataProvider->getCount(),
            [$this->collectionEnvelope => $data[$this->collectionEnvelope]],
            Yii::$app->response->statusCode,
        ]);
    }

    /**
     * @throws InvalidConfigException
     */
    public function serializeArray(array $data)
    {
        $serializedArray = [];
        foreach ($data as $key => $value) {
            if (is_object($value) && !($value instanceof CollectionEnvelopeInterface)) {
                throw new InvalidConfigException(Yii::t('app', 'Вложенные модели должны реализовывать {interface}', ['interface' => CollectionEnvelopeInterface::class]));
            }

            if ($value instanceof CollectionEnvelopeInterface) {
                $serializedArray[$value->getEnvelopKey()][] = $value;
            } elseif (is_array($value)) {
                $collectionEnvelop = $this->extractCollectionEnvelop($value);
                if ($collectionEnvelop) {
                    $serializedArray[$collectionEnvelop] =  ArrayHelper::flatten($value);
                } else {
                    $serializedArray[$key] =  ArrayHelper::flatten($value);
                }
            } else {
                $serializedArray[$key] = $value;
            }
        }

        return Yii::createObject($this->responseModel, [
            $serializedArray,
            Yii::$app->response->statusCode,
        ]);
    }

    private function extractCollectionEnvelop(array $items): ?string
    {
        $collectionEnvelop = null;
        foreach ($items as $item) {
            if (is_object($item) && $item instanceof CollectionEnvelopeInterface) {
                $collectionEnvelop = $item->getEnvelopKey();
                break;
            }
        }

        return $collectionEnvelop;
    }
}
