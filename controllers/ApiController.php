<?php
declare(strict_types=1);

namespace app\controllers;

use app\actions\SwaggerUiAction;
use OpenApi\Generator;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use Yii;

class ApiController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => SwaggerUiAction::class,
                'sourceUrl' => Url::to(['swagger']),
            ],
        ];
    }

    public function actionSwagger()
    {
        Yii::$app->response->format=Response::FORMAT_JSON;
        return Generator::scan([
            Yii::getAlias('@app/api/modules/v1'),
            Yii::getAlias('@app/common/Api/Adapters/Http/Contract'),
        ]);
    }
}