<?php

namespace common\components\rest\response;

use exceptions\AbstractException;
use exceptions\Interfaces\ValidationErrorInterface;
use Error;
use Exception;
use Throwable;
use Yii;
use yii\base\Event;
use yii\web\Response;

class BeforeSendResponseHandle
{
    private ?Event $event = null;

    public function handle(Event $event)
    {
        $this->event                = $event;
        Yii::$app->response->format = Response::FORMAT_JSON;

        /** @var Error|Exception|Throwable $exception */
        $exception = Yii::$app->errorHandler->exception;
        if ($exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @param Error|Exception|Throwable $exception
     */
    private function handleException($exception): void
    {
        /** @var Response $response */
        $response         = $this->event->sender;
        $message          = $response->data['message'] ?? null;
        $response->format = Response::FORMAT_JSON;
        if ($exception instanceof AbstractException) {
            $response->setStatusCode($exception->getStatusCode());
        }

        if ($exception instanceof ValidationErrorInterface) {
            $this->event->sender->data = [
                'status_code' => $exception->getCode(),
                'meta'        => YII_DEBUG ? ['trace' => explode("\n", $exception->getTraceAsString())] : null,
                'data'        => null,
                'errors'      => $exception->getErrors(),
            ];
        } else {
            $this->event->sender->data = [
                'status_code' => $exception->getCode(),
                'meta'        => YII_DEBUG ? ['trace' => explode("\n", $exception->getTraceAsString())] : null,
                'data'        => null,
                'errors'      => [
                    [
                        'message' => Yii::t('app', $message ?? $exception->getMessage()),
                    ],
                ],
            ];
        }
    }
}
