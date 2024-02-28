<?php

namespace common\components\rest\serializer\model;

use common\components\rest\serializer\model\interfaces\ResponseErrorModelInterface;
use common\components\helper\HttpHelper;

class ResponseModel implements ResponseErrorModelInterface
{
    public int $status_code = HttpHelper::HTTP_OK;
    public ?array $meta     = null;
    public array $data      = [];

    public function __construct(
        array $data,
        int $statusCode = HttpHelper::HTTP_OK,
        ?array $meta = null
    ) {
        $this->status_code = $statusCode;
        $this->meta        = $meta;
        $this->data        = $data;
    }
}
