<?php

namespace app\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;

class SwaggerUiAction extends Action
{
    public $sourceUrl;
    public $view = '@app/views/api/index';

    public $layout = false;

    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_HTML;
        $this->controller->layout = $this->layout;

        return $this->controller->render($this->view, [
            'sourceUrl' => $this->sourceUrl,
        ]);
    }
}