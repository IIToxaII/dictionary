<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use app\helpers\Factory;

$canManage = Yii::$app->user->can('manageOwnDictionary', ['dictionary' => $model]);
$crsf = \Yii::$app->request->getCsrfToken();
$urlLearn = Url::to(['dictionary/learn', 'id' => $model->id_dictionary]);
$urlCopy = Url::to(['dictionary/copy']);

$script = <<<JS
$('#learn').click(function() {
    window.location = "$urlLearn";
})
JS;

if (!Yii::$app->user->isGuest){
    $script1 = <<<JS
    var copyButton = $('#copy');
    copyButton.click(function() {
        copyButton.prop('disabled', true);
        $.post("$urlCopy", {_crsf : "$crsf", id_dictionary : $model->id_dictionary}, function(data) {
            var rData = JSON.parse(data);
            if (rData.result == 1) 
                {
                    copyButton.prop('disabled', false);
                    copyButton.text('Copied');
                }
        });
    })
JS;
}

$this->registerJs($script, View::POS_END);
$this->registerJs($script1, View::POS_END);
\app\assets\ShowAsset::register($this);
?>

<?php if($canManage){ ?>
<div class="overlay">
    <div class="popup-dialog">
        <div class="popup-content">
            <div class="popup-header">
                <h4>Add Word</h4>
                <div class="close"></div>
            </div>
            <div class="popup-body">
                <?php $form = ActiveForm::begin(['id' => 'add-word']); ?>
                <?= $form->field($wordModel, 'text')->textInput(); ?>
                <?= $form->field($wordModel, 'sense')->textInput(); ?>
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'add-button']); ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php }?>
<div id="dictionary-view">
    <?= Factory::GetDictionaryView($model, 40, $canManage) ?>
</div>



<?= Html::button("Learn", ['class' => 'btn btn-primary', 'id' => 'learn']) ?>
<?php
if (!Yii::$app->user->isGuest) {
    echo Html::button("Copy", ['class' => 'btn btn-primary', 'id' => 'copy']);
}
?>


