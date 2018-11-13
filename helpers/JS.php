<?php
namespace app\helpers;

class JS
{
    public static function ConvertPHPWordArray($model, $name)
    {
        $script = "";
        foreach ($model as $word)
        {
            $script = $script . "$name.push([ \"$word->text\", \"$word->sense\"]);";
        }
        return $script;
    }
}