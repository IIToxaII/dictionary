<?php

namespace app\controllers;

use app\models\LearnSession;
use app\models\Word;
use Yii;
use app\models\Dictionary;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use app\helpers\Factory;
use \app\models\SignupForm;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class DictionaryController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['show-collection', 'create', 'signup', 'logout', 'login'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['show-collection', 'create', 'logout'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['signup', 'login'],
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dictionaries = Dictionary::find()->where(['<>', 'isPublic', 0])->leftJoin('user', 'dictionary.id_user = user.id_user');
        return $this->render('index', ['model' => $dictionaries]);
    }

    public function actionCopy()
    {
        $id_dictionary = Yii::$app->request->post('id_dictionary');
        $newDictionary = new Dictionary();
        $newDictionary->Copy($id_dictionary);

        return Json::encode(['result' => 1]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionShowCollection()
    {
        $collection = Dictionary::find()->where(['id_user' => Yii::$app->user->id]);
        return $this->render('show-collection', ['collection' => $collection]);
    }

    public function actionCreate(){

        $model = new Dictionary();
        $name = Yii::$app->request->post('name');
        $model->name = $name;
        $model->id_user = Yii::$app->user->id;

        if (!Yii::$app->user->can('createDictionary') || !$model->Create()) return "Error";
        return Factory::GetOwnDictionaryView(Dictionary::find()->where(['id_user' => Yii::$app->user->id]), 40);
    }

    public function actionShow($id)
    {
        $word = new Word();
        if ($word->load(Yii::$app->request->post()) && $word->validate())
        {
            $word->Add($id);
        }

        $dictionary = Dictionary::findOne($id);

        return $this->render('show',['model' => $dictionary, 'wordModel' => new Word()]);

    }

    public function actionRemoveWords()
    {
        $id_words = Yii::$app->request->post('id_words');
        $id_dictionary = Yii::$app->request->post('id_dictionary');
        $dictionary = Dictionary::findOne($id_dictionary);
        if (!Yii::$app->user->can('editDictionary', ['dictionary' => $dictionary])) return "Error";
        $words = Word::findAll($id_words);
        $dictionary->DeleteWords($words);
        $data = ["answer" => "success", "html" => Factory::GetDictionaryView($dictionary, 40, true)];
        $json = Json::encode($data);
            return $json;
    }

    public function actionDelete()
    {
        $id_dictionary = Yii::$app->request->post('id_dictionary');
        $dictionary = Dictionary::findOne($id_dictionary);
        if (!Yii::$app->user->can('deleteDictionary', ['dictionary' => $dictionary])) return "Error";
        $dictionary->delete();
        return Factory::GetOwnDictionaryView(Dictionary::find()->where(['id_user' => Yii::$app->user->id]), 40);
    }

    public function actionToggle()
    {
        $id_dictionary = Yii::$app->request->post('id_dictionary');
        $dictionary = Dictionary::findOne($id_dictionary);
        if (!Yii::$app->user->can('manageOwnDictionary', ['dictionary' => $dictionary])) return "Error";

        return $dictionary->TogglePublic();
    }

    public function actionLearn($id)
    {
        return $this->render('learn',['model' => Dictionary::findOne($id)]);
    }

    public function actionInit()
    {
        $am = Yii::$app->authManager;
        /*$role = Yii::$app->authManager->createRole("user");
        $role->description = "User";
        Yii::$app->authManager->add($role);

        $permit1 = Yii::$app->authManager->createPermission("editDictionary");
        $permit1->description = "Edit dictionary";
        Yii::$app->authManager->add($permit1);

        $permit2 = Yii::$app->authManager->createPermission("createDictionary");
        $permit2->description = "Create dictionary";
        Yii::$app->authManager->add($permit2);

        $permit3 = Yii::$app->authManager->createPermission("deleteDictionary");
        $permit3->description = "Delete dictionary";
        Yii::$app->authManager->add($permit3);

        $rule = new ManageRule();
        $am->add($rule);

        $permit4 = $am->createPermission('manageOwnDictionary');
        $permit4->description = "Edit, delete dictionary";
        $permit4->ruleName = $rule->name;
        $am->add($permit4);

        $am->addChild($role, $permit2);
        $am->addChild($role, $permit4);
        $am->addChild($permit4, $permit1);
        $am->addChild($permit4, $permit3);*/

        /*$am->assign($am->getRole('user'), 1);
        $am->assign($am->getRole('user'), 2);
        $am->assign($am->getRole('user'), 3);*/
        return 16458132;
    }

    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
}