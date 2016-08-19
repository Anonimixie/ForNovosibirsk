<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\Payments;
use yii\helpers\Html;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $sUsers = $sUsersTable = '';

        foreach ((new User)->getList() as $user) {
            $sUserName = Html::encode($user['name']);
            $sUsers .= '<option value="'.$user['id'].'">'.$sUserName.'</option>' . "\n";
            $sUsersTable .= '<tr><td>'.$user['id'].'</td><td>'.$sUserName.'</td><td>'.$user['balance'].'</td></tr>' . "\n";
        }

        return $this->render('index', [
            'users' => $sUsers,
            'usersTableArray' => $sUsersTable
        ]);
    }

    public function actionPayment() {
        $fromUser = Yii::$app->request->post('from_user');
        $toUser = Yii::$app->request->post('to_user');
        $amount = Yii::$app->request->post('amount');

        $sUsers = $sUsersTable = '';



        $error = '';

        try {
            (new Payments())->transfer((int)$fromUser, (int)$toUser, $amount);
        } catch (\Exception $e) {
            $error = $e->getMessage();


            foreach ((new User)->getList() as $user) {
                $sUserName = Html::encode($user['name']);
                $sUsers .= '<option value="'.$user['id'].'">'.$sUserName.'</option>' . "\n";
                $sUsersTable .= '<tr><td>'.$user['id'].'</td><td>'.$sUserName.'</td><td>'.$user['balance'].'</td></tr>' . "\n";
            }


            return $this->render('index', [
                'users' => $sUsers,
                'usersTableArray' => $sUsersTable,
                'error' => $error
            ]);
        }


        return $this->redirect(['/']);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
