<?php

namespace app\assets;

use yii\web\AssetBundle;

class SwaggerAsset extends AssetBundle
{
    public $sourcePath = '@bower/swagger-ui/dist';

    public $js = [
        'swagger-ui-bundle.js',
        'swagger-ui-standalone-preset.js',
    ];

    public $css = [
        'swagger-ui.css',
    ];
}