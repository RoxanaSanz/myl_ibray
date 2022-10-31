<?php

use app\models\Book;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BookSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Listado de libros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <p class="py-3">
        <?= Html::a('Nuevo libro', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idBook',
            'nameBook',
            'editorial',
            'author',
            [
                'header' => 'Estatus',
                'attribute' => 'available',
                'format' => 'text',
                'value' => function ($data) {
                    return Html::encode(($data->available == 1) ? 'Disponible' : 'No disponible');
                },
            ],
            [
                'header' => 'Imágen',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img('@web/' . $data->image, ['width' => '80px']);
                },
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Book $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'idBook' => $model->idBook]);
                }
            ],
        ],
    ]); ?>


</div>