<?php

namespace common\components\rest\serializer\model;

use common\components\rest\serializer\model\interfaces\ResponseErrorModelInterface;
use common\components\helper\HttpHelper;

class ValidationErrorModel implements ResponseErrorModelInterface
{
    public int $status_code = HttpHelper::HTTP_UNPROCESSABLE_ENTITY;
    public string $message  = 'Ошибки валидации';
    public ?array $meta     = null;
    public ?array $data     = null;
    public array $errors    = [];

    public function __construct(
        array $errors = [],
        string $message = 'Ошибки валидации',
        int $statusCode = HttpHelper::HTTP_UNPROCESSABLE_ENTITY,
        ?array $meta = null,
        ?array $data = null
    ) {
        $this->errors      = $errors;
        $this->message     = $message;
        $this->status_code = $statusCode;
        $this->meta        = $meta;
        $this->data        = $data;
    }
}
