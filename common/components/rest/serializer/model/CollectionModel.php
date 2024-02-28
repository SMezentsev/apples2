<?php

namespace common\components\rest\serializer\model;

use common\components\rest\serializer\model\interfaces\ResponseCollectionModelInterface;
use common\components\helper\HttpHelper;

class CollectionModel implements ResponseCollectionModelInterface
{
    public int $status_code = HttpHelper::HTTP_OK;
    public ?array $meta     = null;
    public array $data      = [];

    private ?int $total     = null;
    private ?int $offset    = null;
    private ?int $limit     = null;

    public function __construct(
        int $total,
        int $offset,
        int $limit,
        array $data,
        int $statusCode = HttpHelper::HTTP_OK,
        ?array $meta = null
    ) {
        $this->total       = $total;
        $this->offset      = $offset;
        $this->limit       = $limit;
        $this->status_code = $statusCode;
        $this->meta        = $meta;
        $this->data        = $this->formingData($data);
    }

    private function formingData(array $data = []): array
    {
        return array_merge(
            [
                'total'  => $this->total,
                'offset' => $this->offset,
                'limit'  => $this->limit,
            ],
            $data
        );
    }
}
