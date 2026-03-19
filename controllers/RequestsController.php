<?php

namespace app\controllers;

use app\forms\CreateLoanRequestForm;
use app\services\LoanRequestService;
use Yii;
use yii\web\Response;

class RequestsController extends ApiController
{
    public $enableCsrfValidation = false;

    public function verbs(): array
    {
        return [
            'create' => ['POST'],
        ];
    }

    public function actionCreate(): array
    {
        $form = new CreateLoanRequestForm();
        $form->load(Yii::$app->request->getBodyParams(), '');

        $service = new LoanRequestService();
        $request = $service->create($form);

        if ($request === null) {
            Yii::$app->response->statusCode = Response::HTTP_BAD_REQUEST;

            return ['result' => false];
        }

        Yii::$app->response->statusCode = Response::HTTP_CREATED;

        return [
            'result' => true,
            'id' => $request->id,
        ];
    }
}
