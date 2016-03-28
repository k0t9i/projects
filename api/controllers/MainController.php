<?php

namespace api\controllers;

use yii\rest\Controller;

/**
 * Main conroller of applicaion
 */
class MainController extends Controller
{

    /**
     * Default action for error handling
     * 
     * @return array
     */
    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;

        return [
            'message' => $exception->getMessage(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            'code'    => $exception->getCode(),
            'type'    => get_class($exception)
        ];
    }

}
