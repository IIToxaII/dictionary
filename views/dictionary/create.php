<?php

/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\Dictionary */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Create';
?>

<h1>Create</h1>

<?php $form = ActiveForm::begin(['id' => 'create-form']); ?>
      <?= $form->field($model, 'name')->textInput(['autofocus' => true]); ?>
      <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'create-button']); ?>

<?php ActiveForm::end(); ?>