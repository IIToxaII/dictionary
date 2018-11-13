<?php
/**
 * Created by PhpStorm.
 * User: djtoryx
 * Date: 30.09.2018
 * Time: 15:02
 */

namespace app\rbac;

use yii\rbac\Rule;

class ManageRule extends Rule
{
    public $name = "Manage rule";

    public function execute($user, $item, $params)
    {
        return isset($params['dictionary']) ? $params['dictionary']->id_user == $user : false;
    }
}