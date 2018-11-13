<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$this->title = "Dictionaries";
$url = Url::to(['dictionary/show']);

$script = <<<JS
$(".dictionary").click(function() {
    window.location = "$url?id=" + $(this).attr('id');
})
JS;

$this->registerJs($script, View::POS_END);
?>

<h1>Dictionaries</h1>
<ul>
    <?php
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => ['pageSize' => 40],
        ]);

        echo GridView::widget([
           'dataProvider' => $dataProvider,
           'columns' =>[
               ['class' => '\yii\grid\SerialColumn'],
               'name',
               [
                   'attribute' => 'Username',
                   'value' => 'user.username',
               ],
               [
                   'content' => function($model, $key, $index, $grid)
                   {
                       $open = Html::button('Open', ['class' => 'btn btn-primary dictionary', 'id' => $model->id_dictionary]);
                       return "$open";
                   }
               ]

           ]
        ]);
    ?>
</ul>
