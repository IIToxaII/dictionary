<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\helpers\JS;
use yii\web\View;

$forfunc = JS::ConvertPHPWordArray($model->Words(), 'words');
$url = Url::to(['dictionary/show', 'id' => $model->id_dictionary]);

$script = <<<JS
var words = [];
$forfunc
$('#text').text(words[0][0]);
$('#counter').text(1 + "/" + words.length);
var current = 0;
var state = 0;
$('.progress-bar').css("width", 0 + "%");
function ButtonClick() {
  if (state == 0) {
      $('#sense').text(words[current][1]);
      state = 1;
      $('.progress-bar').css("width", 100 * ((current + 1) / words.length) + "%");
  }
  else {
      if (current < words.length - 1) {
          current++;
      }
      else {
          current = 0;
      }
      $('#text').text(words[current][0]);
      $('#sense').text("--");
      state = 0;
  }
}
$('#show').click(ButtonClick);
$('#back').click(function() {
    window.location = "$url";
})
JS;

$this->registerJs($script, View::POS_END);

?>
<div class="progress">
    <div class="progress-bar progress-bar-success progress-bar-striped" style="width: 0%">

    </div>
</div>
<div id="text" style="width: 100%;"  align="center">

</div>
<div id="sense" style="width: 100%;"  align="center">
--
</div>

<div style="width: 100%">
    <?= Html::button('Show',['id' => 'show', 'class' => 'btn btn-primary']) ?>
    <div style="float: right">
        <?= Html::button('Back',[ 'class' => 'btn btn-default', 'id' => 'back']) ?>
    </div>
</div>
