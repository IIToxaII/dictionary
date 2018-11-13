<?php
use \yii\bootstrap\Button;
?>

<div id=<?= "item-$index" ?>>
    <?= $index ?>
    <?= $model->text ?>
    <?= $model->sense ?>
    <?= Button::widget([
        'label' => 'delete',
        'options' => ['class' => 'btn btn-danger',
                      "onclick($model->id_word)"]
]) ?>
</div>
