<?php
/**
 * Created by PhpStorm.
 * User: djtoryx
 * Date: 03.10.2018
 * Time: 17:07
 */

namespace app\assets;

use yii\web\AssetBundle;

class ShowAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js\ManageDictionary.js'
    ];
    public $depends = [
        '\yii\web\JqueryAsset',
        '\yii\grid\GridViewAsset'
    ];
}