<?php

namespace common\components\rest\serializer;

interface ResponseSerializerInterface
{
    /**
     * @param mixed|object|array $data
     *
     * @return mixed
     */
    public function serialize($data);
}
