<?php

namespace app\controllers;

use app\models\LibroForm;
use Yii;
use yii\web\Controller;

class LibroController extends Controller
{
    public function actionIndex()
    {
        $libroModel =  new LibroForm();

        if ($libroModel->load(\Yii::$app->request->post())) {
            return Yii::$app->getResponse()->redirect('add');
        }

        return $this->render('index', ['libroModel' => $libroModel]);
    }

    public function actionAdd()
    {
        return 'Se ha consultado la función actionAddBook desde el controlador Book';
    }
}
