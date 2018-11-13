<?php
/**
 * Created by PhpStorm.
 * User: djtoryx
 * Date: 04.10.2018
 * Time: 19:03
 */

namespace app\assets;

use yii\web\AssetBundle;

class ShowCollectionAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css\show-collection.css'
    ];
    public $js = [
    ];
    public $depends = [
    ];
}
