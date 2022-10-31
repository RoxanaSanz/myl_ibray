<?php

namespace app\controllers;

use Yii;
use app\models\Book;
use app\models\BookSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\data\Pagination;
use yii\db\Query;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['index', 'view', 'create', 'update', 'delete', 'loanbook', 'leturnbook', 'loancontrol'],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@']
                        ]
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Book model.
     * @param int $idBook Id Book
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($idBook)
    {
        return $this->render('view', [
            'model' => $this->findModel($idBook),
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Book();

        $this->uploadImage($model, 1);

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $idBook Id Book
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($idBook)
    {
        $model = $this->findModel($idBook);

        $this->uploadImage($model, 2);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $idBook Id Book
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($idBook)
    {
        $model = $this->findModel($idBook);

        if (file_exists($model->image)) {
            unlink($model->image);
        }

        $model->delete();

        Yii::$app->session->setFlash('success', 'El libro se ha eliminado correctamente');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $idBook Id Book
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idBook)
    {
        if (($model = Book::findOne(['idBook' => $idBook])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function uploadImage(Book $model, $type)
    {
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->file = UploadedFile::getInstance($model, 'file');

                if ($model->validate()) {
                    if ($model->file) {
                        if (file_exists($model->image)) {
                            unlink($model->image);
                        }

                        $routeFile = 'uploads/' . time() . '_' . $model->file->baseName . '.' . $model->file->extension;

                        if ($model->file->saveAs($routeFile)) {
                            $model->image = $routeFile;
                        }
                    }
                }

                if ($model->save(false)) {
                    if ($type == 1) {
                        Yii::$app->session->setFlash('success', 'El libro se ha capturado correctamente');
                    } else {
                        Yii::$app->session->setFlash('success', 'La información del libro se ha actualizado correctamente');
                    }

                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }
    }

    public function actionList()
    {
        $modelBook = Book::find();
        $pagination = new Pagination([
            'defaultPageSize' => 3,
            'totalCount' => $modelBook->count()
        ]);

        $arrayBooks = $modelBook->orderBy('nameBook')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        if (isset(Yii::$app->user->identity->ID)) {
            $idUser = Yii::$app->user->identity->ID;

            $loanBooks = (new Query())->select([
                'book.idBook'
            ])
                ->from('loan_control AS lc')
                ->leftJoin('book', 'book.idBook = lc.idBook')
                ->leftJoin('user', 'user.id = lc.idUser')
                ->where('book.available=1')
                ->where('active=1')
                ->where('idUser=:idUser', [':idUser' => $idUser])
                ->distinct()
                ->all();
        } else {
            $loanBooks = [];
        }


        return $this->render('list', ['arrayBooks' => $arrayBooks, 'loanBooks' => $loanBooks, 'pagination' => $pagination]);
    }

    /* LOAN CONTROL */
    public function actionLoanbook($idBook)
    {
        $idUser = Yii::$app->user->identity->ID;
        $date = Yii::$app->formatter->asDate('now', 'Y-M-d');

        $responseInsert = Yii::$app->db->createCommand()
            ->insert('loan_control', [
                'idBook' => $idBook,
                'idUser' => $idUser,
                'dateStart' => $date,
                'active' => 1,
            ])
            ->execute();

        $responseUpdate = Yii::$app->db->createCommand()
            ->update('book', [
                'available' => 0
            ], ['idBook' => $idBook])
            ->execute();

        if (is_numeric($responseInsert) && is_numeric($responseUpdate)) {
            Yii::$app->session->setFlash('success', 'La solicitud del libro ha sido dada de alta correctamente, más tarde soporte le dará continuidad a su solicitud');
        } else {
            Yii::$app->session->setFlash('error', 'Ocurrió un error al solicitar el préstamo, favor de intentar más tarde');
        }

        return $this->actionList();
    }

    public function actionReturnbook($idBook)
    {
        $idUser = Yii::$app->user->identity->ID;
        $date = Yii::$app->formatter->asDate('now', 'Y-M-d');

        $responseInsert = Yii::$app->db->createCommand()
            ->update('loan_control', [
                'active' => 0,
                'dateEnd' => $date,
            ], ['idBook' => $idBook, 'idUser' => $idUser])
            ->execute();

        $responseUpdate = Yii::$app->db->createCommand()
            ->update('book', [
                'available' => 1
            ], ['idBook' => $idBook])
            ->execute();

        if (is_numeric($responseInsert) && is_numeric($responseUpdate)) {
            Yii::$app->session->setFlash('success', 'La solicitud para la devolución del libro ha sido dada de alta correctamente, más tarde soporte le dará continuidad a su solicitud');
        } else {
            Yii::$app->session->setFlash('error', 'Ocurrió un error al generar la solicitud de devolución del libro, favor de intentar más tarde');
        }

        return $this->actionList();
    }

    public function actionLoancontrol()
    {
        $query = (new Query())->select([
            'user.username AS Usuario',
            'book.nameBook',
            'book.image',
            'lc.dateStart'
        ])
            ->from('loan_control AS lc')
            ->leftJoin('book', 'book.idBook = lc.idBook')
            ->leftJoin('user', 'user.id = lc.idUser')
            ->where('book.available=1')
            ->where('active=1')->distinct();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('listloancontrol', ['arrayBooks' => $dataProvider]);
    }
}
