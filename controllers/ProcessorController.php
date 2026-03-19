<?php

namespace app\controllers;

use app\services\LoanProcessorService;
use Yii;
use yii\web\Response;

class ProcessorController extends ApiController
{
    public function verbs(): array
    {
        return [
            'index' => ['GET'],
        ];
    }

    public function actionIndex(): array
    {
        $delay = filter_var(Yii::$app->request->get('delay'), FILTER_VALIDATE_INT);

        if ($delay === false || $delay < 0) {
            Yii::$app->response->statusCode = Response::HTTP_BAD_REQUEST;

            return ['result' => false];
        }

        (new LoanProcessorService())->processPending((int) $delay);

        return ['result' => true];
    }
}
