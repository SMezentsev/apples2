<?php

namespace common\components\rest\interfaces;

interface ApiControllerInterface
{
    public function getCollectionEnvelope(): string;

    public function getResponseModel(): string;
}
