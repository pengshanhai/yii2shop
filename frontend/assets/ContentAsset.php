<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19
 * Time: 15:22
 */
namespace frontend\assets;

use yii\web\AssetBundle;

class ContentAsset extends AssetBundle{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/footer.css',
        'style/bottomnav.css',
    ];
    public $js = [
        'js/header.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}