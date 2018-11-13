<?php

use yii\helpers\Url;
use yii\web\View;


$this->title = "My Dictionaries";
$urlShow = Url::to(['dictionary/show']);
$urlDelete = Url::to(['dictionary/delete']);
$urlCreate = Url::to(['dictionary/create']);
$urlToggle = Url::to(['dictionary/toggle']);
$crsf = \Yii::$app->request->getCsrfToken();

$script = <<<JS
$(".content").on('click', '.dictionary', function(){
    window.location = "$urlShow?id=" + $(this).attr('id');
});
$(".content").on('click', '.dictionary-delete', function(){
    $(this).prop('disabled', true)
    $('.content').load("$urlDelete", {_crsf: "$crsf", id_dictionary: $(this).attr('id')});
});
$(".content").on('click', '.dictionary-toggle', function(){
    var butt = $(this);
    $.post("$urlToggle", {_crsf: "$crsf", id_dictionary: butt.attr('id')}, function(data) {
      butt.text(data != 0 ? "Make private" : "Make public"); 
    });
});
$(".content").on('click', '#create', function(){
    $('.content').load("$urlCreate", {_crsf: "$crsf", name: $("#dictionary-name").val()});
});
JS;

$this->registerJs($script, View::POS_END);
\app\assets\ShowCollectionAsset::register($this);
?>

<h1>My Dictionaries</h1>

<div class="content">
<?=
    \app\helpers\Factory::GetOwnDictionaryView($collection, 40);
?>
</div>
