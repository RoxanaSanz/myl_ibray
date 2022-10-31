<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UserSearch;
use app\models\User;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'list', 'signup', 'insert', 'update', 'edit', 'view', 'delete'],
                'rules' => [
                    [
                        'actions' => ['logout', 'list', 'signup', 'insert', 'update', 'edit', 'view', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
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
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
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

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
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

    public function actionList()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSignup()
    {
        $model = new User();

        return $this->render('signup', ['model' => $model]);
    }

    public function actionInsert()
    {
        $request = Yii::$app->request->post();

        if ($request !== []) {

            $data = (object)$request['User'];

            $responseInsert = Yii::$app->db->createCommand()
                ->insert('user', [
                    'username' => $data->username,
                    'email' => $data->email,
                    'password_hash' => Yii::$app->security->generatePasswordHash($data->password),
                    'auth_key' => Yii::$app->security->generateRandomString(),
                    'verification_token' => Yii::$app->security->generateRandomString() . '_' . time(),
                ])
                ->execute();

            if (is_numeric($responseInsert)) {
                Yii::$app->session->setFlash('success', 'El usuario se ha registrado correctamente');

                return $this->actionList();
            } else {
                $this->actionSignup();
            }
        } else {
            return $this->actionList();
        }
    }

    /**
     * Displays a single User model.
     * @param int $id Id User
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id Id User
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id Id User
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        return $this->render('update', ['model' => $model]);
    }

    function actionUpdate()
    {
        $request = Yii::$app->request->post();

        if ($request !== []) {
            $data = (object)$request['User'];
            $data->id = $request['id'];

            $responseUpdate = Yii::$app->db->createCommand()
                ->update('user', [
                    'username' => $data->username,
                    'email' => $data->email,
                    'password_hash' => Yii::$app->security->generatePasswordHash($data->password)
                ], ['id' => $data->id])
                ->execute();

            if (is_numeric($responseUpdate)) {
                Yii::$app->session->setFlash('success', 'El usuario se ha actualizado correctamente');

                return $this->actionList();
            } else {
                $this->actionEdit($data->id);
            }
        } else {
            return $this->actionList();
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id Id User
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();

        Yii::$app->session->setFlash('success', 'El usuario se ha eliminado correctamente');
        return $this->actionList();
    }
}
