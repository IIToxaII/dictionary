<?php

namespace app\helpers;

use yii\data\ActiveDataProvider;
use \yii\grid\GridView;
use app\helpers\ModalWithExternalButton;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

class Factory
{
    public static function GetDictionaryView($model, $pageSize, $canManage = false){

        $crsf = \Yii::$app->request->getCsrfToken();
        $url = Url::to('/dictionary/remove-words');
        $dataProvider = new ActiveDataProvider([
            'query' => $model->WordsQuery(),
            'pagination' => ['pageSize' => $pageSize]
        ]);

        $columns = [
            ['class' => 'yii\grid\SerialColumn'],
            'text',
            'sense',
        ];

        if ($canManage)
        {
            $deleteButton = \yii\bootstrap\Button::widget([
                'label' => 'delete',
                'options' => ['class' => 'btn btn-sm btn-danger', 'id' => 'remove', 'data-url' => "$url",
                    'data-crsf' => "$crsf", 'data-id_dictionary' => "$model->id_dictionary"]
            ]);

            $addButton = \yii\bootstrap\Button::widget([
                'label' => 'Add',
                'options' => ['class' => 'btn btn-sm btn-success', 'id' => 'modal-addWord']
            ]);

            array_push($columns, [
                'class' => 'yii\grid\CheckboxColumn',
                'header' => "$deleteButton $addButton"
            ]);
        }

        return GridView::widget([
            'id' => 'grid-widget',
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model, $key, $index, $grid){
                return ['id' => $key . "-word"];
            },
            'columns' => $columns
        ]);
    }

    public static function GetOwnDictionaryView($model, $pageSize)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => ['pageSize' => $pageSize]]);

        return GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model, $key, $index, $grid){
            },
            'columns' => [
                ['class' => \yii\grid\SerialColumn::className()],
                'name',
                ['class' => \yii\grid\Column::className(),
                    'header' => 'Word count',
                    'content' => function($model, $key, $index, $grid)
                    {
                        return $model->WordsCount();
                    }],
                [
                    'class' => \yii\grid\Column::className(),
                    'header' => Html::button('Create', ['class' => 'btn btn-success', 'id' => 'create']) . " " .
                        Html::input('text', 'name', '', ['class' => 'form-control', 'id' => 'dictionary-name']),
                    'content' => function($model, $key, $index, $grid)
                    {
                        $open = Html::button('Open', ['class' => 'btn btn-primary dictionary', 'id' => $model->id_dictionary]);
                        $delete = Html::button('Delete', ['class' => 'btn btn-danger dictionary-delete', 'id' => $model->id_dictionary]);
                        $toggleLabel = $model->isPublic ? "Make private" : "Make public";
                        $toggle = Html::button("$toggleLabel", ['class' => 'btn btn-danger dictionary-toggle', 'id' => $model->id_dictionary]);
                        return "$open $delete $toggle";
                    }
                ]
            ]
        ]);
    }
}