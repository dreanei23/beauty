<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use \yii\web\View;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'https://api-maps.yandex.ru/2.1/?apikey=95a620d3-e7ab-49ef-861d-8ad5b5b75ec6&lang=ru_RU'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public $jsOptions = ['position' => View::POS_HEAD];
}
