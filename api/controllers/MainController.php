<?php

namespace api\controllers;

use yii\rest\Controller;

class MainController extends Controller
{

    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        
        return [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'code' => $exception->getCode(),
            'type' => get_class($exception),
            'trace' => $exception->getTrace()
        ];
    }

}
