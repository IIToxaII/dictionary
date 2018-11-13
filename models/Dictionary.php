<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * Dictionary model
 *
 * @property integer $id_dictionary
 * @property string $name
 * @property integer $id_user
 * @property integer $isPublic
 *
 */

class Dictionary extends ActiveRecord
{

    public function rules()
    {
        return[
          [['name'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return[
            'name' => 'Name',
        ];
    }

    public function Create()
    {
        if ($this->validate())
        {
            $this->save();
            return true;
        }
        return false;
    }

    public function Copy($id_oldDictionary)
    {
        $oldDictionary = Dictionary::findOne($id_oldDictionary);
        $this->name = $oldDictionary->name;
        $userID = Yii::$app->user->id;
        $this->id_user = $userID;
        $this->Create();
        $id = $this->id_dictionary;
        $words = $oldDictionary->Words();
        foreach ($words as $word){
            $newWord = new Word();
            $newWord->text = $word->text;
            $newWord->sense = $word->sense;
            $newWord->Add($id);
        }
    }

    public function DeleteWords($words)
    {
        foreach ($words as $word)
        {
            if ($word->id_dictionary == $this->id_dictionary)
            {
                $word->delete();
            }
        }
    }

    public function TogglePublic($forceValue = null)
    {
        if (isset($forceValue))
        {
            $this->isPublic = $forceValue;
            if($this->validate())
            {
                $this->save();
                return $forceValue;
            }
        }

        $isPublic = $this->isPublic ? 0 : 1;
        $this->isPublic = $isPublic;
        $this->save();
        return $isPublic;
    }

    public function delete()
    {
        $this->DeleteWords($this->Words());
        return parent::delete();
    }

    public function Words()
    {
        return Word::find()->where(['id_dictionary' => $this->id_dictionary])->all();
    }

    public function WordsQuery()
    {
        return Word::find()->where(['id_dictionary' => $this->id_dictionary]);
    }

    public function WordsCount()
    {
        return $this->WordsQuery()->count();
    }

    public function getUser()
    {
        return Dictionary::hasOne(User::className(),['id_user' => 'id_user']);
    }
}