<?php

use common\components\services\ApplesService;
use common\components\services\TreeService;

use common\components\interfaces\ApplesInterface;
use common\components\interfaces\TreeInterface;

use common\components\rest\serializer\model\interfaces\ResponseCollectionModelInterface;
use common\components\rest\serializer\model\interfaces\ResponseErrorModelInterface;
use common\components\rest\serializer\model\CollectionModel;
use common\components\rest\serializer\model\ValidationErrorModel;

use common\components\rest\serializer\model\ResponseModel;
use common\components\rest\serializer\ResponseSerializer;
use common\components\rest\serializer\ResponseSerializerInterface;
use common\components\rest\serializer\model\interfaces\ResponseModelInterface;

return [
    ResponseCollectionModelInterface::class => CollectionModel::class,
    ResponseErrorModelInterface::class => ValidationErrorModel::class,
    ResponseSerializerInterface::class => ResponseSerializer::class,
    ResponseModelInterface::class => ResponseModel::class,
    ApplesInterface::class => ApplesService::class,
    TreeInterface::class => TreeService::class,
];
