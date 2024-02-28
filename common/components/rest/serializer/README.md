# примеры использования сериалайзера

```php
// в api/config/di_definitions.php определены дефолтные реализации для сериалайзера

use api\components\serializer\model\CollectionModel;
use api\components\serializer\model\interfaces\ResponseCollectionModelInterface;
use api\components\serializer\model\interfaces\ResponseErrorModelInterface;
use api\components\serializer\model\interfaces\ResponseModelInterface;
use api\components\serializer\model\ResponseModel;
use api\components\serializer\model\ValidationErrorModel;
use api\components\serializer\ResponseSerializer;
use api\components\serializer\ResponseSerializerInterface;

return [
	// сам сериалайзер
	ResponseSerializerInterface::class      => ResponseSerializer::class,
	// одна модель
	ResponseModelInterface::class           => ResponseModel::class,
	// коллекция моделей
	ResponseCollectionModelInterface::class => CollectionModel::class,
	// errors validation
	ResponseErrorModelInterface::class      => ValidationErrorModel::class,
];
```

### Абстрактный контроллер

Все апи контроллеры наследуем от него. В дочерних переопределяем логику и свойства.

```php


abstract class AbstractJsonRestController extends Controller implements ApiControllerInterface
{
    protected const DEFAULT_COLLECTION_ENVELOP = 'items';
    protected const DEFAULT_ENTITY_ENVELOP = 'item';

    public $serializer = ResponseSerializerInterface::class;

	/**
	 * Модель - используется для отдачи структурированного респонса - для одной объекта-модели или массива
	 * @see api\components\serializer\ResponseSerializer
	 */
    protected string $responseModel           = ResponseModelInterface::class;
	/**
	 * Модель - используется для отдачи структурированного респонса - для нескольких объектов-моделей
	 * @see \api\components\serializer\ResponseSerializer
	 */
    protected string $responseCollectionModel = ResponseCollectionModelInterface::class;
	/**
	 * Модель - используется для отдачи структурированного респонса - для выдачи ошибок валидации
	 * @see\ api\components\serializer\ResponseSerializer
	 */
    protected string $responseErrorModel      = ResponseErrorModelInterface::class;

	/**
	 * 	Ключ в который обернуть контент со списком сущностей
	 * @see \api\components\serializer\ResponseSerializer
	 */
    protected string $collectionEnvelop = self::DEFAULT_COLLECTION_ENVELOP;
	/**
	 * 	Ключ в который обернуть контент с одной сущностью или массивом
	 * @see \api\components\serializer\ResponseSerializer
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
            'responseCollectionModel' => $this->getresponseCollectionModel(),
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

    public function getresponseCollectionModel(): string
    {
        return $this->responseCollectionModel;
    }

    public function getCollectionEnvelope(): string
    {
        return $this->collectionEnvelop;
    }

	public function getEntityEnvelope():string
	{
		return $this->entityEnvelop;
	}
}


<?php

namespace api\controllers;

use api\components\controller\AbstractJsonRestController;
use common\models\AutoGoods;
use yii\data\ActiveDataProvider;
use api\components\serializer\model\example\DataProviderModel;

class SiteController extends AbstractJsonRestController
{
	protected string $collectionEnvelop = 'users';
	protected string $entityEnvelop = 'user';

	// return ActiveDataProvider
	/*
	{
	  "total": 2809,
	  "offset": 0,
	  "limit": 20,
	  "status_code": 200,
	  "meta": {
	    "pageCount": 141,
	    "currentPage": 1,
	    "perPage": 20
	  },
	  "data": {
	    "users": [
	      {
	        "id": 3675,
	        "auto_brand_id": 44,
	        "auto_model_id": 500,
	        "auto_generation_id": 1089,
	        "auto_modification_id": 3600,
	        "goods_category_id": 14,
	        "goods_sku": "839338",
	        "payload": null,
	        "updated_at": "2022-01-12 21:41:20"
	      },
		  ...
	    ]
	  }
	}
	*/
	public function actionIndex()
	{
		/** ActiveDataProvider - collection models AR */

		return new ActiveDataProvider([
			'query' => AutoGoods::find(),
		]);;
	}


	// return ActiveDataProvider
	/*
	 {
	  "status_code": 200,
	  "meta": null,
	  "data": {
	    "shop": {
	      "shop_id": 0,
	      "region_id": 1,
	      "network_id": 7,
	      "short_name": "ВСЕ МАГАЗИНЫ",
	      "short_name_declination": "ВСЕ МАГАЗИНЫ",
	      "long_name": "ВСЕ МАГАЗИНЫ",
	      "url": "-",
	      "address": "-",
	      "service_desc": "-",
	      "working_hours": "Пн-Пт 09:00-20:00<br><font color=\"red\">Сб-Вс 10:00-18:00</font>",
	      "credit_cards": 0,
	      "map_coords": "0,0",
	      "num_lines": 1,
	      "owned_by": null,
	      "metro_color": "white"
	    }
	  }
	 }
	 */
	public function actionIndex2()
	{
		/** one model AR */
		$this->collectionEnvelop = 'shop';
		$model = Shop::find()->one();
	}


	// массив mixed - multiple models
	// response:
	/*
		{
		  "status_code": 200,
		  "meta": null,
		  "data": {
		    "dataProviderModel": {
		      "items": null
		    },
		    "key2": "val2",
		    "key3": "val3"
		  }
		}
	*/
	public function actionIndex3()
	{
		return [
			'key1' => new DataProviderModel(), // CollectionEnvelopeInterface
			'key2' => 'val2',
			'key3' => 'val3',
		];
	}
	/**
* {
  "status_code": 200,
  "meta": null,
  "data": {
    "Region": [
      {
        "id": 0,
        "priceZoneId": 0,
        "slug": null,
        "title": "-all-",
        "titleDeclination": "-all-",
        ...
      },
      ...
    ],
    "AutoGoods": [
      {
        "id": 3696,
        "auto_brand_id": 36,
        "auto_model_id": 418,
        "auto_generation_id": 937,
        "auto_modification_id": 3155,
      	...
      },
      ...
    ]
  }
}
 */
	public function actionIndex33()
	{
		return [
			'Region' => Region::find()->all(), // CollectionEnvelopeInterface
			'AutoGoods' => AutoGoods::find()->all(),
		];
	}
/**
* with interface CollectionEnvelopeInterface
 *
{
  "status_code": 200,
  "meta": null,
  "data": {
    Region->getEnvelopKey() : [
      {
        "id": 0,
        "priceZoneId": 0,
        "slug": null,
        "title": "-all-",
        "titleDeclination": "-all-",
        ...
      },
      ...
    ],
    AutoGoods->getEnvelopKey(): [
      {
        "id": 3696,
        "auto_brand_id": 36,
        "auto_model_id": 418,
        "auto_generation_id": 937,
        "auto_modification_id": 3155,
      	...
      },
      ...
    ]
  }
}
 */
	public function actionIndex34()
	{
		// Region implements CollectionEnvelopeInterface::getEnvelopKey(): string
		// AutoGoods implements CollectionEnvelopeInterface::getEnvelopKey(): string
		return [
			Region::find()->all(), // CollectionEnvelopeInterface
			AutoGoods::find()->all(),
			...
		];
	}

	// validation error
	/*
	 {
		  "status_code": 400,
		  "message": "Ошибки валидации",
		  "meta": null,
		  "data": null,
		  "errors": [
		    {
		      "field": "items",
		      "message": "Items cannot be blank."
		    }
		  ]
	 }
	 */
	public function actionIndex4()
	{
		$model = new DataProviderModel();
		if (!$model->validate()) {
			Yii::$app->response->statusCode = HttpHelper::HTTP_UNPROCESSABLE_ENTITY;

			return $model;
		}

		return $model;
	}
}

```

### Модель используемая в примере

```php
<?php

namespace api\components\serializer\model\example;

use api\components\serializer\model\interfaces\CollectionEnvelopeInterface;
use yii\base\Model;

class DataProviderModel extends Model implements CollectionEnvelopeInterface
{
	private const COLLECTION_ENVELOPE_KEY = 'dataProviderModel';
	public $items;

	public function rules()
	{
		return [
			['items', 'required','skipOnEmpty' => false],
			['items', 'integer','skipOnEmpty' => false],
			['items', 'filter', 'filter' => 'intval'],
		];
	}

	public function getEnvelopKey(): string
	{
		return self::COLLECTION_ENVELOPE_KEY;
	}
}

```
