<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Word model
 *
 * @property integer $id_word
 * @property string $text
 * @property string $sense
 * @property integer $id_dictionary
 */

class Word extends ActiveRecord
{
    public function rules()
    {
        return[
            [['text', 'sense'], 'required'],
        ];
    }

    public function Add($id_dictionary)
    {
        if ($this->validate())
        {
            $this->id_dictionary = $id_dictionary;
            $this->save();
            return true;
        }
        return false;
    }
}